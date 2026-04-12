<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'max_marks',
        'user_id',   //test owner id , blank if combined test

        'is_open',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'test_subjects', 'test_id', 'schedule_id');
    }

    public function testSubjects()
    {
        return $this->hasMany(TestSubject::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }
    public function scopeClosed($query)
    {
        return $query->where('is_open', false);
    }
    public function scopeCombined($query)
    {
        return $query->whereNull('user_id');
    }
    public function scopeIndividual($query)
    {
        return $query->whereNotNull('user_id');
    }
    public function scopeAccessible($query)
    {

        if (Auth::user()->hasRole('teacher')) {
            return  $query->whereHas('testSubjects', function ($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }
        return $query;
    }
    public function scopeMine($query)
    {
        if (session('role') == 'head' || session('role') == 'admin') {
            return $query;
        }
        return $query->where(function ($q) {
            $q->where('user_id', Auth::id())
              ->orWhere(function ($q2) {
                  $q2->open()->whereHas('testSubjects', function ($q3) {
                      $q3->where('user_id', Auth::id());
                  });
              });
        });
    }
}
