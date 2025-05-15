<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('class_groups')->insert([
            [
                'className' => 'TOEIC',
                'userID' => 3, // Sửa lại cho đúng userID đã được seed ở TeachersTableSeeder
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'className' => 'IT English',
                'userID' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'className' => 'Communicative English',
                'userID' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // DB::table('class_group_student')->insert([
        //     ['classID' => 1, 'studentID' => 1],
        //     ['classID' => 2, 'studentID' => 2],
        // ]);
    }
}
