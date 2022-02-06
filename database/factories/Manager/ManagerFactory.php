<?php

namespace Database\Factories\Manager;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManagerFactory extends Factory
{
    public function definition()
    {
        return [
            'name'      => $this->faker->name(),
            'lastname'  => $this->faker->lastname(),
            'email'     => $this->faker->unique()->safeEmail(),
            'phone'     => $this->faker->phoneNumber(),
            'password'  => Hash::make("customPassword"),
            'token'     => $this->faker->uuid(),
            'birthdate' => $this->faker->date(),
            'verified'  => 1
            ];
    }
}
