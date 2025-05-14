<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. USERS
        Schema::create('users', function (Blueprint $table) {
            $table->id('userID');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['ADMIN', 'TEACHER', 'STUDENT']);
            $table->timestamps();
        });

        // 2. STUDENTS
        Schema::create('students', function (Blueprint $table) {
            $table->unsignedBigInteger('userID')->primary();
            $table->date('dateOfBirth')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('avatarURL')->nullable();
            $table->date('enrollmentDate')->nullable();
            $table->text('bio')->nullable();
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
        });

        // 3. TEACHERS
        Schema::create('teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('userID')->primary();
            $table->date('dateOfBirth')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('avatarURL')->nullable();
            $table->date('enrollmentDate')->nullable();
            $table->text('bio')->nullable();
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
        });

        // 4. ADMINS
        Schema::create('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('userID')->primary();
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
        });

        // 5. CLASS GROUPS
        Schema::create('class_groups', function (Blueprint $table) {
            $table->id('classID');
            $table->string('className');
            $table->unsignedBigInteger('userID')->nullable();
            $table->foreign('userID')->references('userID')->on('teachers')->onDelete('set null');
            $table->timestamps();
        });

        // 6. CLASS_GROUP_STUDENT (many-to-many)
        Schema::create('class_group_student', function (Blueprint $table) {
            $table->unsignedBigInteger('classID');
            $table->unsignedBigInteger('userID');
            $table->primary(['classID', 'userID']);
            $table->foreign('classID')->references('classID')->on('class_groups')->onDelete('cascade');
            $table->foreign('userID')->references('userID')->on('students')->onDelete('cascade');
        });

        // 7. JOURNAL ENTRIES
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id('entryID');
            $table->unsignedBigInteger('userID');
            $table->date('date');
            $table->text('content');
            $table->timestamps();
            $table->foreign('userID')->references('userID')->on('students')->onDelete('cascade');
        });

        // 8. COMMENTS
        Schema::create('comments', function (Blueprint $table) {
            $table->id('commentID');
            $table->unsignedBigInteger('entryID');
            $table->unsignedBigInteger('userID');
            $table->text('content');
            $table->dateTime('createdAt');
            $table->foreign('entryID')->references('entryID')->on('journal_entries')->onDelete('cascade');
            $table->foreign('userID')->references('userID')->on('teachers')->onDelete('cascade');
        });

        // 9. GOALS
        Schema::create('goals', function (Blueprint $table) {
            $table->id('goalID');
            $table->unsignedBigInteger('userID');
            $table->text('description');
            $table->string('semester');
            $table->date('deadline');
            $table->enum('status', ['not-started', 'in-progress', 'completed'])->default('not-started');
            $table->foreign('userID')->references('userID')->on('students')->onDelete('cascade');
        });

        // 10. STUDY PLANS
        Schema::create('study_plans', function (Blueprint $table) {
            $table->id('planID');
            $table->unsignedBigInteger('userID');
            $table->enum('type', ['SELF_STUDY', 'IN_CLASS']);
            $table->date('date');
            $table->string('skills');
            $table->text('lessonSummary')->nullable();
            $table->integer('selfAssessment')->nullable();
            $table->text('difficulties')->nullable();
            $table->text('planToImprove')->nullable();
            $table->boolean('problemSolved')->default(false);
            $table->integer('concentration')->nullable();
            $table->text('resources')->nullable();
            $table->text('activities')->nullable();
            $table->text('evaluation')->nullable();
            $table->text('notes')->nullable();
            $table->foreign('userID')->references('userID')->on('students')->onDelete('cascade');
        });

        // 11. ACHIEVEMENTS
        Schema::create('achievements', function (Blueprint $table) {
            $table->id('achievementID');
            $table->unsignedBigInteger('userID');
            $table->string('title');
            $table->string('fileURL');
            $table->date('uploadDate');
            $table->foreign('userID')->references('userID')->on('students')->onDelete('cascade');
        });

        // 12. CERTIFICATES
        Schema::create('certificates', function (Blueprint $table) {
            $table->id('certificateID');
            $table->unsignedBigInteger('userID');
            $table->string('title');
            $table->string('issuer');
            $table->date('issueDate');
            $table->date('expirationDate')->nullable();
            $table->string('fileURL');
            $table->text('description')->nullable();
            $table->foreign('userID')->references('userID')->on('students')->onDelete('cascade');
        });

        // 13. NOTIFICATIONS
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notificationID');
            $table->unsignedBigInteger('receiverID');
            $table->text('content');
            $table->enum('type', ['SYSTEM', 'GROUP', 'INTERACTION']);
            $table->boolean('isRead')->default(false);
            $table->dateTime('createdAt');
            $table->foreign('receiverID')->references('userID')->on('users')->onDelete('cascade');
        });
    
    
        // 9. support_requests (phụ thuộc students + users)
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id('requestID');
            $table->unsignedBigInteger('senderID');
            $table->unsignedBigInteger('receiverID');
            $table->text('message');
            $table->enum('status', ['PENDING', 'RESOLVED'])->default('PENDING');
            $table->dateTime('createdAt');
            $table->foreign('senderID')->references('userID')->on('students')->onDelete('cascade');
            $table->foreign('receiverID')->references('userID')->on('users')->onDelete('cascade');
        });
    
        // 10. system_logs (phụ thuộc users)
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id('logID');
            $table->unsignedBigInteger('userID');
            $table->text('action');
            $table->dateTime('timestamp');
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('support_requests');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('study_plans');
        Schema::dropIfExists('goals');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('class_group_student');
        Schema::dropIfExists('class_groups');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('students');
        Schema::dropIfExists('users');
    }
};
