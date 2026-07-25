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
        'grant_id',
    ];

    public function lines()
    {
        return $this->hasMany(TransactionLine::class);
    }
    public function feeInvoices()
    {
        return $this->hasMany(FeeInvoice::class);
    }
    public function grant()
    {
        return $this->belongsTo(Grant::class);
    }
}
