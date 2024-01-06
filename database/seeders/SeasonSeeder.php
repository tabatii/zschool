<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SeasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Season::create([
            'name' => '2023/2024',
            'starts_at' => Carbon::create(2023, 9, 1)->startOfDay(),
            'ends_at' => Carbon::create(2024, 7, 30)->endOfDay(),
            'ramadan_starts_at' => Carbon::create(2024, 3, 1)->startOfDay(),
            'ramadan_ends_at' => Carbon::create(2024, 3, 30)->endOfDay(),
            'is_active' => true,
        ]);
    }
}
