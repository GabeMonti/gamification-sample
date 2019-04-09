<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Http\Entities\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => password_hash("12345", PASSWORD_BCRYPT),
        'type' => $faker->randomElement($array = array ('student', 'teacher')),
        'companyId' => 1,
        'roleId' => 1,
        'timeZone' => 'America/Sao_Paulo'
    ];
});

$factory->define(App\Http\Entities\Category::class, function (Faker\Generator $faker) {
    return [
        'organizationId' => function() {
            return \App\Http\Entities\Company::all()->random()->id;
        },
        'categoryName' => $faker->randomElement($array = array (
            'Aula de Costura',
            'Aula de Logica',
            'Aula de Violao',
            'Aula de canto',
            'Aula de Ingles',
            'Pré ENEM',
            'Aula basica de mecanica'
        ))
    ];
});

$factory->define(App\Http\Entities\CategoryInfo::class, function (Faker\Generator $faker) {
    return [
        'categoryId' => function() {
            return \App\Http\Entities\Category::all()->random()->id;
        },
        'description' => $faker->paragraph(3, true),
        'title' => $faker->sentence(4, true),
        'userTarget' => $faker->randomNumber(2, true),
        'coverImage' => $faker->imageUrl(800,600, 'cats'),
        'maxSlots' => $faker->randomNumber(1, true),
        'trailGroups' => $faker->randomNumber(1, true),
        'duration' => 180
    ];
});

$factory->define(App\Http\Entities\ScheduleAvailability::class, function (Faker\Generator $faker) {
    $date = $faker->dateTimeBetween('now','+30 days');
    return [
        'userId' => function() {
            return App\Http\Entities\User::all()->random()->id;
        },
        'categoryId' => function() {
            return App\Http\Entities\Category::all()->random()->id;
        },
        'available_timestamp' => $date->format('H').':00',
        'status' => 1,
        'weekDay' => $date->format('l')
    ];
});
