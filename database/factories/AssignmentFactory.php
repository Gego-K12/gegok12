<?php

namespace Database\Factories;

use App\Models\Assignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition()
    {
        return [

            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(3),
            'attachment' => null,

            'marks' => $this->faker->numberBetween(10, 100),

            'assigned_date' => now()->subDays(rand(1, 5)),
            'submission_date' => now()->addDays(rand(1, 5)),

            'status' => $this->faker->randomElement(['pending', 'ongoing', 'cancel', 'completed']),
        ];
    }
}
