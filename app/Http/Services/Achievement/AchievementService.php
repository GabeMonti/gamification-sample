<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 12/02/19
 * Time: 17:33
 */

namespace App\Http\Services\Achievement;


use App\Http\Repository\Achievement\AchievementRepository;
use App\Http\Services\Service;




class AchievementService extends Service
{

    private $repository;

    public function __construct(AchievementRepository $repo)
    {
        parent::__construct();
        $this->repository = $repo;
    }


    /**
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAchievements($companyId)
    {
        $return = $this->repository->getAchievements($companyId);

        if($return){
            return $this->buildResponse($return, 1147,200);
        }else{
            return $this->buildResponse('error!', 1147,501);
        }


    }


    /**
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAchievements($companyId, $achievementId = null)
    {
        $return = $this->repository->deleteAchievements($companyId, $achievementId);

        if($return){
            return $this->buildResponse($return, 1147,200);

        }else{
            return $this->buildResponse('error!', 1147,501);
        }

    }


    /**
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertAchievements($companyId)
    {
        $return = $this->repository->insertAchievements($companyId);

        if($return){
            return $this->buildResponse($return, 1147,200);

        }else{
            return $this->buildResponse('error!', 1147,501);
        }

    }


}