<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'id_user', 'id_game', 'text', 'video_path',
    ];

    /**
     * Get the game of the publication.
    */
    public function game()
    {
        return $this->belongsTo('App\Game', 'id_game', 'id');
    }

    /**
     * Get the user of the publication.
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }

}
