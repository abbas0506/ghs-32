<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_id',
        'grade_id',
        'lesson_no',
        'title',
        'activity',
        'homework',
        'remarks',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function resources()
    {
        return $this->hasMany(LessonResource::class);
    }
    public function objectives()
    {
        return $this->hasMany(LessonObjective::class);
    }
}
