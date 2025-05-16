<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoalSeeder extends Seeder
{
    public function run()
    {
        // Giả định có userID = 1, 2, 3 trong bảng students
        $goals = [
            [
                'userID'   => 1,
                'description' => 'Improve math skills',
                'semester'    => 'Spring 2025',
                'deadline'    => Carbon::now()->addMonths(3)->toDateString(),
                'title'       => 'Math Improvement',
                'status'      => 'in-progress',
            ],
            [
                'userID'   => 2,
                'description' => 'Complete science project',
                'semester'    => 'Spring 2025',
                'deadline'    => Carbon::now()->addMonths(2)->toDateString(),
                'title'       => 'Science Project',
                'status'      => 'not-started',
            ],
        ];

        DB::table('goals')->insert($goals);
    }
}
