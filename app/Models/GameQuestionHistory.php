<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameQuestionHistory extends Model
{
    // Eloquent menebak nama tabel jamak dari nama model ("history" ->
    // "histories" -> game_question_histories), tapi migrasi membuat tabel
    // bernama game_question_history (tanpa "-ies") — harus disamakan
    // eksplisit di sini, jika tidak semua query ke tabel ini gagal dengan
    // "relation game_question_histories does not exist".
    protected $table = 'game_question_history';

    public $timestamps = false;

    protected $fillable = ['tenant_id', 'game_type', 'question_key', 'used_at'];

    protected $casts = [
        'used_at' => 'datetime',
    ];
}
