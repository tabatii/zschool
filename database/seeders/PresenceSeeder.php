<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PresenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () {
            $sessions = \App\Models\Session::with([
                'subject.topics',
                'group.students',
            ])->where('starts_at', '<', now())->get();
            foreach ($sessions as $session) {
                $session->update([
                    'description' => 'Test',
                    'is_attended' => true,
                ]);
                $session->topics()->attach($session->subject->topics->random());
                $session->students()->attach($session->group->students->map(fn ($student) => [
                    'student_id' => $student->id,
                    'status'  => ($status = Arr::random(array_column(\App\Enums\Presence::cases(), 'value'))),
                    'is_justified'  => $status === \App\Enums\Presence::ABSENT->value ? ($is_justified = Arr::random([true, false])) : ($is_justified = false),
                    'absence_reason'  => $is_justified ? fake()->text() : null,
                ]));
            }
        });
    }
}
