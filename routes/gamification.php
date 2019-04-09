<?php
/**
 * Created by PhpStorm.
 * Date: 17/01/19
 * Time: 16:23
 */

//Main Gamification System
$router->get('get/user/stats/{userId}/{companyId}', 'Gamification\GamificationController@getUserStats');
$router->post('set/user/stats/{userId}/{companyId}/{questId}', 'Gamification\GamificationController@setUserStats');

//Achievement System
$router->get('get/achievements', 'Achievement\AchievementController@getAchievements');
$router->delete('del/achievements/{companyId}[/{achievementId}]', 'Achievement\AchievementController@deleteAchievements');
$router->post('set/achievements/{companyId}', 'Achievement\AchievementController@setAchievements');

//Quest System
$router->get('get/quests', 'Quest\GamificationController@getQuests');
$router->delete('del/quests/{companyId}[/{questsId}]', 'Quest\QuestController@deleteQuests');
$router->post('set/quests/{companyId}', 'Quest\QuestController@setQuests');

//Reward System
$router->get('get/rewards', 'Reward\RewardController@getRewards');
$router->delete('del/rewards/{companyId}[/{rewardsId}]', 'Reward\RewardController@deleteRewards');
$router->post('set/rewards/{companyId}', 'Reward\RewardController@setRewards');

//Experience System
$router->get('get/experience', 'Experience\ExperienceController@getExperience');
$router->delete('del/experience/{companyId}[/{experienceId}]', 'Experience\ExperienceController@deleteExperience');
$router->post('set/experience/{companyId}', 'Experience\ExperienceController@setExperience');
