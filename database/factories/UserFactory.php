<?php

use App\User;
use App\Article;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});



$factory->define(Article::class, function (Faker $faker) {

    $arr = [1=>'php', 2=>'mysql', 3=>'linux', 4=>'laravel'];
    $k = mt_rand(1,4);
    $category = [ $k,$arr[$k] ];

    return [
        'category_id'   =>  $category[0],
        'category_name' =>  $category[1],
        'title'         =>  $faker->title,
        'author'        =>  $faker->name,
        'descr'         =>  $faker->text,
        'image'         =>  $faker->imageUrl(),
        'content'       =>  $faker->text,
        'status'        =>  mt_rand(1,2),
        'click_count'   =>  $faker->numberBetween(100,3000),
        'top_status'    =>  mt_rand(0,1),
        'top_time'      =>  time()
    ];
});

