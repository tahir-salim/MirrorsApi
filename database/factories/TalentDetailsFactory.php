<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\TalentDetails;
use App\User;

class TalentDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TalentDetails::class;

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
            'about' => $this->faker->text,
            'avatar' => $this->faker->word,
            'social_instagram' => $this->faker->word,
            'social_snapchat' => $this->faker->word,
            'social_youtube' => $this->faker->word,
            'social_twitter' => $this->faker->word,
            'social_tik_tok' => $this->faker->word,
            'status' => $this->faker->numberBetween(-10000, 10000),
            'is_featured' => $this->faker->numberBetween(-8, 8),
            'bank_name' => $this->faker->word,
            'bank_account_owner' => $this->faker->word,
            'bank_iban' => $this->faker->word,
        ];
    }
}
