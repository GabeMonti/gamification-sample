<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 12/02/19
 * Time: 17:42
 */

namespace App\Http\Controllers\Reward;

use App\Http\Controllers\Controller;
use App\Http\Services\Quest\QuestService;
use Illuminate\Http\Request;


class QuestController extends Controller
{

    private $request;

    private $service;

    public function __construct(Request $req, QuestService $serv)
    {
        $this->request = $req;
        $this->service = $serv;
    }

    /**
     * @param $companyId
     * @return mixed
     */
    public function getQuests($companyId)
    {
        return $this->service->getQuests($companyId);
    }


    /**
     * @param $companyId
     * @param null $questId
     * @return mixed
     */
    public function delQuests($companyId, $questId = null)
    {
        return $this->service->deleteQuests($companyId, $questId);
    }

    /**
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setQuests()
    {
        $this->validate($this->request, [
            'namespace' => 'required',
            'questData' => 'required',
            'companyId' => 'required',
            'achievementId'  => 'required',
        ]);

        return $this->service->insertQuests($this->request->all());
    }
}