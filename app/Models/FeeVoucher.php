<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FeeVoucher extends Model
{
    use HasFactory;
    protected $fillable = [
        'year',
        'month',
        'amount',
        'due_date',
        'description',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }
    public function  students()
    {
        return $this->belongsToMany(Student::class, 'fee_payments', 'student_id', 'fee_voucher_id');
    }
    public function isOpen()
    {
        return $this->due_date >= date('Y-m-d');
    }
    // get sum of amount paid
    public function sumOfPaidAmount()
    {
        $sections = Auth::user()->accessibleSections();
        $studentIds = Student::whereIn('section_id', $sections->pluck('id'))->pluck('id');

        return $this->feePayments()->whereIn('student_id', $studentIds)->whereNotNull('payment_date')->count() * $this->amount;
    }
    public function sumOfDueAmount()
    {
        $sections = Auth::user()->accessibleSections();
        $studentIds = Student::whereIn('section_id', $sections->pluck('id'))->pluck('id');

        return $this->feePayments()->whereIn('student_id', $studentIds)->count() * $this->amount;
    }
    public function sumOfPayableAmount()
    {
        $sections = Auth::user()->accessibleSections();
        $studentIds = Student::whereIn('section_id', $sections->pluck('id'))->pluck('id');

        return $this->feePayments()->whereIn('student_id', $studentIds)->count() * $this->amount;
    }

    public function getNameAttribute()
    {
        return $this->description;
    }

    public function studentsFromSection($sectionId)
    {
        return $this->feePayments()->whereHas('student', function ($query) use ($sectionId) {
            $query->where('section_id', $sectionId);
        });
    }

    public function studentsWhoHavePaid($sectionId)
    {
        return $this->feePayments()
            ->whereNotNull('payment_date')
            ->whereHas('student', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            });
    }

    public function studentsWhoHavePaidToday($sectionId)
    {
        return $this->feePayments()
            ->whereDate('payment_date', now()->toDateString())
            ->whereHas('student', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            });
    }
}
