<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class StudyPlan extends Model
{
    protected $primaryKey = 'planID';
    protected $table = 'study_plans';
   protected $fillable = [
    'userID',
    'type',
    'semester',
    'week',
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
