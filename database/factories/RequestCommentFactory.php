<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BookingRequest;
use App\Models\RequestComment;
use App\Models\User;

class RequestCommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RequestComment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'request_id' => BookingRequest::factory(),
            'user_id' => User::factory(),
            'comment' => $this->faker->text,
        ];
    }
}
