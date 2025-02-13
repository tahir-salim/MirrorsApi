<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\TalentPost;
use App\User;

class TalentPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TalentPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'media_url' => $this->faker->word,
            'media_type' => $this->faker->numberBetween(-10000, 10000),
            'media_thumbnail_url' => $this->faker->word,
            'body' => $this->faker->word,
        ];
    }
}
