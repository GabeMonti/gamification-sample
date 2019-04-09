<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('/', function () use ($router) {
//    echo 'The forge is ready to be used!';
//    echo 'Service Status : Ok!!';
    return print 'The forge is ready to be used! ' .$router->app->version();
});

if (env('APP_ENV') !== 'local') {
    URL::forceScheme('https');
}

require "gamification.php";