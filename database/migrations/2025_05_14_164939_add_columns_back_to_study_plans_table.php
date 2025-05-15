<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsBackToStudyPlansTable extends Migration
{
    public function up()
    {
        Schema::table('study_plans', function (Blueprint $table) {
            $table->integer('concentration')->nullable();
            $table->string('resources')->nullable();
            $table->string('activities')->nullable();
            $table->string('evaluation')->nullable();
            $table->string('notes')->nullable();
        });
    }

    public function down()
    {
        Schema::table('study_plans', function (Blueprint $table) {
            $table->dropColumn(['concentration', 'resources', 'activities', 'evaluation', 'notes']);
        });
    }
}

