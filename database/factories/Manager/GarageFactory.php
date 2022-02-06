<?php

namespace Database\Factories\Manager;

use App\Models\Manager\Manager;
use App\Services\Commons\StringsHandlerService;
use Illuminate\Database\Eloquent\Factories\Factory;

class GarageFactory extends Factory
{
    public function definition()
    {
        return [
            'manager_id'  => Manager::factory()->create()->id,
            'name'        => $this->faker->company(),
            'url'         => StringsHandlerService::slugify($this->faker->company()),
            'phone'       => $this->faker->phoneNumber(),
            'address'     => $this->faker->address(),
            'desc'        => $this->faker->paragraph(),
            'enable'      => 1,
            'country_id'  => 204, //spain
            'state_id'    => 13, # Madrid, Comunidad de
            'province_id' => 28, # Madrid
            'zipcode'     => 28027
        ];
    }
}
