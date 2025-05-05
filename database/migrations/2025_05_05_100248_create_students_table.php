<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student', function (Blueprint $table) {
            $table->id(); // students.id
            $table->foreignId('user_id')      // thay cho unsignedBigInteger + foreign
                  ->constrained('user')      // references users.id
                  ->cascadeOnDelete();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male','Female','Other'])->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('avatar_url')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
