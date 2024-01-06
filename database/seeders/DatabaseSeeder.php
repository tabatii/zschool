<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SeasonSeeder::class,
            SubjectSeeder::class,
            RoomSeeder::class,
            BranchSeeder::class,
            AdminSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            GuardianSeeder::class,
            GroupSeeder::class,
            ExamSeeder::class,
            PresenceSeeder::class,
        ]);
    }
}
