<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Country;
use App\User;
use App\UserVerification;

class UserVerificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserVerification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->safeEmail,
            'country_id' => Country::factory(),
            'phone' => $this->faker->phoneNumber,
            'token' => $this->faker->word,
            'user_id' => User::factory(),
            'status' => $this->faker->word,
        ];
    }
}
