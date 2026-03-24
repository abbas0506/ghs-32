<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'short_name',
        'father_name',
        'cnic',
        'dob',
        'blood_group',
        'address',
        'phone',
        'joined_at',
        'designation',
        'qualification',
        'bps',
        'personal_no',
        'photo',
        'is_active',
        'seniority',
    ];

    protected $casts = [
        'dob' => 'date',   // Cast as Carbon date
        'joined_at' => 'date',   // Cast as Carbon date
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function allocations()
    {
        return $this->hasMany(Schedule::class);
    }
    public function tests()
    {
        return $this->hasMany(Test::class);
    }
    public function testSubjects()
    {
        return $this->hasMany(TestSubject::class);
    }
    public function isIncharge()
    {
        return $this->allocations->where('lecture_no', 1)->count();
    }
    public function accessibleSections()
    {
        $sectionId = $this->allocations->where('lecture_no', 1)->value('section_id');
        $section = Section::find($sectionId);
        return $section;
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class)
            ->withPivot('status')
            ->withTimestamps();
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
