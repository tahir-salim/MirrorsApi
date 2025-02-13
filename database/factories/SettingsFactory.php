<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Settings;

class SettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Settings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'value' => $this->faker->word,
            'description' => $this->faker->text,
            'show_for_talent' => $this->faker->boolean,
            'show_for_user' => $this->faker->boolean,
        ];
    }
}
