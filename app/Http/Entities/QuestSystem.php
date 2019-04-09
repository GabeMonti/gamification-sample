<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 14/02/19
 * Time: 15:23
 */

namespace App\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class QuestSystem extends Model
{
    protected $table = 'gm_quest_system';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'namespace',
        'questData',
        'companyId',
        'achievementId',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function getAchievement()
    {
        return $this->belongsTo(AchievementSystem::class, 'achievementId','id');

    }
}
