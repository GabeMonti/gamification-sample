<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 02/04/19
 * Time: 18:21
 */

//Reward System
$router->get('get/rewards', 'Reward\RewardController@getRewards');
$router->delete('del/rewards/{companyId}[/{rewardsId}]', 'Reward\RewardController@deleteRewards');
$router->post('set/rewards/{companyId}', 'Reward\RewardController@setRewards');