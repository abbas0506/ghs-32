<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NsbReceipt extends Model
{
    use HasFactory;

    protected $table = 'nsb_receipts';

    protected $fillable = [
        'quarter',
        'amount',
        'received_date',
        'description',
    ];

    protected $casts = [
        'received_date' => 'date',
        'quarter' => 'integer',
        'amount' => 'integer',
    ];
}
