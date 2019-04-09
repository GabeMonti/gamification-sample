<?php
/**
 * Created by PhpStorm.
 * User: panda
 * Date: 16/11/18
 * Time: 15:49
 */

namespace App\Http\Helpers\Location;

class Language
{
    static public function languageByTimezone($timeZone)
    {
        $language = new \DateTimeZone($timeZone);
        switch ($language->getLocation()['country_code']) {
            case 'BR':
                return 'br';
                break;
            default:
                return 'en';
                break;
        }
    }
}