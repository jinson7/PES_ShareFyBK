<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'id_user', 'id_publication'
    ];
    
    /**
     * Get the user of the publication.
    */
    public function user()
    {
        return $this->belongsTo('App\User', 'id_user', 'id');
    }
}
