<?php

namespace Database\Factories;

use App\Models\SalaryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalaryItem>
 */
class SalaryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 3000),
        ];
    }
}
