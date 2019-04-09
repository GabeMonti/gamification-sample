<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 12/02/19
 * Time: 17:42
 */

namespace App\Http\Controllers\Reward;

use App\Http\Controllers\Controller;
use App\Http\Services\Reward\RewardService;
use Illuminate\Http\Request;


class RewardController extends Controller
{

    private $request;

    private $service;

    public function __construct(Request $req, RewardService $serv)
    {
        $this->request = $req;
        $this->service = $serv;
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function getRewards($companyId)
    {
        return $this->service->getRewards($companyId);
    }


    /**
     * @param $companyId
     * @param null $rewardId
     * @return mixed
     */
    public function deleteRewards($companyId, $rewardId = null)
    {
        return $this->service->deleteRewards($companyId, $rewardId);
    }

    /**
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setRewards()
    {
        $this->validate($this->request, [
            'namespace' => 'required',
            'rewardData' => 'required',
            'companyId' => 'required',
            'expId' => 'required',
        ]);

        return $this->service->setRewards($this->request->all());
    }

}