<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @SWG\Info(
 *   title="GAMIFICATION",
 *   description="GAMIFICATION endpoints",
 *   version="1.0.0",
 *   @SWG\Contact(
 *     name=" Gabriel Montibeller",
 *   ),
 *   @SWG\License(
 *     name="MIT",
 *     url="http://github.com/gruntjs/grunt/blob/master/LICENSE-MIT"
 *   ),
 *   termsOfService="http://swagger.io/terms/"
 * )
 * @SWG\Swagger(
 *   host=SWAGGER_LUME_CONST_HOST,
 *   schemes={"http"},
 *   produces={"application/json"},
 *   consumes={"multipart/form-data"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://swagger.io/about"
 *   )
 * )
 */
class Controller extends BaseController
{
    //transport session for social login
    public function transportSession($array)
    {
        if(session_id() == '') {
            session_start();
        }
        $_SESSION = $array;
    }

    public function getTransportSession()
    {
        if(session_id() == '') {
            session_start();
        }
            $session = $_SESSION;
            if (!empty($session)) {
                if(session_id() != '') {
                    session_destroy();
                }
                return $session;
            } else {
                if(session_id() != '') {
                    session_destroy();
                }
                return false;
            }
        }
}
