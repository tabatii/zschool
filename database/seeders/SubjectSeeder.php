<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $english = \App\Models\Subject::create(['name' => 'English']);
        $this->createTopics($english);

        $french = \App\Models\Subject::create(['name' => 'French']);
        $this->createTopics($french);

        $math = \App\Models\Subject::create(['name' => 'Math']);
        $this->createTopics($math);

        $physics = \App\Models\Subject::create(['name' => 'Physics']);
        $this->createTopics($physics);

        $chemistry = \App\Models\Subject::create(['name' => 'Chemistry']);
        $this->createTopics($chemistry);

        $history = \App\Models\Subject::create(['name' => 'History']);
        $this->createTopics($history);
    }

    private function createTopics($subject)
    {
        for ($i=1; $i <= 10; $i++) { 
            $subject->topics()->create(['name' => "Topic {$i}"]);
        }
    }
}
