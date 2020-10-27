<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;
 
$factory->define(Post::class, function (Faker $faker) {
    return [
        'name'          =>  $faker->name,
        'description'   =>  $faker->realText(100)
    ];
});
