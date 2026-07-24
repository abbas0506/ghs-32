<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrantInstallment extends Model
{
    use HasFactory;

    protected $table = 'grant_installments';

    protected $fillable = [
        'grant_id',
        'amount',
        'received_date',
        'description',
        'cheque_no',
        'transaction_id',
    ];

    protected $casts = [
        'received_date' => 'date',
        'amount' => 'integer',
    ];

    public function grant()
    {
        return $this->belongsTo(Grant::class, 'grant_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
