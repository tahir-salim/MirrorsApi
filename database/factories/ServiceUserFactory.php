<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Service;
use App\ServiceUser;
use App\User;

class ServiceUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_id' => Service::factory(),
            'user_id' => User::factory(),
            'price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'duration' => $this->faker->word,
            'is_active' => $this->faker->numberBetween(-8, 8),
        ];
    }
}
