<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Publication extends Model implements JWTSubject
{
    // For JWTAuth
    public function getJWTIdentifier(){
        return $this->getKey();
    }
}
