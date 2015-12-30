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

$factory->define(App\Models\Product::class, function(Faker\Generator $faker) {
	return [
		'upc' => $faker->randomNumber(9),
		'name' => $faker->name(),
		'price' => $faker->randomNumber(2),
		'featured' => $faker->boolean(10), // will be true 10 percent of the time
		'image_url' => 'genesee-cream-ale.jpg'
	];
});

$factory->define(App\Models\User::class, function(Faker\Generator $faker) {
	return [
		'email' => $faker->email(),
		'password' => $faker->password(),
		'first_name' => $faker->firstName(),
		'last_name' => $faker->lastName(),
		'phone_number' => $faker->phoneNumber(),
	];
});

$factory->define(App\Models\UserAddress::class, function(Faker\Generator $faker) {
	return [
		'street' => $faker->streetName(),
		'city' => $faker->city(),
		'state' => $faker->citySuffix(),
		'zipcode' => $faker->randomNumber(5)
	];
});

$factory->define(App\Models\Order::class, function(\Faker\Generator $faker) {
	return [
		'amount' => $faker->randomNumber(2),
		'status' => 'pending',
		'user_id' => 1,
		'user_address_id' => 1
	];
});