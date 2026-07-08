<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Models\RoleUser;
// use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleUserFactory extends Factory
{
    protected $model = RoleUser::class;

    public function definition()
    {

        return [
            //
            'user_type' => 'App\Models\User',
        ];
    }
}
