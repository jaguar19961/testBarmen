<?php

use App\Models\Ingredient;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Ingredient::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'price' => $faker->randomFloat(2, 0.1, 3),
        'is_alcohol' => $faker->boolean,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
