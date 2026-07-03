<?php

namespace Database\Factories;

use App\Models\Homework;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeworkFactory extends Factory
{
    protected $model = Homework::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence(10),
            'date' => now()->subDays(rand(1, 5)),
            'submission_date' => now()->addDays(rand(1, 5)),
        ];
    }
}
