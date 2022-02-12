<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
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
            'announcement' => $this->faker->sentence(),
            'priority' => 3,
            'user_id' => User::factory(),
            'label' => $this->faker->word(),
            'image' => true,
            'image_url' => $this->faker->imageUrl()
        ];
    }
}
