<?php
/**
 * Created by PhpStorm.
 * User: panda
 * Date: 09/08/18
 * Time: 15:49
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Yoda Driver
    |--------------------------------------------------------------------------
    | This is the connection with the admin interface of the project
    */
    'YODA' => env('YODA', 'yoda'),
    /*
    |--------------------------------------------------------------------------
    | Noyification Driver
    |--------------------------------------------------------------------------
    | This is the connection with the notification interface of the project
    */
    'NOTIFICATION' => env('NOTIFICATION', 'notification'),
    /*
    |--------------------------------------------------------------------------
    | Session Driver
    |--------------------------------------------------------------------------
    | This is the connection with the session interface of the project
    */
    'SESSION' => env('SESSION', 'session'),
    /*
    |--------------------------------------------------------------------------
    | Playback Driver
    |--------------------------------------------------------------------------
    | This is the connection with the session interface of the project
    */
    'PLAYBACK' => env('PLAYBACK', 'playback'),
];