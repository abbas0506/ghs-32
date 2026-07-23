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
        return SpecialGrantInstallment::whereBetween('received_date', [
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
     * FTF collection = sum of paid voucher amounts in this session.
     */
    public function getFtfCollectionAttribute(): int
    {
        return (int) FtfVoucher::whereBetween('due_date', [
                $this->start_date->toDateString(),
                $this->end_date->toDateString(),
            ])
            ->join('ftf_payments', 'ftf_vouchers.id', '=', 'ftf_payments.ftf_voucher_id')
            ->whereNotNull('ftf_payments.payment_date')
            ->sum('ftf_vouchers.amount');
    }

    /**
     * NSB collection = sum of NSB receipt amounts in this session.
     */
    public function getNsbCollectionAttribute(): int
    {
        return (int) NsbReceipt::whereBetween('received_date', [
            $this->start_date->toDateString(),
            $this->end_date->toDateString(),
        ])->sum('amount');
    }

    /**
     * Special Grants collection = sum of installment amounts in this session.
     */
    public function getSpecialGrantsCollectionAttribute(): int
    {
        return (int) SpecialGrantInstallment::whereBetween('received_date', [
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
        return (int) Expense::whereBetween('created_at', [
            $this->start_date->startOfDay()->toDateTimeString(),
            $this->end_date->endOfDay()->toDateTimeString(),
        ])->where('fund_type', 'nsb')->sum('amount');
    }

    /**
     * Special Grants expenses in this session (fund_type = 'special_grant').
     */
    public function getSpecialGrantsExpensesAttribute(): int
    {
        return (int) Expense::whereBetween('created_at', [
            $this->start_date->startOfDay()->toDateTimeString(),
            $this->end_date->endOfDay()->toDateTimeString(),
        ])->where('fund_type', 'special_grant')->sum('amount');
    }

    /**
     * Live FTF Balance = ftf_start + ftf_collection - ftf_expenses
     */
    public function getFtfBalanceAttribute(): int
    {
        return $this->ftf_start + $this->ftf_collection - $this->ftf_expenses;
    }

    /**
     * Live NSB Balance = nsb_start + nsb_collection - nsb_expenses
     */
    public function getNsbBalanceAttribute(): int
    {
        return $this->nsb_start + $this->nsb_collection - $this->nsb_expenses;
    }

    /**
     * Live Special Grants Balance = special_grants_start + special_grants_collection - special_grants_expenses
     */
    public function getSpecialGrantsBalanceAttribute(): int
    {
        return $this->special_grants_start + $this->special_grants_collection - $this->special_grants_expenses;
    }
}
