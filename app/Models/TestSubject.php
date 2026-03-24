<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSubject extends Model
{
    use HasFactory;
    protected $fillable = [
        'test_id',
        'section_id',
        'lecture_no',
        'subject_id',
        'user_id',
        'max_marks',
        'test_date',
        'result_date',
    ];

    protected $dates = ['test_date', 'result_date'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
    public function appearingStudents()
    {
        return $this->belongsToMany(Student::class, 'results', 'test_subject_id', 'student_id');
    }

    public function scopeCombined($query)
    {
        return $query->whereHas('test', function ($query) {
            $query->whereNull('user_id');
        });
    }
    public function scopeResultSubmitted($query)
    {
        return $query->whereNotNull('result_date');
    }
    public function scopeToday($query)
    {
        return $query->where('result_date', today());
    }
    public function scopeResultPending($query)
    {
        return $query->whereNull('result_date');
    }
    public function scopeActive($query)
    {
        return $query->whereHas('test', function ($query) {
            $query->where('is_open', true);
        });
    }
    public function hasBeenSubmitted()
    {
        if ($this->result_date != null)
            return true;
        else
            return false;
    }
    public function scopeMine($query)
    {

        // check if session has role 'head' or 'admin', return all
        if (session('role') == 'head' || session('role') == 'admin') {
            return $query;
        }

        // if (Auth::user()->hasAnyRole(['head', 'admin'])) {
        //     return $query;
        // }

        return $query->where('user_id', auth()->id());
    }
}
