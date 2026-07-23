<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialGrant extends Model
{
    use HasFactory;

    protected $table = 'special_grants';

    protected $fillable = [
        'title',
        'issued_by',
        'description',
    ];

    /**
     * All installment payments received against this grant.
     */
    public function installments()
    {
        return $this->hasMany(SpecialGrantInstallment::class);
    }

    /**
     * Total amount received across all installments.
     */
    public function getTotalReceivedAttribute(): int
    {
        return (int) $this->installments()->sum('amount');
    }
}
