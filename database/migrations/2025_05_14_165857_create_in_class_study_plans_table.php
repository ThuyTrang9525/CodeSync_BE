<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
{
    Schema::create('in_class_study_plans', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('userID'); // Chắc chắn rằng cột này đúng tên
        $table->string('semester');
        $table->date('date');
        $table->string('skill');
        $table->text('lessonSummary')->nullable();
        $table->integer('selfAssessment')->nullable();
        $table->text('difficulties')->nullable();
        $table->text('planToImprove')->nullable();
        $table->boolean('problemSolved')->nullable();

        // Tham chiếu đúng vào cột userID trong bảng users
        $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('in_class_study_plans');
    }
};