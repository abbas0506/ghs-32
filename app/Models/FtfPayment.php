<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FtfPayment extends Model
{
    use HasFactory;
    
    protected $table = 'ftf_payments';

    protected $fillable = [
        'ftf_voucher_id',
        'student_id',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function ftfVoucher()
    {
        return $this->belongsTo(FtfVoucher::class, 'ftf_voucher_id');
    }

    public function feeVoucher()
    {
        return $this->ftfVoucher();
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
