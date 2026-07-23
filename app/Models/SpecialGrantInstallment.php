<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialGrantInstallment extends Model
{
    use HasFactory;

    protected $table = 'special_grant_installments';

    protected $fillable = [
        'special_grant_id',
        'amount',
        'received_date',
        'description',
    ];

    protected $casts = [
        'received_date' => 'date',
        'amount' => 'integer',
    ];

    public function grant()
    {
        return $this->belongsTo(SpecialGrant::class, 'special_grant_id');
    }
}
