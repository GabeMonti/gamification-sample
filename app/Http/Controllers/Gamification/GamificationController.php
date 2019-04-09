<?php
/**
 * Created by PhpStorm.
 * Date: 17/01/19
 * Time: 14:35
 */

namespace App\Http\Controllers\Gamification;

use App\Http\Controllers\Controller;
use App\Http\Services\Gamification\GamificationService;
use Illuminate\Http\Request;

class GamificationController extends Controller
{

    private $request;

    private $service;

    /**
     * GamificationController constructor.
     * @param Request $req
     * @param GamificationService $serv
     */
    public function __construct(Request $req, GamificationService $serv)
    {
        $this->request = $req;
        $this->service = $serv;
    }

    /**
     * @param $userId
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserStats($userId, $companyId)
    {
        return $this->service->getUserStats($userId, $companyId);
    }

    /**
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setUserStats($userId, $companyId, $questId)
    {
        return $this->service->setUserStats($userId, $companyId, $questId);
    }
}