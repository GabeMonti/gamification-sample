<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 02/04/19
 * Time: 18:21
 */

//Quest System
$router->get('get/quests', 'Quest\GamificationController@getQuests');
$router->delete('del/quests/{companyId}[/{questsId}]', 'Quest\QuestController@deleteQuests');
$router->post('set/quests/{companyId}', 'Quest\QuestController@setQuests');