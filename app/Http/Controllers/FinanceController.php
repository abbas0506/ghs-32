<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Display the finance summary page.
     */
    public function index()
    {
        $currentSession = AcademicSession::current();

        if ($currentSession) {
            $ftfBalance = $currentSession->ftf_balance;
            $nsbBalance = $currentSession->nsb_balance;
            $specialGrantsBalance = $currentSession->special_grants_balance;

            // Live FTF Collection rate: paid vouchers / total vouchers in this session
            $totalPaid = $currentSession->ftfVouchers()
                ->join('ftf_payments', 'ftf_vouchers.id', '=', 'ftf_payments.ftf_voucher_id')
                ->whereNotNull('ftf_payments.payment_date')
                ->count();

            $totalVouchers = $currentSession->ftfVouchers()
                ->join('ftf_payments', 'ftf_vouchers.id', '=', 'ftf_payments.ftf_voucher_id')
                ->count();

            $ftfChange = $totalVouchers > 0 ? round(($totalPaid / $totalVouchers) * 100) : 0;

            // Live NSB budget receipt rate: nsb_collection / nsb_start (allocated budget)
            $nsbChange = $currentSession->nsb_start > 0 
                ? round(($currentSession->nsb_collection / $currentSession->nsb_start) * 100) 
                : 0;

            // Live Special Grants receipt rate: special_grants_collection / special_grants_start
            $specialGrantsChange = $currentSession->special_grants_start > 0
                ? round(($currentSession->special_grants_collection / $currentSession->special_grants_start) * 100)
                : 0;
        } else {
            $ftfBalance = 0;
            $nsbBalance = 0;
            $specialGrantsBalance = 0;
            $ftfChange = 0;
            $nsbChange = 0;
            $specialGrantsChange = 0;
        }

        // Fetch FTF Bank Account and SMC Bank Account
        $ftfAccount = \App\Models\Account::where('code', '1002')->orWhere('name', 'like', '%FTF%')->first();
        $smcAccount = \App\Models\Account::where('code', '1007')->orWhere('name', 'like', '%SMC%')->first();

        // Fetch each grant and compute balance
        $specialGrants = \App\Models\Grant::with(['installments', 'expenses'])->get();
        foreach ($specialGrants as $grant) {
            $received = $grant->installments->sum('amount');
            $spent = $grant->expenses->sum('amount');
            $grant->balance = $received - $spent;
        }

        return view('finance', compact(
            'ftfBalance', 
            'nsbBalance', 
            'specialGrantsBalance', 
            'ftfChange', 
            'nsbChange', 
            'specialGrantsChange', 
            'currentSession',
            'specialGrants',
            'ftfAccount',
            'smcAccount'
        ));
    }
}
