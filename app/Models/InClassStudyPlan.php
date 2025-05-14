<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InClassStudyPlan extends Model
{
    protected $primaryKey = 'planID';
    protected $table = 'in_class_study_plans';
    protected $fillable = [
        'userID',
        'semester',
        'date',
        'skill',
        'lessonSummary',
        'selfAssessment',
        'difficulties',
        'planToImprove',
        'problemSolved',
    ];

    public $timestamps = false;
}
