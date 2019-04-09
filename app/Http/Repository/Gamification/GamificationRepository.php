<?php
/**
 * Created by PhpStorm.
 * Date: 17/01/19
 * Time: 14:36
 */

namespace App\Http\Repository\Gamification;


use App\Http\Entities\UserStats;
use App\Http\Repository\Repository;
use Illuminate\Support\Facades\Log;
use \Exception;

class GamificationRepository extends Repository
{

    /**
     * @param $userId
     * @return bool
     */
    public function getUserStats($userId)
    {
        try {
            $user = UserStats::select('*')
                ->where('userId', $userId)
                ->first();
            if ($user->count() > 0) {
                return $user->toArray();
            }
            return false;
        } catch (Exception $e) {
            Log::info($e);
            return false;
        }
    }


    /**
     * @param $userId
     * @param $companyId
     * @param $rewards
     * @return bool
     */
    public function setUserStats($userId, $companyId, $rewards)
    {
        try {
            $user = UserStats::select('*')
                ->where('userId', $userId)
                ->first();
            return $user;
            if ($user->count() > 0) {
                $user->update();
                return $user->toArray();
            }
            return false;
        } catch (Exception $e) {
            Log::info($e);
            return false;
        }
    }
}