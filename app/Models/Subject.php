<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'short_name'
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function lessonPlanCompletionPercentageForGrade($gradeId)
    {
        return round($this->lessons()->havingGradeId($gradeId)->whereNotNull('objective')->count() / 72 * 100, 1);
    }
}
