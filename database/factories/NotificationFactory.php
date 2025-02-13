<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Notification;
use App\User;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'body' => $this->faker->text,
            'is_read' => $this->faker->numberBetween(-8, 8),
            'action_type' => $this->faker->numberBetween(-10000, 10000),
            'action_id' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
