<?php

namespace Database\Factories;

use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payroll>
 */
class PayrollFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'school_id'  => School::pluck('id')->random(),
            'payrollno' => 'PR-'.$this->faker->unique()->numberBetween(10000, 99999),
            // 'staff_id'   => User::pluck('id')->random(),
            // 'salary_id'  => Salary::pluck('id')->random(),
            'start_date' => Carbon::now()->startOfMonth(),
            'end_date' => Carbon::now()->endOfMonth(),
            'status' => $this->faker->randomElement(['paid', 'unpaid']),
            'comments' => $this->faker->sentence(6),
        ];
    }
}
