<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'choice' => $this->faker->word(),
            'question_id' => Question::factory(),
            'is_answer' => $this->faker->numberBetween(0, 1),
        ];
    }
}
