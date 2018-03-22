<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    $date_time = $faker->date.' '.$faker->time;
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password?$password:bcrypt('12345'), // secret
        'remember_token' => str_random(10),
        'is_admin' => false,
        'active_token' => str_random(30),
        'active_status' => false,
        'created_at' => $date_time,
        'updated_at' => $date_time
    ];
});
