<?php

namespace Database\Factories;

use App\Models\AssignmentApproval;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentApprovalFactory extends Factory
{
    protected $model = AssignmentApproval::class;

    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
