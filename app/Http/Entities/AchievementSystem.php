<?php

namespace App\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class AchievementSystem extends Model
{

    protected $table = 'gm_achievement_system';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'namespace',
        'achievementData',
        'companyId',
        'rewardId',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function getReward()
    {
        return $this->belongsTo(RewardSystem::class, 'rewardId','id');

    }
}
