<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Admin::create([
            'gender' => 'male',
            'name' => 'Admin',
            'username' => 'admin',
            'password' => '$2y$10$H5UqfADhinR9WQ4CwsJkYugnWodKaUcz1WzgkPHizyHk8HX4o2nS6', // 123456
            'mobile' => fake()->bothify('06########'),
            'email' => fake()->unique()->safeEmail(),
            'birthday' => Carbon::parse('01/01/1995')->subDays(rand(1, 3650)),
        ]);
        for ($i=1; $i <= 4; $i++) {
            $gender = fake()->randomElement(['male', 'female']);
            \App\Models\Admin::create([
                'gender' => $gender,
                'name' => fake()->name($gender),
                'username' => fake()->bothify('?######'),
                'password' => '$2y$10$H5UqfADhinR9WQ4CwsJkYugnWodKaUcz1WzgkPHizyHk8HX4o2nS6', // 123456
                'mobile' => fake()->bothify('06########'),
                'email' => fake()->unique()->safeEmail(),
                'birthday' => Carbon::parse('01/01/1995')->subDays(rand(1, 3650)),
            ]);
        }
    }
}
