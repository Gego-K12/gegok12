<?php

namespace Database\Factories;

use App\Models\StudentParentLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentParentLinkFactory extends Factory
{
    protected $model = StudentParentLink::class;

    public function definition()
    {
        return [
            'status' => 1,
        ];
    }
}
