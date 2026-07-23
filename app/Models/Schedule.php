<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'lecture_no',
        'subject_id',
        'user_id',
        'day1',
        'day2',
        'day3',
        'day4',
        'day5',
        'day6',
        'room_no',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /**
     * Scope: schedules that overlap with a given session date range.
     */
    public function scopeForSession($query, $sessionStartDate, $sessionEndDate)
    {
        return $query->where('start_date', '<=', $sessionEndDate)
                     ->where('end_date', '>=', $sessionStartDate);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function testSubjects()
    {
        return $this->hasMany(TestSubject::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeHavingLectureNo($query, $lecture_no)
    {
        return $query->where('lecture_no', $lecture_no);
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'test_subjects', 'test_id', 'allocation_id')
            ->withTimestamps();
    }
}
