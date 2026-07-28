<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'code', 'host_id', 'game_type',
        'status', 'seed', 'started_at', 'finished_at',
        'tenant_id',
    ];

    protected $casts = [
        'seed'        => 'array',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function participants()
    {
        return $this->hasMany(GameSessionParticipant::class);
    }

    public function participantOf(int $userId): ?GameSessionParticipant
    {
        return $this->relationLoaded('participants')
            ? $this->participants->firstWhere('user_id', $userId)
            : $this->participants()->where('user_id', $userId)->first();
    }

    public function isParticipant(int $userId): bool
    {
        return $this->participantOf($userId) !== null;
    }

    public static function generateCode(): string
    {
        // withoutTenantScope: kode sesi HARUS unik secara GLOBAL, bukan
        // cuma dalam tenant pembuatnya — withoutTenantScope() dipakai di
        // banyak tempat (GameSessionController::respond/move/leave/dst,
        // routes/channels.php) untuk mencari sesi lintas-tenant lewat kode
        // ini saja. Kalau uniqueness cuma dicek dalam scope tenant sendiri
        // (perilaku default query di sini), dua tenant berbeda bisa
        // menghasilkan kode yang sama tanpa saling tahu, dan lookup
        // withoutTenantScope()->where('code',$code)->first() di tempat lain
        // bisa salah mengembalikan sesi milik tenant yang sama sekali
        // berbeda.
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::withoutTenantScope()->where('code', $code)->exists());

        return $code;
    }
}
