<?php

namespace Database\Factories;

use App\Models\HomeworkApproval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomeworkApproval>
 */
class HomeworkApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
