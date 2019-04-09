<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 14/02/19
 * Time: 15:24
 */

namespace App\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class ExperienceSystem extends Model
{
    protected $table = 'gm_experience_system';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'baseValue',
        'companyId',
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


