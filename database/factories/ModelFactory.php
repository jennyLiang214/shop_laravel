<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Model\Category::class, function (Faker\Generator $faker) {
    return [
        'pid' => 0,
        'name' => $faker->name,
        'level' => 1,
        'describe' => $faker->sentence,
        'img' => $faker->imageUrl(256, 256),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Model\GoodsLabel::class, function (Faker\Generator $faker) {
    $cids = \App\Model\Category::pluck('id')->toArray();

    return [
        'category_id' => $faker->randomElement($cids),
        'goods_label_name' => $faker->name,
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Model\Goods::class, function (Faker\Generator $faker) {
    $cids = \App\Model\Category::pluck('id')->toArray();
    
    return [
        'category_id' => $faker->randomElement($cids),
        'goods_title' => $faker->sentence,
        'goods_label' => '{}',
        'goods_original' => '{}',
        'goods_thumbnail' => '{}',
        'goods_info' => $faker->paragraph,
    ];
});
