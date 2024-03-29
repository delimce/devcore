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

use App\Services\Commons\StringsHandlerService;
use Illuminate\Support\Facades\Hash;

$factory->define(App\Models\Users\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'lastname' => $faker->lastname,
        'email' => $faker->email,
        'password' => Hash::make("customPassword"),
        'verified' => 0
    ];
});

$factory->define(App\Models\Manager\Manager::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'password' => Hash::make("customPassword"),
        'token' => $faker->uuid,
        'birthdate' => $faker->date,
        'verified' => 1
    ];
});

$factory->define(App\Models\Manager\Company::class, function (Faker\Generator $faker) use ($factory) {
    return [
        'manager_id' => $factory->create(App\Models\Manager\Manager::class)->id,
        'name' => $faker->name,
        'nif' => $faker->randomAscii,
        'phone' => $faker->phoneNumber,
    ];
});

$factory->define(App\Models\Manager\Garage::class, function ($faker) use ($factory) {
    return [
        'manager_id' => $factory->create(App\Models\Manager\Manager::class)->id,
        'name' => $faker->company,
        'url' => StringsHandlerService::slugify($faker->company),
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'desc' => $faker->paragraph,
        'enable' => 1,
        'country_id' => 204, //spain
        'state_id' => 13, # Madrid, Comunidad de
        'province_id' => 28, # Madrid
        'zipcode' => 28027
    ];
});
