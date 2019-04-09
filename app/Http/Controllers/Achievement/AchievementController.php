<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 11/02/19
 * Time: 17:47
 */

namespace App\Http\Controllers\Achievement;

use App\Http\Controllers\Controller;
use App\Http\Services\Achievement\AchievementService;
use Illuminate\Http\Request;


class AchievementController extends Controller
{

    private $request;

    private $service;

    /**
     * AchievementController constructor.
     * @param Request $req
     * @param AchievementController $serv
     */
    public function __construct(Request $req, AchievementService $serv)
    {
        $this->request = $req;
        $this->service = $serv;
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function getAchvs($companyId)
    {
        return $this->service->getAchievements($companyId);
    }


    /**
     * @param $companyId
     * @param null $achievementId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleleteAchvs($companyId, $achievementId = null)
    {
        return $this->service->deleteAchievements($companyId, $achievementId);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setAchvs()
    {
        $this->validate($this->request, [
            'namespace' => 'required',
            'achvData' => 'required',
            'companyId' => 'required',
            'questId'  => 'required',
        ]);

        return $this->service->insertAchievements($this->request->all());
    }

}