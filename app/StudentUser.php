<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentUser extends Model
{
    protected $fillable = [
        'year_group',
        'form',
        'dob',
        'upn',
        'adno',
        'fsm',
        'gt',
        'sen',
        'need_type',
        'eal',
        'scei',
        'fsme',
        'care',
        'ppi',
        'ccc'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
