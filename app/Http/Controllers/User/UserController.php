<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 02/04/19
 * Time: 18:31
 */
namespace App\Http\Controllers\User;


use App\Http\Services\User\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    /**
     * The request instance.
     *
     * @var RegisterService $registerService
     */
    private $service;

    /**
     * The request instance.
     *
     * @var /Illuminate/Http/Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param RegisterService $registerService
     */
    public function __construct(Request $request, UserService $UserService) {
        $this->request = $request;
        $this->service = $UserService;
    }
    /**
     * @return mixed
     */
    public function signUp()
    {
        return $this->service->signUp($this->request->all());
    }


    /**
     * Get User Info
     *
     * @SWG\Get(
     *     path="/user/info",
     *     tags={"User"},
     *     operationId="UserInfo",
     *     summary="UserInfo",
     *     description="Fetch all of user Info",
     *     @SWG\Parameter(
     *         name="token",
     *         in="header",
     *         description="token",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *      @SWG\Response(
     *         response=200,
     *         description="Array with the user info",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="{message : User not found , status : 400}",
     * ),
     * )
     *
     *
     * @return mixed
     */
    public function userInfo()
    {
        return $this->service->userInfo(
            $this->request->input('decodedToken')->userId
        );
    }

    /**
     * Check token status
     *
     * * @SWG\Get(
     *     path="/user/session",
     *     tags={"User"},
     *     operationId="Token Status",
     *     summary="UserInfo",
     *     description="Fetch token status",
     *     @SWG\Parameter(
     *         name="token",
     *         in="header",
     *         description="token",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *      @SWG\Response(
     *         response=200,
     *         description="Array with the token status",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="{message : Token Expired , status : 400}",
     * ),
     * )
     *
     * @return array
     */
    public function checkToken()
    {
        return array('message' => 'token still valid', 'code' => 1006);
    }

    /**
     * @param $roleName
     * @param int $per_page
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function listUsers($roleName, $per_page = 15 )
    {
        if( $this->request->input('per_page') !== null ){
            $per_page = (int) $this->request->input('per_page');
        }
        $params = [
            "companyId" => $this->request->input("decodedToken")->organization,
            "roleName" => $roleName,
            "per_page" => $per_page
        ];
        return $this->service->getUsers(
            $params
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listRoles()
    {
        return $this->service->getRoles();
    }

    /**
     * @param $roleName
     * @param $search
     * @param int $per_page
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function searchUsers($roleName, $search, $per_page = 15 )
    {
        if( $this->request->input('per_page') !== null ){
            $per_page = (int) $this->request->input('per_page');
        }
        $params = [
            "companyId" => $this->request->input("decodedToken")->organization,
            "roleName" => $roleName,
            "search" => $search,
            "per_page" => $per_page
        ];

        return $this->service->searchUsers(
            $params
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo($userId)
    {
        if ($userId == null) {
            return $this->service->getUserInfo(
                $this->request->input('decodedToken')->userId
            );
        } else {
            return $this->service->getUserInfo($userId);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUserInformation()
    {
        return $this->service->setUserInformation(
            $this->request->all()
        );
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmailById($userId)
    {
        return $this->service->getEmailById($userId);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeRoles()
    {
        $params = $this->request->all();
        return $this->service->setRoleUser($params['userId'], $params['roleToGo']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus()
    {
        $params = $this->request->all();
        return $this->service->setStatusUser($params['userId'], $params['statusUser']);
    }

    /**
     * @param $idUser
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserById($idUser = null)
    {
        return $this->service->getUserToEdit($idUser);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUserInfoById()
    {
        return $this->service->settingUserById(
            $this->request->all()
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function userSetPicture()
    {
        return $this->service->savePicture(
            $this->request->all()
        );
    }
}