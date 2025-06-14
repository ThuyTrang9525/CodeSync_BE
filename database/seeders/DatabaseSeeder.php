<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Gọi các Seeder
        $this->call([
            UsersTableSeeder::class,
            StudentsTableSeeder::class,
            ClassesTableSeeder::class,
            GoalSeeder::class,
            ClassGroupStudentSeeder::class,
            NotifyTableSeeder::class,
        ]);
    }
}
