<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BookingRequest;
use App\Models\RequestAttachment;

class RequestAttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RequestAttachment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'request_id' => BookingRequest::factory(),
            'file_type' => $this->faker->numberBetween(-10000, 10000),
            'file_path' => $this->faker->word,
            'description' => $this->faker->text,
        ];
    }
}
