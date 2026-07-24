<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;

    protected $table = 'grants';

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
        return $this->hasMany(GrantInstallment::class, 'grant_id');
    }

    /**
     * All expenses charged against this grant.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'grant_id');
    }

    /**
     * Total amount received across all installments.
     */
    public function getTotalReceivedAttribute(): int
    {
        return (int) $this->installments()->sum('amount');
    }
}
