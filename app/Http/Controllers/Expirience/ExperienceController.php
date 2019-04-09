<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 14/02/19
 * Time: 16:51
 */

namespace App\Http\Controllers\Expirience;


use App\Http\Controllers\Controller;
use App\Http\Services\Experinece\ExperineceService;
use Illuminate\Http\Request;

class ExperienceRepository extends Controller
{

    private $request;

    private $service;

    public function __construct(Request $req, ExperineceService $serv)
    {
        $this->request = $req;
        $this->service = $serv;
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function getBaseExperience($companyId)
    {
        return $this->service->getBaseExperience($companyId);
    }


    /**
     * @param $companyId
     * @param null $experienceId
     * @return mixed
     */
    public function deleteBaseExperience($companyId, $experienceId = null)
    {
        return $this->service->deleteBaseExperience($companyId, $experienceId);
    }

    /**
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setBaseExperience()
    {
        $this->validate($this->request, [
            'baseValue' => 'required',
            'companyId' => 'required',
        ]);

        return $this->service->setBaseExperience($this->request->all());
    }

}

