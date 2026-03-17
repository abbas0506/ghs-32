<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlanResource extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_plan_id',
        'resource_type',
        'resource_url',
        'description',
    ];

    public function lessonPlan()
    {
        return $this->belongsTo(LessonPlan::class);
    }
}
