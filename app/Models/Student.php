<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'father_name',
        'bform',
        'gender',
        'dob',
        'phone',
        'address',
        'photo',
        'id_mark',
        'caste',
        'distinction',
        'is_orphan',
        'fee',

        //academic info
        'section_id',
        'rollno',
        'admission_no',
        'admission_date',

    ];

    protected $casts = [
        'dob' => 'date',   // Cast as Carbon date
        'admission_date' => 'date',   // Cast as Carbon date
    ];
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function feeInvoices()
    {
        return $this->hasMany(FeeInvoice::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function ftfPayments()
    {
        return $this->hasMany(FtfPayment::class);
    }

    public function feePayments()
    {
        return $this->ftfPayments();
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function testSubjects()
    {
        return $this->hasManyThrough(TestSubject::class, Result::class);
    }

    public function testRank($sortedPercentages)
    {
        $index = $sortedPercentages->search(function ($ranking) {
            return $ranking['id'] == $this->id;
        });

        //!== will only be false if record could not be found
        if ($index !== false) {
            $ranking = $sortedPercentages->get($index);
            return $ranking['position'];
        } else {
            return '';
        }
    }
    public function testTotal($sortedPercentages)
    {
        // Find the key (position) of the specific student
        $index = $sortedPercentages->search(function ($ranking) {
            return $ranking['id'] == $this->id;
        });

        //!== will only be false if record could not be found
        if ($index !== false) {
            $ranking = $sortedPercentages->get($index);
            return $ranking['max_marks'];
        } else {
            return '';
        }
    }
    public function testAggregate($sortedPercentages)
    {
        // Find the key (position) of the specific student
        $index = $sortedPercentages->search(function ($ranking) {
            return $ranking['id'] == $this->id;
        });

        //!== will only be false if record could not be found
        if ($index !== false) {
            $ranking = $sortedPercentages->get($index);
            return $ranking['obtained_marks'];
        } else {
            return '';
        }
    }

    public function testPercentage($sortedPercentages)
    {
        // Find the key (position) of the specific student
        $index = $sortedPercentages->search(function ($ranking) {
            return $ranking['id'] == $this->id;
        });

        //!== will only be false if record could not be found
        if ($index !== false) {
            $ranking = $sortedPercentages->get($index);
            return $ranking['percentage'];
        } else {
            return '';
        }
    }

    public function maximumMarks($testId)
    {
        $sumMarks = $this  // Find the student by ID
            ->results()  // Get the student's results
            ->whereHas('testSubject', function ($query) use ($testId) {
                $query->where('test_id', $testId);  // Filter by test_id in the test_subjects
            })
            ->join('test_subjects', 'results.test_subject_id', '=', 'test_subjects.id')  // Join test_subjects to results
            ->sum('test_subjects.max_marks');  // Sum the max_marks from the test_subjects


        return $sumMarks;
    }
    public function scopeCreatedToday($query)
    {
        return $query->whereDate('created_at', today());
    }
    public function scopeCreatedThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }

    public function hasBeenCreatedThisWeek()
    {
        return $this->created_at
            ? $this->created_at->isCurrentWeek()
            : false;
    }
    public function scopeCreatedThisWeek($query)
    {
        return $query->whereWeek('created_at', now()->week);
    }

    public function previousAbsences()
    {
        return $this->attendances()
            ->where('status', 0)
            ->whereDate('date', '<', today());
    }
    public function currentAbsences()
    {
        return $this->attendances()
            ->where('status', 0)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year);
    }
}
