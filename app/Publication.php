<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'id_user', 'id_game', 'text', 'video_path',
    ];

    protected $with = ['game', 'user:id,username,photo_path', 'comments.user:id,username,photo_path', 'like.user:id,username,photo_path'];
    protected $withCount = ['like AS num_likes'];

    public function scopePublicationsFromGame($query, $id_game)
    {
        return $query->where('id_game', $id_game);
    }

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

    /**
     * Get the likes of the publication.
    */
    public function like()
    {
        return $this->hasMany('App\Like', 'id_publication', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the likes of the publication.
    */
    public function comments()
    {
        return $this->hasMany('App\Comment', 'id_publication', 'id')->orderBy('created_at', 'desc');
    }

}
