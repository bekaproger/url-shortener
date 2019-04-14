<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'url', 'short_code', 'expires', 'expires_in', 'count', 'user_id'
    ];

    public function urlUser()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
