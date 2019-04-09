<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 14/02/19
 * Time: 17:09
 */

use App\Http\Entities\ExperienceSystem;
use App\Http\Repository\Repository;
use Illuminate\Support\Facades\Log;
use \Exception;

class ExperienceRepository extends Repository
{

    /**
     * @param $companyId
     * @return bool
     */
    public function getBaseExperience($companyId)
    {
        try {
            $settings = ExperienceSystem::select('*')
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
     * @param null $experienceId
     * @return bool
     */
    public function deleteBaseExperience($companyId, $experienceId = null)
    {
        try {
            if(empty($experienceId)){
                $return = ExperienceSystem::select('*')
                    ->where('companyId', $companyId)
                    ->delete();
                if (!empty($return) || $return->count() > 0) {
                    return $return->toArray();
                }
                return false;
            }else{
                $return = ExperienceSystem::select('*')
                    ->where('companyId', $companyId)
                    ->whereIn('id', $experienceId)
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
    public function setBaseExperience($params)
    {
        try {
            $achievement = ExperienceSystem::create($params);
            return $achievement;
        } catch (Exception $e) {
            Log::info($e);
            return false;
        }


    }
}