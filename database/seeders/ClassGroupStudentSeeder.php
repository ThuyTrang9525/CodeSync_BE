<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassGroupStudentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('class_group_student')->insert([
            // ['classID' => 1, 'userID' => 1],
            // ['classID' => 2, 'userID' => 2],
            ['classID' => 3, 'userID' => 2],

        ]);
    }
}
