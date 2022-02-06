<?php
namespace Database\Factories\Manager;

use App\Models\Manager\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    
    public function definition()
    {
        return [
            'manager_id' => Manager::factory()->create()->id,
            'name'       => $this->faker->name(),
            'nif'        => $this->faker->randomAscii(),
            'phone'      => $this->faker->phoneNumber(),
        ];
    }


}