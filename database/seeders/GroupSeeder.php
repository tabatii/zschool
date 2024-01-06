<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $g1 = \App\Models\Group::create([
            'name' => 'Group 1',
            'year' => 1,
            'season_id' => 1,
            'branch_id' => 1,
        ]);
        $g1->students()->attach(array_merge([1], \App\Models\Student::where('id', '!=', 1)->take(9)->inRandomOrder()->pluck('id')->all()));
        $this->generate($g1, $this->items(1));

        $g2 = \App\Models\Group::create([
            'name' => 'Group 2',
            'year' => 2,
            'season_id' => 1,
            'branch_id' => 2,
        ]);
        $g2->students()->attach(\App\Models\Student::where('id', '!=', 1)->take(10)->inRandomOrder()->pluck('id')->all());
        $this->generate($g2, $this->items(2));
    }

    private function generate($group, $items): void
    {
        foreach ($items as $data) {
            \App\Support\CreateSessions::createFromBeginning($group, $data);
        }
    }

    private function items($number): array
    {
        return collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'])->map(function ($day) use ($number) {
            $ids = collect([1, 2, 3, 4, 5, 6])->shuffle();
            return collect([
                ['starts_at' => '08:00', 'ends_at' => '09:00', 'starts_at_ramadan' => '08:00', 'ends_at_ramadan' => '09:00'],
                ['starts_at' => '08:00', 'ends_at' => '10:00', 'starts_at_ramadan' => '08:00', 'ends_at_ramadan' => '10:00'],
                ['starts_at' => '10:00', 'ends_at' => '11:00', 'starts_at_ramadan' => '10:00', 'ends_at_ramadan' => '11:00'],
                ['starts_at' => '10:00', 'ends_at' => '12:00', 'starts_at_ramadan' => '10:00', 'ends_at_ramadan' => '12:00'],
                ['starts_at' => '14:00', 'ends_at' => '15:00', 'starts_at_ramadan' => '14:00', 'ends_at_ramadan' => '15:00'],
                ['starts_at' => '14:00', 'ends_at' => '16:00', 'starts_at_ramadan' => '14:00', 'ends_at_ramadan' => '16:00'],
                ['starts_at' => '16:00', 'ends_at' => '17:00', 'starts_at_ramadan' => '16:00', 'ends_at_ramadan' => '17:00'],
                ['starts_at' => '16:00', 'ends_at' => '18:00', 'starts_at_ramadan' => '16:00', 'ends_at_ramadan' => '18:00'],
            ])->random(4)->unique('starts_at')->map(function ($range) use ($day, $number, &$ids) {
                $id = $ids->pop();
                return [
                    'subject_id' => $id,
                    'teacher_id' => $id * $number,
                    'room_id' => $id * $number,
                    'day' => $day,
                    ...$range,
                ];
            });
        })->flatten(1)->toArray();
    }
}
