<?php
/**
 * Created by: PhpStorm.
 * Date: 31/07/18
 * Time: 17:07
 */

namespace App\Http\Wrappers;


interface BaseWrapperInterface
{
    public function upload($file, $fileName);
    public function delete($file);
}