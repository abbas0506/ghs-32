<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'expense_account_id',
        'payment_account_id',
        'status',
        'transaction_id',
        'fund_type',
        'grant_id',
        'receipt_no',
        'tax_type',
        'gst_rate',
        'pst_rate',
        'it_rate',
        'net_amount',
        'expense_type',
        'school_resolution_id',
        'description',
    ];

    protected $casts = [
        'amount' => 'integer',
        'gst_rate' => 'float',
        'pst_rate' => 'float',
        'it_rate' => 'float',
        'net_amount' => 'integer',
        'status' => 'boolean',
        'grant_id' => 'integer',
        'school_resolution_id' => 'integer',
    ];

    public function getGstAmountAttribute()
    {
        return (int) round(($this->net_amount * $this->gst_rate) / 100);
    }

    public function getPstAmountAttribute()
    {
        return (int) round(($this->net_amount * $this->pst_rate) / 100);
    }

    public function getItAmountAttribute()
    {
        return (int) round(($this->net_amount * $this->it_rate) / 100);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function expenseAccount()
    {
        return $this->belongsTo(Account::class, 'expense_account_id');
    }

    public function paymentAccount()
    {
        return $this->belongsTo(Account::class, 'payment_account_id');
    }

    public function grant()
    {
        return $this->belongsTo(Grant::class, 'grant_id');
    }

    public function schoolResolution()
    {
        return $this->belongsTo(SchoolResolution::class, 'school_resolution_id');
    }
}
