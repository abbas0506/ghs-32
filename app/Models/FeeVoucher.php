<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }
}
