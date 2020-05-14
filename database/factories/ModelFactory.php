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
use Illuminate\Support\Facades\Hash;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Models\Manager\Manager::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'password' => Hash::make("customPassword"),
        'token'=> $faker->uuid,
        'birthdate' => $faker->date,
        'verified'=>1
    ];
});

$factory->define(App\Models\Manager\Company::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'rif'=>$faker->randomAscii,
        'phone' => $faker->phoneNumber,
    ];
});
