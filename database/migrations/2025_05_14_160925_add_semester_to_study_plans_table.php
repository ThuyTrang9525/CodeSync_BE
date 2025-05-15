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
    Schema::table('study_plans', function (Blueprint $table) {
        $table->string('semester')->after('type'); // đặt sau cột 'type', tùy ý
    });
}

public function down(): void
{
    Schema::table('study_plans', function (Blueprint $table) {
        $table->dropColumn('semester');
    });
}

};
