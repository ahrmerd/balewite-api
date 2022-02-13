<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FacultyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'faculty' => $this->faker->unique()->word(),
            // 'faculty' => $this->faker->word(),
        ];
    }
}