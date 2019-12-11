<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name', 'birth_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // For JWTAuth

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }

    /**
     * Get the the publications.
    */
    public function publications()
    {
        return $this->hasMany('App\Publication', 'id_user', 'id');
    }

    /**
     * Get the the followers.
    */
    public function followers()
    {
        return $this->hasMany('App\Follower', 'id_followed', 'id');
    }

    /**
     * Get the the followed.
    */
    public function followed()
    {
        return $this->hasMany('App\Follower', 'id_follower', 'id');
    }
    
    public function isNotificationsActive(){
        return ($this->notification === 1 ? true : false);
    }
}
