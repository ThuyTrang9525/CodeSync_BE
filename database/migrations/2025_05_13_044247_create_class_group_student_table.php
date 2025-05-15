<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('class_group_student', function (Blueprint $table) {
            $table->unsignedBigInteger('classID');
            $table->unsignedBigInteger('userID'); // Thay vì userID
            $table->primary(['classID', 'userID']);

            $table->foreign('classID')->references('classID')->on('class_groups')->onDelete('cascade');
            $table->foreign('studentID')->references('studentID')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_group_student');
    }
};
