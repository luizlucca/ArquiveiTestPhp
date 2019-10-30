<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\NfeNote;
use Faker\Generator as Faker;

$factory->define(NfeNote::class, function (Faker $faker) {
    return [
        'id'=> (string) $faker->uuid,
        'value' => (double) $faker->randomFloat()
    ];
});
