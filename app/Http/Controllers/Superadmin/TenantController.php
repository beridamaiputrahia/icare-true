<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\DailyVerse;
use App\Models\Member;
use App\Models\SuperadminTenantRole;
use App\Models\Tenant;
use App\Models\User;
use App\Services\BibleApiService;
use Database\Seeders\AppSettingSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Throwable;

/**
 * CRUD daftar tenant + halaman "pilih tenant" (tenant switcher) untuk superadmin.
 * Superadmin tidak terikat tenant manapun; semua akses ke data tenant-scoped
 * (jadwal, anggota, dst) dilakukan dengan "masuk sebagai" salah satu tenant
 * lewat halaman ini, disimpan di session (lihat BelongsToTenant::resolveTenantId()).
 */
class TenantController extends Controller
{
    public function index(): View
    {
        $tenants = Tenant::visible()->withCount('users')->orderBy('nama_perusahaan')->get();

        return view('superadmin.tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        return view('superadmin.tenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'email'           => ['nullable', 'string', 'email', 'max:255'],
            'is_active'       => ['nullable', 'boolean'],
        ]);

        $data['slug']      = $this->generateUniqueSlug($data['nama_perusahaan']);
        $data['is_active'] = $request->boolean('is_active', true);

        $tenant = Tenant::create($data);

        // Tanpa ini, halaman Pengaturan Aplikasi tampil kosong untuk tenant baru
        // karena AppSetting di-scope per tenant (BelongsToTenant) dan tidak ada
        // baris default yang otomatis dibuat selain lewat seeder ini.
        (new AppSettingSeeder)->run($tenant->id, $tenant->nama_perusahaan);

        // Ruang chat global & leader dibutuhkan agar halaman Live Chat tidak
        // kosong sejak awal -- dibuat eksplisit di sini karena Conversation
        // pakai BelongsToTenant yang butuh user login untuk auto-isi tenant_id,
        // sedangkan controller ini dijalankan oleh superadmin (tenant_id null).
        Conversation::withoutTenantScope()->create([
            'type'      => Conversation::TYPE_GLOBAL,
            'tenant_id' => $tenant->id,
        ]);
        Conversation::withoutTenantScope()->create([
            'type'      => Conversation::TYPE_LEADER,
            'tenant_id' => $tenant->id,
        ]);

        // Tanpa ini, ayat harian kosong untuk tenant baru sampai giliran
        // verse:generate-daily berikutnya jam 06:00 -- bisa lewat berhari-hari.
        // Gagal-aman: kalau API.Bible down/key belum diset, tenant tetap
        // berhasil dibuat, cuma ayat harian pertamanya menyusul di jadwal.
        try {
            $verse = app(BibleApiService::class)->randomVerse();

            DailyVerse::withoutTenantScope()->create([
                'ayat'      => $verse['ayat'],
                'referensi' => $verse['referensi'],
                'tanggal'   => today(),
                'is_active' => true,
                'tenant_id' => $tenant->id,
            ]);
        } catch (Throwable $e) {
            report($e);
        }

        return redirect()->route('superadmin.tenants.index')->with('success', 'I Care Group berhasil dibuat.');
    }

    public function edit(Tenant $tenant): View
    {
        return view('superadmin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $data = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'email'           => ['nullable', 'string', 'email', 'max:255'],
            'is_active'       => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $tenant->update($data);

        return redirect()->route('superadmin.tenants.index')->with('success', 'I Care Group berhasil diperbarui.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        if ($tenant->users()->exists()) {
            return back()->with('error', 'I Care Group tidak bisa dihapus karena masih memiliki anggota.');
        }

        $tenant->delete();

        return redirect()->route('superadmin.tenants.index')->with('success', 'I Care Group berhasil dihapus.');
    }

    // ── Tenant Switcher ──────────────────────────────────────────────

    public function select(): View
    {
        $tenants = Tenant::where('is_active', true)->orderBy('nama_perusahaan')->get();

        // Identitas yang sudah pernah dipilih superadmin ini di tiap grup,
        // supaya dropdown-nya menunjukkan pilihan terakhir, bukan selalu kosong.
        $identities = auth()->user()->superadminTenantRoles()->pluck('role', 'tenant_id');

        return view('superadmin.tenants.select', compact('tenants', 'identities'));
    }

    public function switch(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tenant_id'     => ['required', 'exists:tenants,id'],
            'identity_role' => ['nullable', Rule::in([User::ROLE_ANGGOTA, User::ROLE_ICL, User::ROLE_CTL, User::ROLE_ADMIN])],
        ]);

        session(['active_tenant_id' => (int) $data['tenant_id']]);

        if (! empty($data['identity_role'])) {
            $this->setIdentityRole(auth()->user(), (int) $data['tenant_id'], $data['identity_role']);
        }

        return redirect()->route('dashboard')->with('success', 'Berhasil masuk sebagai I Care Group terpilih.');
    }

    /**
     * Simpan/perbarui identitas tampilan superadmin di suatu tenant, dan
     * pastikan ada Member record di tenant itu supaya profil (nama panggilan,
     * foto, dst) punya tempat tersimpan per-grup -- sama seperti anggota biasa.
     */
    private function setIdentityRole(User $superadmin, int $tenantId, string $role): void
    {
        SuperadminTenantRole::updateOrCreate(
            ['user_id' => $superadmin->id, 'tenant_id' => $tenantId],
            ['role' => $role]
        );

        $hasMember = Member::withoutTenantScope()
            ->where('user_id', $superadmin->id)
            ->where('tenant_id', $tenantId)
            ->exists();

        if (! $hasMember) {
            Member::withoutTenantScope()->create([
                'user_id'      => $superadmin->id,
                'nama_lengkap' => $superadmin->name,
                'is_active'    => true,
                'created_by'   => $superadmin->id,
                'tenant_id'    => $tenantId,
            ]);
        }
    }

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;

        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
