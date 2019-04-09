<?php
/**
 * Created by PhpStorm.
 * Date: 04/02/19
 * Time: 14:53
 */


namespace App\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class UserStats extends Model
{

    protected $table = 'gm_user_stats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userId', 'currentLevel', 'experiencePoints', 'levelSystemId'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

}