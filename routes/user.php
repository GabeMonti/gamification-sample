<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 02/04/19
 * Time: 18:22
 */

/*
 * ONBOARDING OPEN ROUTES
 */

$router->post('auth/signup', 'User\UserController@signUp');

$router->get('user/info', 'User\UserController@UserInfo');
$router->get('get/users/{roleName}', 'User\UserController@listUsers');
$router->get('search/users/{roleName}/{search}', 'User\UserController@searchUsers');
$router->get('user/get-full-information/{userId}', 'User\UserController@getUserInfo');
$router->post('user/set-full-information', 'User\UserController@setUserInformation');
$router->get('user/get-email-by-id/{userId}', 'User\UserController@getEmailById');
$router->get('user/session', 'User\UserController@checkToken');

$router->post('set/roles', 'User\UserController@changeRoles');
$router->post('user/set-status', 'User\UserController@changeStatus');
$router->get('user/get-user-by-id/{idUser}', 'User\UserController@getUserById');

$router->post('user/set-information', 'User\UserController@setUserInfoById');
$router->post('user/set/picture', 'User\UserController@userSetPicture');