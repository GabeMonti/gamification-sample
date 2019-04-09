<?php
/**
 * Created by PhpStorm.
 * User: panda
 * Date: 04/09/18
 * Time: 14:58
 */

namespace App\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    protected $table = 'users_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'companyId', 'userId', 'billingInformation', 'userInformation'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function user() {
        return $this->hasOne(User::class,'id', 'userId');
    }

}