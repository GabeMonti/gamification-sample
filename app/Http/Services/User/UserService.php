<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 02/04/19
 * Time: 19:05
 */

namespace App\Http\Services\User;


use App\Http\Repository\User\UserRepository;
use App\Http\Services\Service;
use GuzzleHttp\Exception\RequestException;
use \Exception;

class UserService extends Service
{
    private $repository;

    /**
     * RegisterService constructor.
     * @param RegisterRepository $registerRepository
     */
    public function __construct(UserRepository $UserRepository)
    {
        parent::__construct();
        $this->repository = $UserRepository;
    }

    /**
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp($params)
    {
        $data = null;

        if($this->repository->getUserByEmail($params["email"], $params["companyId"])){
            return $this->buildResponse($data, 1004,200, "User exists");
        }

        $return = $this->repository->signUp($params);

        if (isset($return['error'])) {
            return $this->buildResponse($data, 1003,401, $return['error']);
        }

        return $this->buildResponse($data, 1004,$return['status'], $return['message']);
    }


    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function userInfo($userId)
    {
        $data = $this->repository->getUserInfo($userId);
        return $this->buildResponse($data, 1005,200);
    }

    /**
     * @param $params
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getUsers($params)
    {
        if ($params["roleName"] == 'All') {
            $users = $this->repository->getAllUsers($params['companyId'], $params['per_page']);
            return $users;
        }
        else {
            $userRole = $this->repository->getUserRoleByName($params["roleName"]);
            if( !isset($userRole) ){
                return $this->buildResponse($userRole, 1017,200, 'Role not found');
            }
            $params["roleId"] = $userRole->id;
            $data = $this->repository->getUsers($params);
            return $data;
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoles()
    {
        $userRoles = $this->repository->getRoleUsers();
        return $this->buildResponse($userRoles, 1017,200, 'Role not found');
    }

    /**
     * @param $params
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function searchUsers($params)
    {
        $userRole = $this->repository->getUserRoleByName($params["roleName"]);
        if( !isset($userRole) ){
            return $this->buildResponse($userRole, 1017,200, 'Role not found');
        }
        $params["roleId"] = $userRole->id;
        $data = $this->repository->searchUsers($params);
        return $data;
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo($userId)
    {
        $userFullInformation = $this->repository->getUserFullInfo($userId);
        return $this->buildResponse($userFullInformation['data'], 1005,$userFullInformation['code'], $userFullInformation['message']);
    }

    /**
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUserInformation($params)
    {
        $removeKeys = array('name', 'email','timeZone');
        $user_update = $params['user_update'];
        foreach($removeKeys as $key) {
            $user_update[$key] = $params[$key];
            unset($params[$key]);
        }
        unset($params['decodedToken']);
        $userFullInformation = $this->repository->setUserInformation($user_update, $params);
        $this->logSessionUserAction([
            'UserId' => (int) $user_update['userId'],
            'companyId' => $user_update['companyId'],
            'key' => 'userInfoUpdate',
            'value' => 'User Information updated',
            'status' => 1
        ]);
        return $this->buildResponse($userFullInformation['data'], 1005,$userFullInformation['code'], $userFullInformation['message']);
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmailById($userId)
    {
        try {
            $userEmail = $this->repository->getEmailById($userId);
            return $this->buildResponse($userEmail['email'], 1005, 200, 'This is the information');
        } catch(Exception $error){
            return $this->buildResponse($error, 1005, 404 , $error);
        }
    }

    /**
     * @param $userId
     * @param $roleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setRoleUser($userId, $roleId)
    {
        try {
            $changeRole = $this->repository->setRoleById($userId, $roleId);
            return $this->buildResponse($changeRole, 1005, 200, 'Role changed success.');
        } catch (Exception $error) {
            return $this->buildResponse($error, 1005, 404, $error);
        }
    }

    /**
     * @param $userId
     * @param $statusUser
     * @return \Illuminate\Http\JsonResponse
     */
    public function setStatusUser($userId, $statusUser)
    {
        try {
            $changeStatus = $this->repository->setStatusById($userId, $statusUser);
            return $this->buildResponse($changeStatus, 1005, 200, 'Status was change success.');
        } catch (Exception $error) {
            return $this->buildResponse($error, 1005, 404, $error);
        }
    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserToEdit($userId)
    {
        try {
            $user = $this->repository->getUserToEditById($userId);
            return $this->buildResponse($user, 1005, 200, 'User get success.');
        } catch (Exception $error) {
            return $this->buildResponse($error, 1005, 404, $error);
        }
    }

    /**
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function settingUserById($params)
    {
        try {
            $user = $this->repository->setUserInfo($params);
            return $this->buildResponse($user['data'], 1005, $user['code'], $user['message']);
        } catch (Exception $error) {
            return $this->buildResponse($error, 1005, 404, $error);
        }
    }

    /**
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePicture($params)
    {
        try {
            $user = $this->repository->saveProfilePicture($params);
            return $this->buildResponse($user, 1005, 200, 'The image has created!');
        } catch (Exception $error) {
            return $this->buildResponse($error, 1005, 404, $error);
        }
    }


}