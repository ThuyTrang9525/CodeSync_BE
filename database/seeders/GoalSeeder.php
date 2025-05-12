<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoalSeeder extends Seeder
{
    public function run()
    {
        // Giả định có studentID = 1, 2, 3 trong bảng students
        $goals = [
            [
                'studentID'   => 1,
                'description' => 'Improve math skills',
                'semester'    => 'Spring 2025',
                'deadline'    => Carbon::now()->addMonths(3)->toDateString(),
            ],
            [
                'studentID'   => 2,
                'description' => 'Complete science project',
                'semester'    => 'Spring 2025',
                'deadline'    => Carbon::now()->addMonths(2)->toDateString(),
            ],
        ];

        DB::table('goals')->insert($goals);
    }
}
