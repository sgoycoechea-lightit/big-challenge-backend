<?php

namespace Database\Factories;

use App\Enums\SubmissionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'symptoms' => fake()->text(1023),
            'status' => SubmissionStatus::Pending
        ];
    }
}
