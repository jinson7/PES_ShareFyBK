<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'id_user', 'game', 'text', 'video_path',
    ];
}
