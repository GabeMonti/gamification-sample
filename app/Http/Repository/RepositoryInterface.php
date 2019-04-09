<?php
/**
 * Created by: PhpStorm.
 * Date: 03/07/18
 * Time: 19:20
 */

namespace App\Http\Repository;


interface RepositoryInterface
{
    public function getErrors();
    public function setErrors($errorMessage);
}