<?php

namespace Database\Factories;

use App\Models\Day;
use App\Models\Course;
use App\Models\Period;
use Illuminate\Database\Eloquent\Factories\Factory;

class LectureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'course_id' => Course::factory(),
            'day_id' => Day::factory(),
            'period_id' => Period::factory(),
            'location' => $this->faker->word(),
            'lecturer' => $this->faker->word(),
        ];
    }
}
