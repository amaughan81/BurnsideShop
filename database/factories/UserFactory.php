<?php

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

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'auth' => 'ldap',
        'sims_id' => 123456,
        'forename' => $faker->firstName,
        'surname' => $faker->lastName,
        'username' => $faker->userName,
        'role' => 'staff',
        'active' => 1,
        'deleted' => 0,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(bbec\Shop\Models\Product::class, function (Faker $faker) {

    $name = $faker->name;

    return [
        'name' => $name,
        'category_id' => 1,
        'slug' => str_slug($name),
        'description' => $faker->text,
        'price' => $faker->numberBetween(20,2000)
    ];
});

$factory->define(bbec\Shop\Models\ProductPhoto::class, function (Faker $faker) {

    return [

    ];
});

$factory->define(bbec\Shop\Models\Category::class, function (Faker $faker) {

    $name = $faker->name;

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});

$factory->define(bbec\Shop\Models\Coupon::class, function (Faker $faker)  {


    return [
        'value' => 100
    ];
});

$factory->define(bbec\Shop\Models\Order::class, function (Faker $faker)  {


    return [

    ];
});

