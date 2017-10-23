<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleUser extends Model
{
    protected $fillable = ['username', 'password'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
