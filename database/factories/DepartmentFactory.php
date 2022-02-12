<?php

namespace Database\Factories;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'department' => $this->faker->unique()->word(),
            'faculty_id' => Faculty::factory(),
            'banner' => $this->faker->imageUrl(),

        ];
    }
}
