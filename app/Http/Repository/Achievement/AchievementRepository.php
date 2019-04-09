<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 12/02/19
 * Time: 18:15
 */

namespace App\Http\Repository\AchievementRepository;

use App\Http\Entities\AchievementSystem;
use App\Http\Repository\Repository;
use Illuminate\Support\Facades\Log;
use \Exception;


class AchievementRepository extends Repository
{

    /**
     * @param $companyId
     * @return bool
     */
    public function getAchievements($companyId)
    {
        try {
            $settings = AchievementSystem::select('*')
                ->where('companyId', $companyId)
                ->whereIn('companyId', 'global')
                ->get();
            if (!empty($settings) || $settings->count() > 0) {
                return $settings->toArray();
            }
            return false;
        } catch (Exception $e) {
            Log::info($e);
            return false;
        }

    }

    /**
     * @param $companyId
     * @param null $achievementId
     * @return bool
     */
    public function deleteAchievements($companyId, $achievementId = null)
    {
        try {
            if(empty($achievementId)){
                $return = AchievementSystem::select('*')
                    ->where('companyId', $companyId)
                    ->delete();
                if (!empty($return) || $return->count() > 0) {
                    return $return->toArray();
                }
                return false;
            }else{
                $return = AchievementSystem::select('*')
                    ->where('companyId', $companyId)
                    ->whereIn('id', $achievementId)
                    ->delete();
                if (!empty($return) || $return->count() > 0) {
                    return $return->toArray();
                }
                return false;
            }
        } catch (Exception $e) {
            Log::info($e);
            return false;
        }

    }

    /**
     * @param $params
     * @return bool
     */
    public function insertAchievements($params)
    {
        try {
            $achievement = AchievementSystem::create($params);
            return $achievement;
        } catch (Exception $e) {
            Log::info($e);
            return false;
        }


    }

}
