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
        Schema::create('class_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classID');
            $table->unsignedBigInteger('userID');
            $table->enum('role', ['student', 'teacher'])->default('student');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            // Foreign key constraints (optional but recommended)
            $table->foreign('classID')->references('classID')->on('class_groups')->onDelete('cascade');
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');

            // Prevent duplicate entries
            $table->unique(['classID', 'userID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_members');
    }
};
