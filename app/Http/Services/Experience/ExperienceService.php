<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 14/02/19
 * Time: 17:14
 */

namespace App\Http\Services\Experience;

use App\Http\Repository\Experience\ExperienceRepository;
use App\Http\Services\Service;

class ExperienceService extends Service
{
    private $repository;

    public function __construct(ExperienceRepository $repo)
    {
        parent::__construct();
        $this->repository = $repo;
    }

    /**
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBaseExperience($companyId)
    {
        $return = $this->repository->getBaseExperience($companyId);

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
    public function deleteBaseExperience($companyId, $experienceId = null)
    {
        $return = $this->repository->deleteBaseExperience($companyId, $experienceId);

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
    public function setBaseExperience($params)
    {
        $return = $this->repository->setBaseExperience($params);

        if($return){
            return $this->buildResponse($return, 1147,200);

        }else{
            return $this->buildResponse('error!', 1147,501);
        }

    }


}