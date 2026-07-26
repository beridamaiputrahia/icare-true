<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Identitas/label peran superadmin di dalam satu I Care Group tertentu --
 * murni tampilan, tidak memengaruhi hak akses (superadmin selalu punya akses
 * penuh di mana pun). Lihat User::displayRoleFor().
 */
class SuperadminTenantRole extends Model
{
    protected $fillable = ['user_id', 'tenant_id', 'role'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
