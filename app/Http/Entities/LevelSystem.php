<?php
/**
 * Created by phpstorm.
 * Date: 04/02/19
 * Time: 16:11
 */


namespace App\Http\Entities;


use Illuminate\Database\Eloquent\Model;

class LevelSystem extends Model
{

    protected $table = 'gm_level_system';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'companyId',
        'key',
        'value'
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
