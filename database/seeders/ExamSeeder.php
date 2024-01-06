<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Group::with(['students', 'sessions.subject.topics'])->get()->each(function ($group) {
            foreach (range(1, 5) as $i) {
                $session = $group->sessions->random();
                $exam = $session->exam()->create(['description' => "Exam {$i}"]);
                $exam->topics()->attach($session->subject->topics->random());
                $exam->students()->attach($group->students->map(fn ($student) => [
                    'student_id' => $student->id,
                    'result' => mt_rand(5, 20),
                ]));
            }
        });
    }
}
