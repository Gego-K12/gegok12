<?php

namespace Database\Factories;

use App\Models\PayrollTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PayrollTemplate>
 */
class PayrollTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name' => $this->faker->randomElement([
                'Teacher Template',
                'Staff Template',
                'Default Salary Template',
                'HR Template',
            ]),
            'status' => 1,
        ];
    }
}
