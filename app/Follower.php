<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_follower', 'id_followed', 'pending'
    ];

    /**
     * Get the user of the publication.
    */
    public function user_follower()
    {
        return $this->belongsTo('App\User', 'id_follower', 'id');
    }
}
