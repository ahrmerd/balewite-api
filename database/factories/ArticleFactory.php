<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'article' => $this->faker->sentence(),
            'priority' => 3,
            'label' => $this->faker->word(),
            'user_id' => User::factory()
        ];
    }
}
