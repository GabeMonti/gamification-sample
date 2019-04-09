<?php
/**
 * Created by PhpStorm.
 * User: gabriel
 * Date: 02/04/19
 * Time: 18:21
 */

//Experience System
$router->get('get/experience', 'Experience\ExperienceController@getExperience');
$router->delete('del/experience/{companyId}[/{experienceId}]', 'Experience\ExperienceController@deleteExperience');
$router->post('set/experience/{companyId}', 'Experience\ExperienceController@setExperience');