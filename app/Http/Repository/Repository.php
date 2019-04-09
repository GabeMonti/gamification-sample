<?php
/**
 * Created by: PhpStorm.
 * Date: 03/07/18
 * Time: 19:20
 */

namespace App\Http\Repository;

use App\Http\Entities\User;
use Illuminate\Support\Facades\Log;
use \Exception;

class Repository implements RepositoryInterface
{

    protected $errors;

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors($errorMessage)
    {
        $this->errors[] = $errorMessage;
    }

    /**
     * This make to handle the errors
     * @param $e
     * @return array
     */
    public function errorDBHandler($e) {
        return array(
            'error' => "DB-ERROR: ".$e->getCode(),
            'status' => 404
        );
    }

    /**
     * @param $specialistId
     * @return mixed
     */
    public function getUserInfo($specialistId)
    {
        try {
            $session = User::select('*')
                ->where('id', $specialistId)
                ->first();
        } catch (Exception $e) {
            Log::info($e);
            $session = array();
        }

        return $session->toArray();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserTimeZone($userId)
    {
        return User::select('timeZone')->where('id', $userId)->first()->toArray();
    }

    /**
     * @param $object
     * @return mixed
     */
    public function buildPagination($object)
    {
        $paginate['total'] = $object->total();
        $paginate['current_page'] = (empty($object->currentPage())) ? null : $object->currentPage();
        $paginate['first_item'] = $object->firstItem();
        $paginate['last_item'] = $object->lastItem();
        $paginate['has_more_pages'] = $object->hasMorePages();
        $paginate['last_page'] = (empty($object->lastPage())) ? null : $object->lastPage();
        $paginate['next_page'] = (empty($object->nextPageUrl())) ? null : $object->nextPageUrl();
        $paginate['first_page'] = $object->onFirstPage();
        $paginate['per_page'] = $object->perPage();
        $paginate['previous_page'] = (empty($object->previousPageUrl())) ? null : $object->previousPageUrl();
        return $paginate;
    }

}
