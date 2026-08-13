<?php

/** @var $faker Faker\Generator */
return [
    'id' => env('TESTING_USER_ID'),
    'username' => env('TESTING_USERNAME'),
    'auth_key' => env('TESTING_USER_AUTH_KEY'),
    'password_hash' => env('TESTING_USER_PASSWORD_HASH'),
    'password_reset_token' => env('TESTING_USER_PASSWORD_RESET_TOKEN'),
    'email' => env('TESTING_USER_EMAIL'),
    'status' => 10,
    'created_at' => time(),
    'updated_at' => time(),
    'data' => [],
];
