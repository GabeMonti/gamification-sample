<?php
/**
 * Created by PhpStorm.
 * User: panda
 * Date: 15/08/18
 * Time: 16:56
 */

namespace App\Http\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class ServiceConnection extends Service
{
    private $service;
    private $serviceName;
    /**
     * @param $service
     */
    public function __construct($service) {

        $this->service = new Client(['base_uri' => 'http://' . app('config')->get('micro-services.'.$service)]);
        $this->serviceName = app('config')->get('micro-services.'.$service);

    }

    /**
     * @param $url
     * @param null $params
     * @param null $token
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($url, $params = null, $token = null)
    {
        if (!empty($params)) {
            $firstKey = key($params);
            $first = [ $firstKey => $params[$firstKey]];
            unset($params[$firstKey]);
            $url = $url.'?'.key($first).'='.$first[key($first)];
            $count = count($params);
            if ($count > 1) {
                foreach ($params as $key => $val) {
                    $url = $url.'&'.$key.'='.$val;
                }
            }
        }
        try {
            $serviceRequest = $this->service->request('GET', $url, ['headers' => [
                'token' => $token
            ]]);
            $response = \GuzzleHttp\json_decode($serviceRequest->getBody()->getContents());
            return $response->data;
        } catch (RequestException $e) {
            return [
                'message'    => $e->getMessage(),
                'statusCode' => $e->getResponse()->getStatusCode()
            ];
        }
    }

    /**
     * @param $url
     * @param $params
     * @param string $typeOfPost
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($url, $params = null, $typeOfPost = 'form_params')
    {
        try {
            $serviceRequest = $this->service->request('POST', $url, [
                $typeOfPost => $params
            ]);

            $response = \GuzzleHttp\json_decode($serviceRequest->getBody()->getContents());
            return $response;
        } catch (ClientException $e) {
            
            $returnError = explode('response:', $e->getMessage());

            return $returnError;
        }
    }

    /**
     * @param $url
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($url) {
        try {
            $serviceRequest = $this->service->request('DELETE', $url);
            $response = \GuzzleHttp\json_decode($serviceRequest->getBody()->getContents());
            return $response;
        } catch (ClientException $e) {
            $returnError = explode('response:', $e->getMessage());
            return $returnError;
        }
    }

    /**
     * @param $url
     * @param null $params
     * @param string $typeOfPost
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put($url, $params = null, $typeOfPost = 'form_params') {
        try {
            $serviceRequest = $this->service->request('PUT', $url, [
                $typeOfPost => $params
            ]);
            $response = \GuzzleHttp\json_decode($serviceRequest->getBody()->getContents());
            return $response;
        } catch (ClientException $e) {
            $returnError = explode('response:', $e->getMessage());
            return $returnError;
        }
    }
}
