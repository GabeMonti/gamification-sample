<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 14/02/19
 * Time: 15:23
 */

namespace App\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class RewardSystem extends Model
{
    protected $table = 'gm_reward_system';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'namespace',
        'rewardData',
        'companyId',
        'expId',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function getExpirience()
    {
        return $this->belongsTo(ExperienceSystem::class, 'expId','id');

    }
}