<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'ftf_start',
        'nsb_start',
        'special_grants_start',
        'is_current',
    ];

    protected $casts = [
        'start_date'            => 'date',
        'end_date'              => 'date',
        'is_current'            => 'boolean',
        'ftf_start'             => 'integer',
        'nsb_start'             => 'integer',
        'special_grants_start'  => 'integer',
    ];

    /**
     * The "booted" method of the model.
     * Ensure only one session is current at a time.
     */
    protected static function booted()
    {
        static::saving(function ($session) {
            if ($session->is_current) {
                static::where('id', '!=', $session->id)->update(['is_current' => false]);
            }
        });
    }

    /**
     * Get the currently active academic session.
     */
    public static function current()
    {
        return static::where('is_current', true)->first();
    }

    // ── Date-range query helpers ──

    /**
     * FTF vouchers whose due_date falls within this session.
     */
    public function ftfVouchers()
    {
        return FtfVoucher::whereBetween('due_date', [
            $this->start_date->toDateString(),
            $this->end_date->toDateString(),
        ]);
    }

    /**
     * NSB receipts whose received_date falls within this session.
     */
    public function nsbReceipts()
    {
        return NsbReceipt::whereBetween('received_date', [
            $this->start_date->toDateString(),
            $this->end_date->toDateString(),
        ]);
    }

    /**
     * Expenses whose created_at falls within this session.
     */
    public function expenses()
    {
        return Expense::whereBetween('created_at', [
            $this->start_date->startOfDay()->toDateTimeString(),
            $this->end_date->endOfDay()->toDateTimeString(),
        ]);
    }

    /**
     * Special grant installments whose received_date falls within this session.
     */
    public function specialGrantInstallments()
    {
        return GrantInstallment::whereBetween('received_date', [
            $this->start_date->toDateString(),
            $this->end_date->toDateString(),
        ]);
    }

    /**
     * Schedules that overlap with this session (start_date <= session end AND end_date >= session start).
     */
    public function schedules()
    {
        return Schedule::where('start_date', '<=', $this->end_date->toDateString())
                       ->where('end_date', '>=', $this->start_date->toDateString());
    }

    // ── Computed Attributes ──

    /**
     * FTF collection = sum of manual deposits to FTF Bank Account in this session.
     */
    public function getFtfCollectionAttribute(): int
    {
        $ftfAccount = Account::where('code', '1002')->first();
        if (!$ftfAccount) return 0;
        return (int) \App\Models\TransactionLine::where('account_id', $ftfAccount->id)
            ->whereHas('transaction', function($q) {
                $q->whereBetween('date', [
                    $this->start_date->toDateString(),
                    $this->end_date->toDateString(),
                ]);
            })
            ->sum('debit');
    }

    /**
     * NSB collection = sum of NSB receipt amounts in this session.
     */
    public function getNsbCollectionAttribute(): int
    {
        $nsbGrant = \App\Models\Grant::where('title', 'like', '%NSB%')->orWhere('title', 'like', '%Non-Salary%')->first();
        if (!$nsbGrant) return 0;
        return (int) $nsbGrant->installments()->whereBetween('received_date', [
            $this->start_date->toDateString(),
            $this->end_date->toDateString(),
        ])->sum('amount');
    }

    /**
     * Special Grants collection = sum of installment amounts in this session.
     */
    public function getSpecialGrantsCollectionAttribute(): int
    {
        $nsbGrant = \App\Models\Grant::where('title', 'like', '%NSB%')->orWhere('title', 'like', '%Non-Salary%')->first();
        $nsbId = $nsbGrant ? $nsbGrant->id : 0;
        return (int) \App\Models\GrantInstallment::where('grant_id', '!=', $nsbId)->whereBetween('received_date', [
            $this->start_date->toDateString(),
            $this->end_date->toDateString(),
        ])->sum('amount');
    }

    /**
     * FTF expenses in this session (fund_type = 'ftf').
     */
    public function getFtfExpensesAttribute(): int
    {
        return (int) Expense::whereBetween('created_at', [
            $this->start_date->startOfDay()->toDateTimeString(),
            $this->end_date->endOfDay()->toDateTimeString(),
        ])->where('fund_type', 'ftf')->sum('amount');
    }

    /**
     * NSB expenses in this session (fund_type = 'nsb').
     */
    public function getNsbExpensesAttribute(): int
    {
        $nsbGrant = \App\Models\Grant::where('title', 'like', '%NSB%')->orWhere('title', 'like', '%Non-Salary%')->first();
        if (!$nsbGrant) return 0;
        return (int) Expense::whereBetween('created_at', [
            $this->start_date->startOfDay()->toDateTimeString(),
            $this->end_date->endOfDay()->toDateTimeString(),
        ])->where('grant_id', $nsbGrant->id)->sum('amount');
    }

    /**
     * Special Grants expenses in this session (fund_type = 'special_grant').
     */
    public function getSpecialGrantsExpensesAttribute(): int
    {
        $nsbGrant = \App\Models\Grant::where('title', 'like', '%NSB%')->orWhere('title', 'like', '%Non-Salary%')->first();
        $nsbId = $nsbGrant ? $nsbGrant->id : 0;
        return (int) Expense::whereBetween('created_at', [
            $this->start_date->startOfDay()->toDateTimeString(),
            $this->end_date->endOfDay()->toDateTimeString(),
        ])->where('grant_id', '!=', $nsbId)->whereNotNull('grant_id')->sum('amount');
    }

    /**
     * Live FTF Balance = ftf_start + ftf_collection (deposits) - ftf_withdrawals - ftf_expenses
     */
    public function getFtfBalanceAttribute(): int
    {
        $ftfAccount = Account::where('code', '1002')->first();
        if (!$ftfAccount) return $this->ftf_start - $this->getFtfExpensesAttribute();
        
        $withdrawals = (int) \App\Models\TransactionLine::where('account_id', $ftfAccount->id)
            ->whereHas('transaction', function($q) {
                $q->whereBetween('date', [
                    $this->start_date->toDateString(),
                    $this->end_date->toDateString(),
                ]);
            })
            ->sum('credit');

        return $this->ftf_start + $this->ftf_collection - $withdrawals - $this->getFtfExpensesAttribute();
    }

    /**
     * Live NSB Balance = nsb_start + nsb_collection - nsb_expenses
     */
    public function getNsbBalanceAttribute(): int
    {
        $nsbGrant = \App\Models\Grant::where('title', 'like', '%NSB%')->orWhere('title', 'like', '%Non-Salary%')->first();
        if (!$nsbGrant) return 0;
        $received = $nsbGrant->installments()->sum('amount');
        $spent = $nsbGrant->expenses()->sum('amount');
        return $received - $spent;
    }

    /**
     * Live Special Grants Balance = special_grants_start + special_grants_collection - special_grants_expenses
     */
    public function getSpecialGrantsBalanceAttribute(): int
    {
        $nsbGrant = \App\Models\Grant::where('title', 'like', '%NSB%')->orWhere('title', 'like', '%Non-Salary%')->first();
        $nsbId = $nsbGrant ? $nsbGrant->id : 0;
        $received = \App\Models\GrantInstallment::where('grant_id', '!=', $nsbId)->sum('amount');
        $spent = Expense::where('grant_id', '!=', $nsbId)->whereNotNull('grant_id')->sum('amount');
        return $received - $spent;
    }
}
