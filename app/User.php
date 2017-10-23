<?php

namespace App;

use bbec\Shop\Traits\UserShopTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, UserShopTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auth', 'sims_id', 'forename', 'surname', 'username', 'password', 'role', 'gender', 'active', 'deleted',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeActiveStudents($query)
    {
        return $query
            ->where('role','student')->orWhere('role','manager')
            ->where('active',1)
            ->orderBy('surname', 'ASC')
            ->get();
    }

    /**
     * Get the user for authentication
     *
     * @param $username
     * @return mixed
     */
    public static function getActiveUser($username) {
        return self::where([
            'username' => $username,
            'deleted' => 0,
            'active' => 1
        ])->first();
    }

    /**
     * Check if a Google account exisits
     *
     * @return bool
     */
    public function googleAccount()
    {
        if((boolean) $this->has('google')->get()->count()) {
            return $this->google()->get();
        }
        return [];
    }

    /**
     * Return Google Account Details
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function google()
    {
        return $this->hasOne(GoogleUser::class);
    }

    /**
     * Return Staff account if user has one
     *
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function staffProfile()
    {
        if((boolean) $this->has('staff')->get()->count()) {
            return $this->staff()->get();
        }
        return [];
    }

    /**
     * Return Staff Profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function staff()
    {
        return $this->hasOne(StaffUser::class);
    }

    /**
     * Return Student account if user has one
     *
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function studentProfile()
    {
        if((boolean) $this->has('student')->get()->count()) {
            return $this->staff()->get();
        }
        return [];
    }

    /**
     * Return Student Profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne(StudentUser::class);
    }
}
