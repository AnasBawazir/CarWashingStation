<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'phone'=> $faker->phoneNumber,
        'password' => '$2y$10$LWjVghUz50vJQDgTZ64SbugiRpNCuoUr5HXSNi9h5bGNKfL2Kz.eW', // password
        'remember_token' => Str::random(10),
    ];
});

