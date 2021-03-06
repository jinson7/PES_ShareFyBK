<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'id_user', 'id_publication', 'date', 'text',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }
}
