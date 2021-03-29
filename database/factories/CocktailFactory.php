<?php

use App\Models\Cocktail;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Cocktail::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
