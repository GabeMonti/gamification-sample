<?php
namespace App\Http\Services;

use App\Http\Repository\Register\RegisterRepository;
use App\Http\Repository\Yoda\YodaRepository;
use App\Http\Repository\Repository as MainRepo;
use App\Http\Services\Register\RegisterService;
use App\Http\Services\Yoda\YodaService;
use Firebase\JWT\JWT;
use \Datetime;
use \DateTimeZone;
use App\Http\Wrappers\AWS;
use \Exception;

class Service implements ServiceInterface
{

    protected $message;
    protected $status;
    protected $mainRepository;
    private   $awsSdk;

    public function __construct()
    {
        $repoMain = new MainRepo();
        $this->mainRepository = $repoMain;
        $this->awsSdk = new AWS();
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return (int) $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Build all response to json
     *
     * @param $data
     * @param null $status
     * @param null $message
     * @param null $optional
     * @return \Illuminate\Http\JsonResponse
     */
    public function buildResponse($data, $langCode, $status = null, $message = null, $optional = null)
    {
        $statusCode = $this->getStatus();
        $returnMessage = $this->getMessage();
        if ($status) {
            $statusCode = $status;
        }
        if ($message) {
            $returnMessage = $message;
        }
        $response = [
            'message'=> $returnMessage,
            'code' => $langCode,
            'data' => $data,
            'status' => $status
        ];
        if ($optional) {
            $response['info'] = $optional;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Create a new token.
     *
     * @param  array $user
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function buildToken($user)
    {
        $payload = [
            'name' => $user['name'],
            'email' => $user['email'],
            'roleId' => $user['roleId'],
            'userId' => $user['id'],
            'userTimezone' => $user['timeZone'],
            'organization' => $user['organization']->id,
            'billingProfile' => $user['billing']['id'],
            'interactiveType' => $user['interactiveType'],
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60*6 // Expiration time 6 Hours
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    }


    /**
     * @param $time
     * @param string $userTimezone
     * @return Datetime
     */
    protected function nowDateByTime($time, $userTimezone = 'UTC')
    {
        $hourMinute = explode(':', $time);
        $date = new DateTime("now", new DateTimeZone($userTimezone));
        $date->setTime((int)$hourMinute[0],(int)$hourMinute[1]);
        return $date;
    }

    /**
     * @param $companyId
     * @return array|bool
     */
    protected function companyGlobalSettings($companyId)
    {
        return $this->mainRepository->companyGlobalSettings($companyId);
    }

    /**
     * @param $companyId
     * @return array|bool
     */
    protected function companyGlobalLevelSettings($companyId)
    {
        return $this->mainRepository->companyGlobalLevelSettings($companyId);
    }

    /**
     * @param $params
     */
    protected function setDefaultUserStats($params)
    {
        return $this->mainRepository->setDefaultUserStats($params);
    }

    /**
     * @param $questId
     */
    protected function getRewards($questId)
    {
        return $this->mainRepository->getRewards($questId);
    }


    protected function calcUserExpirience($companyId, $reward)
    {
        return $this->mainRepository->calcUserExpirience($companyId, $reward);
    }


    /**
     * @param $timestamp
     * @param $timeZone
     * @return array
     */
    protected function analiseTimestamp($timestamp, $timeZone)
    {
        $datePlaceholder = new DateTime('now', new DateTimeZone($timeZone));
        $date = $datePlaceholder->setTimestamp($timestamp);
        $return = array(
            'year' => $date->format('Y'),
            'month' => $date->format('m'),
            'day' => $date->format('d'),
            'hour' => $date->format('H:i'),
            'weekDay' => $date->format('l'),
            'completeDate' => $date->format('Y-m-d H:i'),
            'timestamp' => $timestamp,
            'dateTime' => $date
        );
        return $return;
    }

    /**
     * @param $companyId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCompanySettings($companyId)
    {
        $yodarepo = new YodaRepository();
        $registerRepo = new RegisterRepository();
        $register = new RegisterService($registerRepo);
        $yoda = new YodaService($register, $yodarepo);
        $settingsResponse = $yoda->getCompanySettings(['companyId' => $companyId]);
        $fullAnswerSettings = json_decode($settingsResponse->content(), true);
        $settings= json_decode($fullAnswerSettings['data']['settings'], true);
        $settingsDecoded = $settings;

        $activeModules = array();
        foreach ($settingsDecoded['services'] as $service => $config) {
            if ($config['enable'] == 1) {
                $service = strtolower($service);
                $activeModules[$service] = $config;
            }
        }

        return $activeModules;
    }

    /**
     * @param $params
     */
    public function logSessionUserAction($params)
    {
        $this->mainRepository->logSessionUserAction($params);
    }

    /**
     * Get User TimeZone
     *
     * @param $userId
     * @return mixed
     */
    protected function getUserTimeZone($userId)
    {
        return $this->mainRepository->getUserTimeZone($userId);
    }

    /**
     * @param $type
     * @param $entityId
     * @param $price
     * @param $billingId
     * @return mixed
     */
    public function setEntityBillingProfile($type, $entityId, $price, $billingId)
    {
        return $this->mainRepository->setEntityBillingProfile($type, $entityId, $price, $billingId);
    }

    /**
     * @param $filename
     * @return string
     */
    public function getCleanFileName($filename, $ext){
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) .'.'. $ext;
    }


    /**
     * @return array
     */
    static public function getTheFrontHostnameFromEnv()
    {
        $env = env('APP_ENV');
        switch ($env) {
            case 'develop':
                return $domainInformation = [
                ];
                break;
            case 'qa':
                return $domainInformation = [
                ];
                break;
            case 'local':
                return $domainInformation = [
                ];
                break;
        }
    }
}
