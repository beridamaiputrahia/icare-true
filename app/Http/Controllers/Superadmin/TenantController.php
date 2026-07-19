<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

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
        $tenants = Tenant::withCount('users')->orderBy('nama_perusahaan')->get();

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

        return redirect()->route('superadmin.tenants.index')->with('success', 'Tenant berhasil dibuat.');
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

        return redirect()->route('superadmin.tenants.index')->with('success', 'Tenant berhasil diperbarui.');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        if ($tenant->users()->exists()) {
            return back()->with('error', 'Tenant tidak bisa dihapus karena masih memiliki anggota.');
        }

        $tenant->delete();

        return redirect()->route('superadmin.tenants.index')->with('success', 'Tenant berhasil dihapus.');
    }

    // ── Tenant Switcher ──────────────────────────────────────────────

    public function select(): View
    {
        $tenants = Tenant::where('is_active', true)->orderBy('nama_perusahaan')->get();

        return view('superadmin.tenants.select', compact('tenants'));
    }

    public function switch(Request $request): RedirectResponse
    {
        $request->validate(['tenant_id' => ['required', 'exists:tenants,id']]);

        session(['active_tenant_id' => (int) $request->tenant_id]);

        return redirect()->route('dashboard')->with('success', 'Berhasil masuk sebagai tenant terpilih.');
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
