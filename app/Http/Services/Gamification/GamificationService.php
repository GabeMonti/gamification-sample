<?php
/**
 * Created by Phpstorm.
 * Date: 17/01/19
 * Time: 14:36
 */
namespace App\Http\Services\Gamification;

use App\Http\Repository\Gamification\GamificationRepository;
use App\Http\Services\Service;

class GamificationService extends Service
{

    private $repository;

    public function __construct(GamificationRepository $repo)
    {
        parent::__construct();
        $this->repository = $repo;
    }

    /**
     * @param $allSettings
     * @param $remove
     * @return array
     */
    private function manipulateSettings($allSettings, $remove)
    {
        $arrayKeys = array_keys($allSettings);
        $company = array();
        $globals = array();
        foreach ($arrayKeys as $key) {
            if ($key == 'global') {
                $collection = collect($allSettings[$key]);

                $filtered = $collection->filter(function ($value, $key) use ($remove) {
                    return !starts_with(strtolower($key), $remove);
                });
                $globals = $filtered->all();
            } else {
                foreach ($allSettings[$key] as $k => $v) {
                $collectionCompany = collect($allSettings[$key]);

                $filteredCompany = $collectionCompany->filter(function ($value, $key) use ($remove) {
                    return !starts_with(strtolower($key), $remove);
                });
                $company = $filteredCompany->all();
                }
            }

        }
        $return = array();
        foreach ($globals as $k => $v) {
            $return[$k] = $v;
            if (array_key_exists($k, $company)) {
                $return[$k] = $company[$k];
            }
        }
        return $return;
    }

    /**
     * @param $userId
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserStats($userId, $companyId)
    {
        $userStats = $this->repository->getUserStats($userId);
        if ($userStats !== false) {
            $settings = $this->manipulateSettings($this->companyGlobalLevelSettings(
                $companyId
            ), 'livesession');
            $userStats = [
                'userId' => $userId,
                'currentLevel' => 1,
                'experiencePoints' => 0,
                'levelSystemId' => $settings['level_system_id']
            ];
            $this->setDefaultUserStats($userStats);
        }
        return $this->buildResponse($userStats, 1147,200);
    }


    /**
     * @param $userId
     * @param $companyId
     * @param $questId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUserStats($userId, $companyId, $questId)
    {
        $rewards = $this->getRewards($questId);
        $userLevel = $this->calcUserExpirience($companyId, $rewards);
        return $userLevel;
        $userStats = $this->repository->setUserStats($userId, $companyId, $rewards);

        if ($userStats == false) {
            return $this->buildResponse('error!', 1147,501);
        }
        return $this->buildResponse($userStats, 1147,200);
    }
}