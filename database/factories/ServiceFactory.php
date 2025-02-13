<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Service;
use App\Subcategory;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_category_id' => Subcategory::factory(),
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'icon' => $this->faker->word,
        ];
    }
}
