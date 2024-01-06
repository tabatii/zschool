<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $b1 = \App\Models\Branch::create([
            'name' => 'Branch 1',
            'shortcut' => 'B1',
        ]);
        $b2 = \App\Models\Branch::create([
            'name' => 'Branch 2',
            'shortcut' => 'B2',
        ]);
        $b1->subjects()->attach([
            [
                'subject_id' => 1,
                'minutes' => 2400,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 2,
                'minutes' => 1800,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 3,
                'minutes' => 1200,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 4,
                'minutes' => 3000,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 5,
                'minutes' => 3600,
                'factor' => 1,
                'year' => 1,
            ],[
            ],[
                'subject_id' => 6,
                'minutes' => 4200,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 1,
                'minutes' => 2400,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 2,
                'minutes' => 1800,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 3,
                'minutes' => 1200,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 4,
                'minutes' => 3000,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 5,
                'minutes' => 3600,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 6,
                'minutes' => 4200,
                'factor' => 1,
                'year' => 2,
            ]
        ]);
        $b2->subjects()->attach([
            [
                'subject_id' => 1,
                'minutes' => 4200,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 2,
                'minutes' => 1200,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 3,
                'minutes' => 3000,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 4,
                'minutes' => 2400,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 5,
                'minutes' => 1800,
                'factor' => 1,
                'year' => 1,
            ],[
            ],[
                'subject_id' => 6,
                'minutes' => 3600,
                'factor' => 1,
                'year' => 1,
            ],[
                'subject_id' => 1,
                'minutes' => 4200,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 2,
                'minutes' => 1200,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 3,
                'minutes' => 3000,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 4,
                'minutes' => 2400,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 5,
                'minutes' => 1800,
                'factor' => 1,
                'year' => 2,
            ],[
                'subject_id' => 6,
                'minutes' => 3600,
                'factor' => 1,
                'year' => 2,
            ]
        ]);
    }
}
