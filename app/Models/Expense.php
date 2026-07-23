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
        'special_grant_id',
        'receipt_no',
        'tax_type',
        'gst_rate',
        'pst_rate',
        'it_rate',
        'gst_amount',
        'pst_amount',
        'it_amount',
        'net_amount',
    ];

    protected $casts = [
        'amount' => 'integer',
        'gst_rate' => 'float',
        'pst_rate' => 'float',
        'it_rate' => 'float',
        'gst_amount' => 'integer',
        'pst_amount' => 'integer',
        'it_amount' => 'integer',
        'net_amount' => 'integer',
        'status' => 'boolean',
        'special_grant_id' => 'integer',
    ];

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

    public function specialGrant()
    {
        return $this->belongsTo(SpecialGrant::class, 'special_grant_id');
    }
}
