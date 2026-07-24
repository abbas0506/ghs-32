<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'cheque_no',
        'description',
    ];

    public function lines()
    {
        return $this->hasMany(TransactionLine::class);
    }
    public function feeInvoices()
    {
        return $this->hasMany(FeeInvoice::class);
    }
}
