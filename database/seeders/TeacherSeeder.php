<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teacher = \App\Models\Teacher::create([
            'gender' => 'male',
            'name' => 'Teacher',
            'username' => 'teacher',
            'password' => '$2y$10$H5UqfADhinR9WQ4CwsJkYugnWodKaUcz1WzgkPHizyHk8HX4o2nS6', // 123456
            'mobile' => fake()->bothify('06########'),
            'email' => fake()->unique()->safeEmail(),
            'birthday' => Carbon::parse('01/01/1995')->subDays(rand(1, 3650)),
        ]);
        $teacher->subjects()->attach($teacher->id);
        for ($i=1; $i <= 11; $i++) {
            $gender = fake()->randomElement(['male', 'female']);
            $teacher = \App\Models\Teacher::create([
                'gender' => $gender,
                'name' => fake()->name($gender),
                'username' => fake()->bothify('?######'),
                'password' => '$2y$10$H5UqfADhinR9WQ4CwsJkYugnWodKaUcz1WzgkPHizyHk8HX4o2nS6', // 123456
                'mobile' => fake()->bothify('06########'),
                'email' => fake()->unique()->safeEmail(),
                'birthday' => Carbon::parse('01/01/1995')->subDays(rand(1, 3650)),
            ]);
            $teacher->subjects()->attach($teacher->id <= 6 ? $teacher->id : $teacher->id - 6);
        }
    }
}
