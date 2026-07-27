<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameQuestionHistory extends Model
{
    public $timestamps = false;

    protected $fillable = ['tenant_id', 'game_type', 'question_key', 'used_at'];

    protected $casts = [
        'used_at' => 'datetime',
    ];
}
