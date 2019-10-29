<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'id_user', 'id_game', 'text', 'video_path',
    ];
}
