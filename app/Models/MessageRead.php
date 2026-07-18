<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class MessageRead extends Model
{
    use BelongsToTenant;
    public $timestamps = false;

    protected $fillable = ['message_id', 'user_id', 'read_at', 'tenant_id'];

    protected $casts = ['read_at' => 'datetime'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
