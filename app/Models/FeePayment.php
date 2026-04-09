<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'fee_voucher_id',
        'student_id',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function feeVoucher()
    {
        return $this->belongsTo(FeeVoucher::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
