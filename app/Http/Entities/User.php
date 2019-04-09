<?php

namespace App\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'companyId',
        'roleId',
        'timeZone',
        'facebookId',
        'googleId',
        'identity_token',
        'timeZone',
        'event_role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function roleDetails()
    {
        return $this->belongsTo(UserRole::class, 'roleId','id')->select('id','roleName');
    }

    public function getUserInformation()
    {
        return $this->belongsTo(UserInformation::class, 'id','userId')->select('userInformation')->get();
    }
}
