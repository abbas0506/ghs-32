@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-16 md:pb-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <a href="{{ route('finance.index') }}"
                   class="shrink-0 w-7 h-7 flex items-center justify-center bg-teal-50 hover:bg-teal-100 text-teal-600 rounded-lg transition-colors"
                   title="Back to Finance">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Fund Ledger</p>
                    <h1 class="text-sm font-extrabold text-slate-800 leading-tight truncate">Farogh-e-Taleem Fund (FTF)</h1>
                </div>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <button type="button" onclick="openModal('addReceiptModal')"
                    class="flex items-center gap-1 px-2 py-1.5 sm:px-3 text-[10px] font-bold bg-white hover:bg-teal-50 text-teal-700 border border-teal-200 rounded-lg shadow-sm transition-all"
                    title="Add Receipt">
                    <i class="bi bi-arrow-down-circle text-[12px]"></i>
                    <span class="hidden sm:inline">Add Receipt</span>
                </button>
                <button type="button" onclick="openModal('addWithdrawalModal')"
                    class="flex items-center gap-1 px-2 py-1.5 sm:px-3 text-[10px] font-bold bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 rounded-lg shadow-sm transition-all"
                    title="Cheque Withdrawal">
                    <i class="bi bi-arrow-up-circle text-[12px]"></i>
                    <span class="hidden sm:inline">Cheque Withdrawal</span>
                </button>
                <button type="button" onclick="openModal('addExpenseModal')"
                    class="flex items-center gap-1 px-2 py-1.5 sm:px-3 text-[10px] font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-lg shadow-sm transition-all"
                    title="Add Expense">
                    <i class="bi bi-receipt text-[12px]"></i>
                    <span class="hidden sm:inline">Add Expense</span>
                </button>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- FTF Info Banner --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 text-white rounded-2xl border border-emerald-400/20 shadow-lg">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-6 -bottom-6 w-24 h-24 bg-emerald-400/20 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 px-5 py-3.5">
                {{-- Row 1: Opening Balance --}}
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[8px] font-extrabold uppercase tracking-widest text-emerald-200/60">Opening Balance</span>
                    <span class="text-xs font-black text-white">{{ number_format($openingBalance ?? 0) }} <span class="text-[8px] text-emerald-300 font-semibold">PKR</span></span>
                </div>

                {{-- Divider --}}
                <div class="border-t border-white/15 mb-2.5"></div>

                {{-- Row 2: Received | Spent | Balance --}}
                <div class="flex items-center justify-between text-center">
                    <div class="flex-1">
                        <p class="text-[7px] font-extrabold uppercase tracking-wider text-emerald-200/60 leading-none">Received</p>
                        <p class="text-sm font-black text-emerald-200 leading-tight mt-1">{{ number_format($totalReceived) }}</p>
                        <p class="text-[7px] text-emerald-300/70 mt-0.5">PKR</p>
                    </div>
                    <span class="text-white/20 text-base mx-2">|</span>
                    <div class="flex-1">
                        <p class="text-[7px] font-extrabold uppercase tracking-wider text-emerald-200/60 leading-none">Spent</p>
                        <p class="text-sm font-black text-rose-200 leading-tight mt-1">{{ number_format($totalGross) }}</p>
                        <p class="text-[7px] text-emerald-300/70 mt-0.5">PKR</p>
                    </div>
                    <span class="text-white/20 text-base mx-2">|</span>
                    <div class="flex-1">
                        <p class="text-[7px] font-extrabold uppercase tracking-wider text-white/60 leading-none">Balance</p>
                        <p class="text-sm font-black text-white leading-tight mt-1">{{ number_format($balance) }}</p>
                        <p class="text-[7px] text-emerald-200/70 mt-0.5">PKR</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ledger Table --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-3 py-2 border-b border-slate-100 flex items-center justify-between bg-teal-50/50">
                <div class="flex items-center gap-2">
                    <i class="bi bi-journal-text text-teal-500 text-xs"></i>
                    <h3 class="text-[9px] font-extrabold text-teal-700 uppercase tracking-wider">Chronological Ledger</h3>
                </div>
                <span class="px-1.5 py-0.5 bg-teal-100 text-teal-700 rounded text-[8px] font-bold">
                    {{ $ledger->count() }} entries
                </span>
            </div>

            @if ($ledger->count() == 0)
                <div class="p-8 text-center">
                    <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="bi bi-inbox text-base"></i>
                    </div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">No ledger entries</p>
                    <p class="text-[8px] text-slate-450 mt-0.5">Use quick buttons above to record receipts or expenses.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 border-b border-slate-100">
                                <th class="py-2.5 px-3 text-[8px] font-extrabold uppercase tracking-wider">Ref & Date</th>
                                <th class="py-2.5 px-3 text-[8px] font-extrabold uppercase tracking-wider">Description</th>
                                <th class="py-2.5 px-3 text-[8px] font-extrabold uppercase tracking-wider text-right">Outflow (PKR)</th>
                                <th class="py-2.5 px-3 text-[8px] font-extrabold uppercase tracking-wider text-right">Inflow (PKR)</th>
                                <th class="py-2.5 px-3 text-[8px] font-extrabold uppercase tracking-wider text-right">Running Balance</th>
                                <th class="py-2.5 px-3 text-[8px] font-extrabold uppercase tracking-wider text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($ledger as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    {{-- Ref & Date --}}
                                    <td class="py-2.5 px-3 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-mono font-bold text-slate-600 leading-tight">
                                                {{ $item->receipt_no ?? '-' }}
                                            </span>
                                            <span class="text-[8px] font-bold text-slate-400 mt-0.5 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($item->date)->format('d M, Y') }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Description --}}
                                    <td class="py-2.5 px-3">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-slate-800 leading-tight">
                                                {{ $item->description }}
                                            </span>
                                            <span class="mt-0.5">
                                                @if ($item->type === 'receipt')
                                                    <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-150 rounded text-[7px] font-extrabold uppercase tracking-wider">
                                                        Receipt
                                                    </span>
                                                @elseif ($item->type === 'expense')
                                                    <span class="px-1.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-150 rounded text-[7px] font-extrabold uppercase tracking-wider">
                                                        Expense
                                                    </span>
                                                @elseif ($item->type === 'manual_transaction')
                                                    @if ($item->txn_direction === 'debit')
                                                        <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-150 rounded text-[7px] font-extrabold uppercase tracking-wider">
                                                            Bank Deposit
                                                        </span>
                                                    @else
                                                        <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-150 rounded text-[7px] font-extrabold uppercase tracking-wider">
                                                            Chq Withdrawal
                                                        </span>
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Outflow --}}
                                    <td class="py-2.5 px-3 text-right text-[10px] font-bold text-rose-600 whitespace-nowrap">
                                        @if ($item->type === 'expense' || ($item->type === 'manual_transaction' && $item->txn_direction === 'credit'))
                                            -{{ number_format($item->amount) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Inflow --}}
                                    <td class="py-2.5 px-3 text-right text-[10px] font-bold text-emerald-600 whitespace-nowrap">
                                        @if ($item->type === 'receipt' || ($item->type === 'manual_transaction' && $item->txn_direction === 'debit'))
                                            +{{ number_format($item->amount) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Running Balance --}}
                                    <td class="py-2.5 px-3 text-right text-[10px] font-black text-slate-800 whitespace-nowrap">
                                        {{ number_format($item->running_balance) }} <span class="text-[7px] text-slate-450 font-bold">PKR</span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="py-2.5 px-3 text-center whitespace-nowrap space-x-1">
                                        @if ($item->type === 'manual_transaction')
                                            {{-- Edit Button --}}
                                            <button type="button"
                                                onclick="openEditManualTransactionModal('{{ $item->raw_model->id }}', '{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}', '{{ $item->amount }}', '{{ $item->raw_model->cheque_no }}', '{{ addslashes($item->raw_model->description) }}', '{{ $item->txn_direction }}')"
                                                class="p-1 text-slate-400 hover:text-indigo-600 rounded hover:bg-indigo-50 transition" title="Edit Transaction">
                                                <i class="bi bi-pencil-square text-[11px]"></i>
                                            </button>
                                            
                                            {{-- Delete Button --}}
                                            <form action="{{ route('accounts.transaction.destroy', $item->raw_model->id) }}" method="POST" id="del-txn-{{ $item->raw_model->id }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDel(event, 'del-txn-{{ $item->raw_model->id }}')" class="p-1 text-slate-400 hover:text-rose-600 rounded hover:bg-rose-50 transition" title="Delete Transaction">
                                                    <i class="bi bi-trash text-[11px]"></i>
                                                </button>
                                            </form>
                                        @elseif ($item->type === 'expense')
                                            {{-- Edit Button --}}
                                            <button type="button"
                                                onclick="openEditExpenseModal('{{ $item->raw_model->id }}', '{{ $item->raw_model->expense_account_id }}', '{{ $item->expense_type }}', '{{ $item->net_amount }}', '{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}', '{{ $item->receipt_no }}', '{{ addslashes($item->raw_model->description) }}')"
                                                class="p-1 text-slate-400 hover:text-indigo-600 rounded hover:bg-indigo-50 transition" title="Edit Expense">
                                                <i class="bi bi-pencil-square text-[11px]"></i>
                                            </button>
 
                                            {{-- Delete Button --}}
                                            <form action="{{ route('expenses.destroy', $item->raw_model->id) }}" method="POST" id="del-exp-{{ $item->raw_model->id }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDel(event, 'del-exp-{{ $item->raw_model->id }}')" class="p-1 text-slate-400 hover:text-rose-600 rounded hover:bg-rose-50 transition" title="Delete Expense">
                                                    <i class="bi bi-trash text-[11px]"></i>
                                                </button>
                                            </form>
                                        @elseif ($item->type === 'receipt')
                                            {{-- Edit Button --}}
                                            <button type="button"
                                                onclick="openEditFeePaymentModal('{{ $item->raw_model->id }}', '{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}')"
                                                class="p-1 text-slate-400 hover:text-indigo-600 rounded hover:bg-indigo-50 transition" title="Edit Date">
                                                <i class="bi bi-pencil-square text-[11px]"></i>
                                            </button>

                                            {{-- Delete Button --}}
                                            <form action="{{ route('ftf-payments.destroy-direct', $item->raw_model->id) }}" method="POST" id="del-rec-{{ $item->raw_model->id }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDel(event, 'del-rec-{{ $item->raw_model->id }}')" class="p-1 text-slate-400 hover:text-rose-600 rounded hover:bg-rose-50 transition" title="Delete Payment">
                                                    <i class="bi bi-trash text-[11px]"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODAL 1: ADD RECEIPT (MANUAL DEPOSIT)
    ══════════════════════════════════════════════════════ --}}
    <div id="addReceiptModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="addReceiptCard"
            class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 bg-emerald-50 border-b border-emerald-100">
                <div>
                    <h3 class="text-xs font-black text-emerald-800 uppercase tracking-wider">Add Receipt</h3>
                    <p class="text-[9px] text-emerald-500 mt-0.5">Record a manual deposit to the FTF Bank Account</p>
                </div>
                <button type="button" onclick="closeModal('addReceiptModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-emerald-100 text-emerald-400 hover:text-emerald-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form action="{{ route('accounts.transaction.store', $ftfAccount->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="txn_type" value="debit">
                <input type="hidden" name="contra_account_id" value="{{ $ftfIncomeAccount->id }}">
                <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">

                {{-- Amount --}}
                <div>
                    <label for="modal_rec_amount" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Amount (PKR) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" id="modal_rec_amount" min="1" placeholder="e.g. 10000"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Date --}}
                <div>
                    <label for="modal_rec_date" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" id="modal_rec_date" value="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Cheque / Ref --}}
                <div>
                    <label for="modal_rec_cheque" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Cheque / Ref Number
                    </label>
                    <input type="text" name="cheque_no" id="modal_rec_cheque" placeholder="e.g. REF-FTF-001"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent outline-none transition">
                </div>

                {{-- Description --}}
                <div>
                    <label for="modal_rec_desc" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Description / Notes
                    </label>
                    <textarea name="description" id="modal_rec_desc" rows="2"
                        placeholder="e.g. Voluntary contribution..."
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent outline-none transition resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal('addReceiptModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Save Deposit
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODAL 2: CHEQUE WITHDRAWAL
    ══════════════════════════════════════════════════════ --}}
    <div id="addWithdrawalModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="addWithdrawalCard"
            class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 bg-blue-50 border-b border-blue-100">
                <div>
                    <h3 class="text-xs font-black text-blue-800 uppercase tracking-wider">Cheque Withdrawal</h3>
                    <p class="text-[9px] text-blue-500 mt-0.5">Withdraw funds from FTF Bank to Cash Account</p>
                </div>
                <button type="button" onclick="closeModal('addWithdrawalModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-blue-100 text-blue-400 hover:text-blue-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form action="{{ route('accounts.transaction.store', $ftfAccount->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="txn_type" value="credit">
                <input type="hidden" name="contra_account_id" value="{{ $cashAccount->id }}">
                <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">

                {{-- Amount --}}
                <div>
                    <label for="modal_with_amount" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Amount to Withdraw (PKR) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" id="modal_with_amount" min="1" placeholder="e.g. 15000"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Date --}}
                <div>
                    <label for="modal_with_date" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Withdrawal Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" id="modal_with_date" value="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Cheque No --}}
                <div>
                    <label for="modal_with_cheque" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Cheque Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cheque_no" id="modal_with_cheque" placeholder="e.g. CHQ-FTF-992"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Description --}}
                <div>
                    <label for="modal_with_desc" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Description / Notes
                    </label>
                    <textarea name="description" id="modal_with_desc" rows="2"
                        placeholder="e.g. Cheque withdrawal for FTF expenses..."
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal('addWithdrawalModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Confirm Withdrawal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODAL 3: ADD EXPENSE (PAID FROM CASH)
    ══════════════════════════════════════════════════════ --}}
    <div id="addExpenseModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="addExpenseCard"
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 bg-teal-50 border-b border-teal-100">
                <div>
                    <h3 class="text-xs font-black text-teal-800 uppercase tracking-wider">Add Expense</h3>
                    <p class="text-[9px] text-teal-500 mt-0.5">Record a cash expense associated with FTF</p>
                </div>
                <button type="button" onclick="closeModal('addExpenseModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-teal-100 text-teal-400 hover:text-teal-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form action="{{ route('expenses.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="form_type" value="grant_expense">
                <input type="hidden" name="fund_type" value="ftf">
                <input type="hidden" name="tax_type" value="none">
                <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">

                {{-- Expense Category --}}
                <div>
                    <label for="modal_expense_account_id" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Expense Category / Account <span class="text-red-500">*</span>
                    </label>
                    <select name="expense_account_id" id="modal_expense_account_id" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400 bg-white" required>
                        <option value="">— Select Category —</option>
                        @foreach ($expenseAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Expense Type --}}
                <div>
                    <label for="modal_expense_type" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Expense Type <span class="text-red-500">*</span>
                    </label>
                    <select name="expense_type" id="modal_expense_type" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400 bg-white" required>
                        <option value="purchase">Purchase</option>
                        <option value="service">Service</option>
                        <option value="utility">Utility</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                {{-- Amount & Date --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="modal_exp_amount" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                            Amount (PKR) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" id="modal_exp_amount" min="1" placeholder="e.g. 5000"
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                    </div>
                    <div>
                        <label for="modal_exp_date" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                            Expense Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="expense_date" id="modal_exp_date" value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                    </div>
                </div>

                {{-- Receipt No --}}
                <div>
                    <label for="modal_exp_receipt" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Receipt / Invoice Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="receipt_no" id="modal_exp_receipt" placeholder="e.g. REC-10294"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Description --}}
                <div>
                    <label for="modal_exp_desc" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Description / Notes
                    </label>
                    <textarea name="description" id="modal_exp_desc" rows="2"
                        placeholder="e.g. Stationery purchase..."
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal('addExpenseModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════
         MODAL 4: EDIT FEE PAYMENT (STUDENT RECEIPT)
    ══════════════════════════════════════════════════════ --}}
    <div id="editFeePaymentModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="editFeePaymentCard"
            class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            <div class="flex items-center justify-between px-5 py-3.5 bg-emerald-50 border-b border-emerald-100">
                <div>
                    <h3 class="text-xs font-black text-emerald-800 uppercase tracking-wider">Edit Fee Payment</h3>
                    <p class="text-[9px] text-emerald-500 mt-0.5">Update the payment date of fee collection</p>
                </div>
                <button type="button" onclick="closeModal('editFeePaymentModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-emerald-100 text-emerald-400 hover:text-emerald-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form id="editFeePaymentForm" action="" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PUT')

                {{-- Date --}}
                <div>
                    <label for="edit_fee_payment_date" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Payment Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="payment_date" id="edit_fee_payment_date"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-transparent outline-none transition" required>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editFeePaymentModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Update Date
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════
         MODAL 5: EDIT MANUAL TRANSACTION (DEPOSIT/WITHDRAWAL)
    ══════════════════════════════════════════════════════ --}}
    <div id="editManualTxnModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="editManualTxnCard"
            class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            <div class="flex items-center justify-between px-5 py-3.5 bg-blue-50 border-b border-blue-100">
                <div>
                    <h3 class="text-xs font-black text-blue-800 uppercase tracking-wider">Edit Transaction</h3>
                    <p class="text-[9px] text-blue-500 mt-0.5">Modify manual FTF bank transaction</p>
                </div>
                <button type="button" onclick="closeModal('editManualTxnModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-blue-100 text-blue-400 hover:text-blue-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form id="editManualTxnForm" action="" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                <input type="hidden" name="account_id" id="edit_txn_account_id" value="{{ $ftfAccount->id }}">
                <input type="hidden" name="txn_type" id="edit_txn_type" value="">
                <input type="hidden" name="contra_account_id" id="edit_txn_contra_account_id" value="">

                {{-- Amount --}}
                <div>
                    <label for="edit_txn_amount" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Amount (PKR) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" id="edit_txn_amount" min="1"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Date --}}
                <div>
                    <label for="edit_txn_date" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" id="edit_txn_date"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Cheque No --}}
                <div>
                    <label for="edit_txn_cheque" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Cheque / Ref Number
                    </label>
                    <input type="text" name="cheque_no" id="edit_txn_cheque"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition">
                </div>

                {{-- Description --}}
                <div>
                    <label for="edit_txn_desc" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Description / Notes
                    </label>
                    <textarea name="description" id="edit_txn_desc" rows="2"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editManualTxnModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Update Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════
         MODAL 6: EDIT EXPENSE
    ══════════════════════════════════════════════════════ --}}
    <div id="editExpenseModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="editExpenseCard"
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            <div class="flex items-center justify-between px-5 py-3.5 bg-teal-50 border-b border-teal-100">
                <div>
                    <h3 class="text-xs font-black text-teal-800 uppercase tracking-wider">Edit Expense</h3>
                    <p class="text-[9px] text-teal-500 mt-0.5">Modify FTF cash expense</p>
                </div>
                <button type="button" onclick="closeModal('editExpenseModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-teal-100 text-teal-400 hover:text-teal-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form id="editExpenseForm" action="" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="grant_expense">
                <input type="hidden" name="fund_type" value="ftf">
                <input type="hidden" name="tax_type" value="none">
                <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">

                {{-- Expense Category --}}
                <div>
                    <label for="edit_expense_account_id" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Expense Category / Account <span class="text-red-500">*</span>
                    </label>
                    <select name="expense_account_id" id="edit_expense_account_id" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400 bg-white" required>
                        <option value="">— Select Category —</option>
                        @foreach ($expenseAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Expense Type --}}
                <div>
                    <label for="edit_expense_type" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Expense Type <span class="text-red-500">*</span>
                    </label>
                    <select name="expense_type" id="edit_expense_type" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400 bg-white" required>
                        <option value="purchase">Purchase</option>
                        <option value="service">Service</option>
                        <option value="utility">Utility</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                {{-- Amount & Date --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="edit_exp_amount" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                            Amount (PKR) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" id="edit_exp_amount" min="1"
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                    </div>
                    <div>
                        <label for="edit_exp_date" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                            Expense Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="expense_date" id="edit_exp_date"
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                    </div>
                </div>

                {{-- Receipt No --}}
                <div>
                    <label for="edit_exp_receipt" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Receipt / Invoice Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="receipt_no" id="edit_exp_receipt"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Description --}}
                <div>
                    <label for="edit_exp_desc" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Description / Notes
                    </label>
                    <textarea name="description" id="edit_exp_desc" rows="2"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editExpenseModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Update Expense
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
<script>
    /* ─────────── Modal helpers ─────────── */
    function openModal(id) {
        const modal = document.getElementById(id);
        let cardId = 'addReceiptCard';
        if (id === 'addWithdrawalModal') cardId = 'addWithdrawalCard';
        else if (id === 'addExpenseModal') cardId = 'addExpenseCard';
        else if (id === 'editFeePaymentModal') cardId = 'editFeePaymentCard';
        else if (id === 'editManualTxnModal') cardId = 'editManualTxnCard';
        else if (id === 'editExpenseModal') cardId = 'editExpenseCard';
        const card  = document.getElementById(cardId);
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        let cardId = 'addReceiptCard';
        if (id === 'addWithdrawalModal') cardId = 'addWithdrawalCard';
        else if (id === 'addExpenseModal') cardId = 'addExpenseCard';
        else if (id === 'editFeePaymentModal') cardId = 'editFeePaymentCard';
        else if (id === 'editManualTxnModal') cardId = 'editManualTxnCard';
        else if (id === 'editExpenseModal') cardId = 'editExpenseCard';
        const card  = document.getElementById(cardId);
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    /* Close modal on backdrop click */
    ['addReceiptModal', 'addWithdrawalModal', 'addExpenseModal', 'editFeePaymentModal', 'editManualTxnModal', 'editExpenseModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function (e) {
                if (e.target === this) closeModal(id);
            });
        }
    });

    /* Populate and open Edit modals */
    function openEditFeePaymentModal(id, date) {
        const form = document.getElementById('editFeePaymentForm');
        form.action = `/ftf-payments/${id}`;
        document.getElementById('edit_fee_payment_date').value = date;
        openModal('editFeePaymentModal');
    }

    function openEditManualTransactionModal(id, date, amount, chequeNo, description, txnDirection) {
        const form = document.getElementById('editManualTxnForm');
        form.action = `/accounts/transactions/${id}`;
        document.getElementById('edit_txn_amount').value = amount;
        document.getElementById('edit_txn_date').value = date;
        document.getElementById('edit_txn_cheque').value = chequeNo === '-' ? '' : chequeNo;
        document.getElementById('edit_txn_desc').value = description;
        document.getElementById('edit_txn_type').value = txnDirection;
        const cashAccountId = '{{ $cashAccount->id }}';
        const ftfIncomeAccountId = '{{ $ftfIncomeAccount->id }}';
        document.getElementById('edit_txn_contra_account_id').value = (txnDirection === 'credit') ? cashAccountId : ftfIncomeAccountId;
        openModal('editManualTxnModal');
    }

    function openEditExpenseModal(id, categoryId, type, netAmount, date, receiptNo, description) {
        const form = document.getElementById('editExpenseForm');
        form.action = `/expenses/${id}`;
        document.getElementById('edit_expense_account_id').value = categoryId;
        document.getElementById('edit_expense_type').value = type;
        document.getElementById('edit_exp_amount').value = netAmount;
        document.getElementById('edit_exp_date').value = date;
        document.getElementById('edit_exp_receipt').value = receiptNo;
        document.getElementById('edit_exp_desc').value = description;
        openModal('editExpenseModal');
    }

    /* ─────────── Delete confirmation ─────────── */
    function confirmDel(event, formId) {
        event.preventDefault();
        Swal.fire({
            title: 'Delete this entry?',
            text: 'This action cannot be undone and will revert the transaction line from ledger.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete!'
        }).then(result => {
            if (result.isConfirmed) document.getElementById(formId).submit();
        });
    }
</script>
@endsection
