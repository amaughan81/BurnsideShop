<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffUser extends Model
{
    protected $fillable = ['department_id', 'job_role', 'room_name', 'telephone', 'email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
