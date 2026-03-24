<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $fillable = [
        'test_subject_id',
        'student_id',
        'obtained_marks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function testSubject()
    {
        return $this->belongsTo(TestSubject::class);
    }
    public function scopeTest($query, $testId)
    {
        return $query->whereHas('testSubject', function ($query) use ($testId) {
            $query->where('test_id', $testId);
        });
    }

    public function scopeForLectureNo($query, $lectureNo)
    {
        return $query->whereHas('testSubject', function ($query) use ($lectureNo) {
            $query->where('lecture_no', $lectureNo);
        });
    }
}
