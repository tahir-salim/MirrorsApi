<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'email_verified_at' => $this->faker->dateTime(),
            'country_id' => $this->faker->word,
            'phone' => $this->faker->phoneNumber,
            'phone_verified_at' => $this->faker->dateTime(),
            'password' => $this->faker->password,
            'role_id' => $this->faker->numberBetween(-10000, 10000),
            'is_blocked' => $this->faker->numberBetween(-8, 8),
            'device_os' => $this->faker->word,
            'device_os_version' => $this->faker->word,
            'device_token' => $this->faker->word,
            'device_name' => $this->faker->word,
            'app_version' => $this->faker->word,
            'last_ip_address' => $this->faker->word,
            'last_activity' => $this->faker->dateTime(),
            'is_social' => $this->faker->numberBetween(-8, 8),
            'provider_id' => $this->faker->word,
            'google_id' => $this->faker->word,
            'provider' => $this->faker->word,
            'provider_token' => $this->faker->word,
            'branch_origin' => $this->faker->word,
            'branch_origin_id' => $this->faker->word,
            'remember_token' => $this->faker->word,
        ];
    }
}
