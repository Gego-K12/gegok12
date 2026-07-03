<?php

namespace Database\Factories;

use App\Models\Salary;
use App\Models\TemplateItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Salary>
 */
class SalaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $templateItem = TemplateItem::inRandomOrder()->first();
        $templateId = $templateItem->template_id;

        // $paycategoryId  = $templateItem->paycategory_id;
        return [
            'template_id' => $templateId,
            'gross_salary' => $this->faker->randomFloat(2, 10000, 80000),
            'effective_date' => now()->addDays(rand(1, 5)),
            'comments' => $this->faker->sentence(),
        ];
    }
}
