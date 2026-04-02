<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',    //section label A, B, C
        'grade_id',
    ];

    public function incharge()
    {
        //
        $inchargeId = $this->allocations->where('lecture_no', 1)->value('user_id');
        $incharge = User::findOrFail($inchargeId);
        return $incharge;
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function testSubjects()
    {
        return $this->hasMany(TestSubject::class);
    }
    public function scopeActive($query)
    {
        return true;
    }
    public function  studentRank($sortedPercentages, $specificId)
    {
        $index = $sortedPercentages->search(function ($student) use ($specificId) {
            return $student['id'] === $specificId;
        });

        if ($index !== false) {
            return $index + 1;
        } else {
            return '';
        }
    }
    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Student::class);
    }
    public function feeInvoices()
    {
        return $this->hasManyThrough(FeeVoucher::class, Student::class);
    }
    public function attendanceMarked()
    {
        return $this->attendances()->whereDate('date', today())->count();
    }
    public function scopeAccessible($query)
    {
        if (Auth::user()->hasAnyRole(['head', 'admin'])) {
            return $query;
        }

        return  $query->whereHas('testSubjects', function ($q) {
            $q->where('user_id', Auth::user()->id);
        });
    }
    // get average attendance of the section during last week
    public function averageAttendance()
    {
        //get unque dates from $section->attendances
        $currentDate = \Carbon\Carbon::parse(today());
        $sessionStart = $currentDate->month >= 4
            ? \Carbon\Carbon::create($currentDate->year, 4, 1)
            : \Carbon\Carbon::create($currentDate->year - 1, 4, 1);

        // During session
        $overallPresence = $this->attendances()
            ->where('attendances.status', 1)
            ->whereDate('date', '>=', $sessionStart)
            ->whereDate('date', '<=', $currentDate)
            ->get();

        $overallAttendance = $this->attendances()
            ->whereDate('date', '>=', $sessionStart)
            ->whereDate('date', '<=', $currentDate)
            ->get();
        if ($overallAttendance->count() == 0) {
            return 0;
        }

        return round($overallPresence->count() / ($overallAttendance->count()) * 100, 1);
    }

    public function newAdmissions()
    {
        // get new admission during last 7 days
        $newStudents = $this->students()
            ->where('created_at', '>=', \Carbon\Carbon::now()->subWeek())
            ->get();

        return $newStudents;
    }
}
