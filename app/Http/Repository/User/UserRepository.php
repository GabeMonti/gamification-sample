<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 03/04/19
 * Time: 10:47
 */
namespace App\Http\Repository\User;


use App\Http\Entities\User;
use App\Http\Entities\UserRole;
use App\Http\Entities\UserInformation;
use App\Http\Repository\Repository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Exception;

class UserRepository extends Repository
{
    /**
     * @param $email
     * @return array
     */
    public function getUserByEmail($email, $companyId)
    {
        try {
            $user = User::select('id', 'name')
                ->where('email', $email)
                ->where('companyId', $companyId)
                ->first();
            return $user;
        } catch (Exception $e) {
            return $this->errorDBHandler($e);
        }
    }

    /**
     * @param $params
     * @return array
     */
    public function signUp($params)
    {
        try {
            User::create($params);
        } catch (Exception $e) {
            Log::info($e);
            return $this->errorDBHandler($e);
        }
        return array(
            'message' => 'User Created Successfully',
            'status' => 200
        );
    }

    private $getBy = array(
        'category',
        'user',
        'livesession',
        'playback'
    );

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserInfo($userId)
    {
        return User::where('id', $userId)->with('roleDetails')->first();
    }

    /**
     * @param $params
     * @return array
     */
    public function getUsers($params)
    {
        $users = User::where('users.companyId', $params["companyId"])
            ->where('users.roleId', $params["roleId"])->paginate(15);
        $data = array();
        foreach ($users as $user) {
            $array = $user;
            $array['userInformation'] = $user->getUserInformation()->first()["userInformation"];
            $data['users'][] = $array;
        }
        $data['pagination'] = $this->buildPagination($users);
        return $data;
    }

    /**
     * @param $params
     * @return array
     */
    public function searchUsers($params)
    {
        return User::select("*")
            -> where('companyId', $params["companyId"])
            -> where('roleId', $params["roleId"])
            -> where(function($query) use ($params)
            {
                $query
                    -> where('name', 'LIKE', '%' . $params["search"] . '%')
                    -> orWhere('email', 'LIKE', '%' . $params["search"] . '%');
            })
            ->paginate($params["per_page"]);
    }

    /**
     * @param $slugRole
     * @return array
     */
    public function getUserRoleByName($roleName)
    {
        try {
            $role = UserRole::select("*")
                ->where('roleName', $roleName)
                ->first();
            return $role;
        } catch (Exception $e) {
            Log::info($e);
            return $this->errorDBHandler($e);
        }
    }

    /**
     * @return mixed
     */
    public function getRoleUsers()
    {
        try {
            $role = UserRole::get();
            return $role;
        } catch (Exception $e) {
            Log::info($e);
            return $this->errorDBHandler($e);
        }
    }

    /**
     * @param $userId
     * @return array
     */
    public function getUserFullInfo($userId)
    {
        try {
            $basicInfo = User::where('id', $userId)->first();
            unset($basicInfo['password']);
            $details = DB::table('users_information')->where('userId', $userId)->first();
            if($details == null) {
                return [
                    'message' => 'No information provided',
                    'code' => 200,
                    'data' => null
                ];
            } else {
                // attach the details in user Info
                $basicInfo->details = $details;
                return [
                    'message' => 'The information of user ' . $basicInfo->email,
                    'code' => 200,
                    'data' => $basicInfo
                ];
            }
        } catch(Exception $error) {
            Log::info($error);
            return $this->errorDBHandler($error);
        }
    }

