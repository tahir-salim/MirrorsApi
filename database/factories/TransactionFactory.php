<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BookingRequest;
use App\Models\Transaction;
use App\Models\User;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'request_id' => BookingRequest::factory(),
            'status' => $this->faker->numberBetween(-10000, 10000),
            'amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'tap_customer_id' => $this->faker->word,
            'tap_charge_id' => $this->faker->word,
            'tap_status' => $this->faker->word,
            'tap_response' => $this->faker->text,
            'currency' => $this->faker->word,
            'payment_link' => $this->faker->word,
            'is_success' => $this->faker->numberBetween(-8, 8),
            'paid_at' => $this->faker->dateTime(),
            'usd_amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'origin' => $this->faker->word,
        ];
    }
}
