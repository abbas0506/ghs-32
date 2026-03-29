<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'grade_no',
        'name',
    ];

    public function gradeSubjects()
    {
        return $this->hasMany(GradeSubject::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'grade_subjects');
    }
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function syllabi()
    {
        return $this->hasMany(Syllabus::class);
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
