<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfStudyPlan extends Model
{
    protected $primaryKey = 'planID';
    protected $table = 'in_class_study_plan';
    protected $fillable = [
        'userID',
        'semester',
        'week',
        'date',
        'skill',
        'lessonSummary',
        'time_allocation',
        'concentration',
        'resources',
        'activities',
        'evaluation',
        'notes'
    ];

    public $timestamps = false;
}
