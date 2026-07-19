<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\Tenant;
use Illuminate\Console\Command;

/**
 * TenantController::store() awalnya tidak membuat conversation global/leader
 * saat tenant baru dibuat, jadi halaman Live Chat kosong sejak awal untuk
 * semua tenant yang ada sebelum perbaikan itu ditambahkan. Command ini
 * dijalankan sekali untuk mengisi conversation yang hilang tersebut.
 */
class BackfillTenantConversations extends Command
{
    protected $signature   = 'chat:backfill-conversations';
    protected $description = 'Buat conversation global & leader untuk tenant yang belum punya';

    public function handle(): int
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $hasGlobal = Conversation::withoutTenantScope()
                ->where('tenant_id', $tenant->id)
                ->where('type', Conversation::TYPE_GLOBAL)
                ->exists();

            if (! $hasGlobal) {
                Conversation::withoutTenantScope()->create([
                    'type'      => Conversation::TYPE_GLOBAL,
                    'tenant_id' => $tenant->id,
                ]);
                $this->info("Global conversation dibuat untuk tenant: {$tenant->nama_perusahaan}");
            }

            $hasLeader = Conversation::withoutTenantScope()
                ->where('tenant_id', $tenant->id)
                ->where('type', Conversation::TYPE_LEADER)
                ->exists();

            if (! $hasLeader) {
                Conversation::withoutTenantScope()->create([
                    'type'      => Conversation::TYPE_LEADER,
                    'tenant_id' => $tenant->id,
                ]);
                $this->info("Leader conversation dibuat untuk tenant: {$tenant->nama_perusahaan}");
            }
        }

        $this->info('Selesai.');

        return self::SUCCESS;
    }
}
