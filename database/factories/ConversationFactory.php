<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

namespace Database\Factories;

use App\Conversation;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Conversation::class, function (Faker $faker) {
    return [
        'uuid' => Str::uuid(),
    ];
});
