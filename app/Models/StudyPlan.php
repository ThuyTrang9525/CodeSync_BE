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
    'date',
    'skills',
    'lessonSummary',
    'selfAssessment',
    'difficulties',
    'planToImprove',
    'problemSolved',
    'concentration',
    'resources',
    'activities',
    'evaluation',
    'notes'
];

    public $timestamps = false;
}
