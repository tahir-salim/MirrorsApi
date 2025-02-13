<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BookingRequest;
use App\Models\User;

class RequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Request::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'talent_user_id' => User::factory(),
            'price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'details' => $this->faker->text,
            'status' => $this->faker->numberBetween(-10000, 10000),
            'transaction_id' => $this->faker->numberBetween(-10000, 10000),
            'requested_delivery_date' => $this->faker->dateTime(),
            'completed_at' => $this->faker->dateTime(),
            'processed_at' => $this->faker->dateTime(),
        ];
    }
}
