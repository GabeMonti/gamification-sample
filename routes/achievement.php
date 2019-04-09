<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 02/04/19
 * Time: 18:21
 */

//Achievement System
$router->get('get/achievements', 'Achievement\AchievementController@getAchievements');
$router->delete('del/achievements/{companyId}[/{achievementId}]', 'Achievement\AchievementController@deleteAchievements');
$router->post('set/achievements/{companyId}', 'Achievement\AchievementController@setAchievements');