    /**
     * @param $user_update
     * @param $user_details
     * @return array
     */
    public function setUserInformation($user_update, $user_details)
    {
        try {
            $this->setUserInfo($user_update);
            $user = UserInformation::updateOrCreate(array('userId' => $user_update['userId']), $user_details);
            return [
                'message' => 'The Information was updated',
                'code' => 200,
                'data' => $user
            ];
        } catch(Exception $error) {
            Log::info($error);
            return $this->errorDBHandler($error);
        }
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getEmailById($userId)
    {
        try {
            return User::select('email')
                ->where('id', $userId)
                ->first();
        } catch(Exception $error) {
            Log::info($error);
            return $this->errorDBHandler($error);
        }
    }

    /**
     * @param $userId
     * @param $roleId
     * @return array
     */
    public function setRoleById($userId, $roleId)
    {
        try {
            $role = User::updateOrCreate(['id' => $userId], ['roleId' => $roleId]);
            return [
                'message' => 'The Role was updated',
                'code' => 200,
                'data' => $role
            ];
        } catch (Exception $error) {
            Log::info($error);
            return $this->errorDBHandler($error);
        }
    }

    /**
     * @param $userId
     * @param $statusUser
     * @return array
     */
    public function setStatusById($userId, $statusUser)
    {
        try {
            $user = User::where('id', $userId)->update(['status' => $statusUser]);
            return [
                'message' => 'The status was updated',
                'code' => 200,
                'data' => $user
            ];
        } catch (Exception $error) {
            Log::info($error);
            return $this->errorDBHandler($error);
        }
    }

    /**
     * This is a function to make the first configuration in case the user has empty values.
     */
    public function getDefaultUserConfiguration() {
        return [
            'twitter'           => "",
            'facebook'          => "",
            'linkedin'          => "",
            'qualification'     => "",
//            'default_avatar_m'  => "https://dev.s3.amazonaws.com/1/1/phpN67I0O.png", //there is no way to know
//            'default_avatar_f'  => "https://dev.s3.amazonaws.com/1/1/phpsEQ6Lu.png", //the user sex atm
            'profile_picure'    => "https://dev.s3.amazonaws.com/1/1/php9XLOGh.png",
            'cover_picture'     => "",
            'phone' => ""
        ];
    }

    /**
     * This is a function to make the first configuration in case the user has empty values.
     */
    public function getDefaultAddressConfig() {
        return [
            'address' => "",
            'address2' => "",
            'zipcode' => "",
            'city' => "",
            'country' => ""
        ];
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserToEditById($userId)
    {
        $user_fields = array('id','name','email','companyId','timeZone');
        try {
            $user = User::select($user_fields)->where('id', $userId)->get()->first();
            $userInformation = $user->getUserInformation()->first();

            if (empty($userInformation)) {
                $user->userInformation = $this->getDefaultUserConfiguration();
            } else {
                $user->userInformation =  json_decode($userInformation->userInformation, true);
            }

            return $user;
        } catch(Exception $error) {
            return $this->errorDBHandler($error);
        }
    }

    /**
     * @param $params
     * @return array
     */
    public function setUserInfo($params)
    {
        try {
            if (isset($params['userId'])) {
                $params['id'] = $params['userId'];
                unset($params['userId']);
            }
            if (isset($params['password']) && $params['password'] != "") {
                $params['password'] = password_hash($params['password'], PASSWORD_BCRYPT);
            } else {
                unset($params['password']);
            }
            unset($params['decodedToken']);
            $userData = User::where('id', $params['id'])->update($params);
            return [
                'message' =>  'The user was updated',
                'code' => 200,
                'data' => $userData
            ];
        } catch (Exception $error) {
            Log::info($error);
            return $this->errorDBHandler($error);
        }
    }

    /**
     * @param $params
     * @return array
     */
    public function saveProfilePicture($params)
    {
        try {
            $userData = UserInformation::where('userId', $params['userId'])->first();
            if(empty($userData)){
                $this->createUserInformation($params['userId'], $params['companyId']);
                $userData = UserInformation::where('userId', $params['userId'])->first();
            }
            $userData->userId = $params['userId'];
            $userData->companyId = $params['companyId'];
            $userData->billingInformation = json_decode($params['billingInformation']);
            $userData->userInformation = json_decode($params['userInformation']);
            $userData->where('userId', $params['userId'])->update(['userId' => $params['userId'],
                'companyId' => $params['companyId'],
                'billingInformation' => $params['billingInformation'],
                'userInformation' => $params['userInformation']]);
            return [
                'message' => 'The image has created',
                'code' => 200,
                'lang' => 1005,
                'data' => $userData
            ];
        } catch (Exception $error) {
            Log::info($error);
            return $this->errorDBHandler($error);
        }
    }

    /**
     * @param $userId
     * @param $companyId
     */
    private function createUserInformation($userId, $companyId)
    {
        $userInfo = new UserInformation;
        $userInfo->userId = $userId;
        $userInfo->companyId = $companyId;
        $userInfo->billingInformation = json_encode('{...}');
        $userInfo->userInformation = json_encode('{...}');
        $userInfo->save();
    }

    public function getAllUsers($companyId, $perPage)
    {
        $users = User::where('users.companyId', $companyId)->paginate(15);
        $data = array();
        foreach ($users as $user) {
            $array = $user;
            $array['userInformation'] = $user->getUserInformation()->first()["userInformation"];
            $data['users'][] = $array;
        }
        $data['pagination'] = $this->buildPagination($users);
        return $data;
    }

}