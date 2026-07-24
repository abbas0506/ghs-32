@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-16 md:pb-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
            <div class="flex items-center gap-2">
                <a href="{{ route('accounts.index') }}"
                   class="w-7 h-7 flex items-center justify-center bg-teal-50 hover:bg-teal-100 text-teal-600 rounded-lg transition-colors"
                   title="Back to Accounts">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Account Ledger</p>
                    <h1 class="text-sm font-extrabold text-slate-800 leading-tight">{{ $account->name }}</h1>
                </div>
            </div>
            <button type="button" onclick="openModal('addTxnModal')"
                class="flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-lg shadow-sm transition-all">
                <i class="bi bi-plus-lg"></i>
                + Post Manual Transaction
            </button>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Account Banner Card --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-slate-800 via-slate-900 to-teal-900 text-white rounded-2xl p-4 border border-slate-700 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <span class="px-2 py-0.5 bg-white/10 text-teal-300 rounded-full text-[8px] font-black uppercase tracking-widest">{{ $account->type }}</span>
                    <h2 class="text-base font-black tracking-tight mt-1">{{ $account->name }}</h2>
                    <p class="text-[9px] text-slate-300">Code: <span class="font-mono font-bold text-white">{{ $account->code }}</span></p>
                </div>
                <div class="text-right">
                    <p class="text-[8px] font-extrabold uppercase tracking-widest text-slate-400">Current Balance</p>
                    <p class="text-lg font-black text-amber-400 leading-tight mt-0.5">
                        {{ number_format($balance) }}
                        <span class="text-[9px] text-amber-300 font-bold">PKR</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Ledger Table --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-3 py-2 border-b border-slate-100 flex items-center justify-between bg-teal-50/50">
                <div class="flex items-center gap-2">
                    <i class="bi bi-journal-text text-teal-500 text-xs"></i>
                    <h3 class="text-[9px] font-extrabold text-teal-700 uppercase tracking-wider">Transaction Ledger</h3>
                </div>
                <span class="px-1.5 py-0.5 bg-teal-100 text-teal-700 rounded text-[8px] font-bold">
                    {{ $lines->count() }} entries
                </span>
            </div>

            @if ($lines->count() == 0)
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <i class="bi bi-journal-x text-3xl text-teal-200 mb-2"></i>
                    <h4 class="text-xs font-bold text-slate-600">No Transactions Found</h4>
                    <p class="text-[9px] text-slate-400 mt-1">Post a manual transaction or view related grant/expense ledgers.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 whitespace-nowrap">Date</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 whitespace-nowrap">Txn #</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400">Particulars / Description</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 whitespace-nowrap">Cheque #</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">Credit (Outflow)</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">Debit (Inflow)</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">Balance</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-center whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($lines->sortBy(fn($l) => $l->transaction ? $l->transaction->date : $l->created_at) as $line)
                                @php
                                    $txn = $line->transaction;
                                    $cheque = $txn ? $txn->cheque_no : null;
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-2 px-2.5 text-[9px] text-slate-500 font-medium whitespace-nowrap">
                                        {{ $txn && $txn->date ? \Carbon\Carbon::parse($txn->date)->format('d M Y') : $line->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-2 px-2.5 text-[9px] font-mono font-bold text-slate-600 whitespace-nowrap">
                                        #{{ $line->transaction_id }}
                                    </td>
                                    <td class="py-2 px-2.5 max-w-[220px]">
                                        <p class="text-[10px] font-semibold text-slate-800 truncate" title="{{ $txn ? $txn->description : 'Entry' }}">
                                            {{ $txn ? $txn->description : 'Entry' }}
                                        </p>
                                    </td>
                                    <td class="py-2 px-2.5 text-[9px] whitespace-nowrap">
                                        @if ($cheque)
                                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 font-mono rounded text-[8px] font-bold">Chq: {{ $cheque }}</span>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-2.5 text-right text-[9px] font-extrabold whitespace-nowrap {{ $line->credit > 0 ? 'text-rose-600' : 'text-slate-300' }}">
                                        {{ $line->credit > 0 ? number_format($line->credit) : '—' }}
                                    </td>
                                    <td class="py-2 px-2.5 text-right text-[9px] font-extrabold whitespace-nowrap {{ $line->debit > 0 ? 'text-emerald-600' : 'text-slate-300' }}">
                                        {{ $line->debit > 0 ? number_format($line->debit) : '—' }}
                                    </td>
                                    <td class="py-2 px-2.5 text-right text-[9px] font-black text-slate-800 whitespace-nowrap">
                                        {{ number_format($line->running_balance) }}
                                    </td>
                                    <td class="py-2 px-2.5 text-center whitespace-nowrap">
                                        @if ($txn)
                                            @php
                                                $contraLine = $txn->lines->firstWhere('account_id', '!=', $account->id);
                                                $contraId   = $contraLine ? $contraLine->account_id : '';
                                                $txnType    = $line->debit > 0 ? 'debit' : 'credit';
                                                $amount     = $line->debit > 0 ? $line->debit : $line->credit;
                                            @endphp
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button"
                                                    onclick="openEditTxnModal({{ json_encode([
                                                        'id' => $txn->id,
                                                        'date' => $txn->date ? \Carbon\Carbon::parse($txn->date)->format('Y-m-d') : $line->created_at->format('Y-m-d'),
                                                        'txn_type' => $txnType,
                                                        'amount' => $amount,
                                                        'contra_account_id' => $contraId,
                                                        'cheque_no' => $txn->cheque_no,
                                                        'description' => $txn->description
                                                    ]) }})"
                                                    class="text-slate-300 hover:text-teal-600 transition-colors" title="Edit Transaction">
                                                    <i class="bi bi-pencil-square text-[11px]"></i>
                                                </button>
                                                <form id="del-txn-{{ $txn->id }}" action="{{ route('accounts.transaction.destroy', $txn->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDel(event, 'del-txn-{{ $txn->id }}')"
                                                        class="text-slate-300 hover:text-red-500 transition-colors" title="Delete Transaction">
                                                        <i class="bi bi-trash text-[11px]"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-800 text-white">
                                <td colspan="4" class="py-2.5 px-2.5 text-[8px] font-extrabold uppercase tracking-widest text-slate-400">Total Lines</td>
                                <td class="py-2.5 px-2.5 text-right text-[9px] font-black text-rose-400">{{ number_format($lines->sum('credit')) }}</td>
                                <td class="py-2.5 px-2.5 text-right text-[9px] font-black text-emerald-400">{{ number_format($lines->sum('debit')) }}</td>
                                <td class="py-2.5 px-2.5 text-right text-[10px] font-black text-amber-400">{{ number_format($balance) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODAL: POST MANUAL TRANSACTION
    ══════════════════════════════════════════════════════ --}}
    <div id="addTxnModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="addTxnCard"
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            <div class="flex items-center justify-between px-5 py-3.5 bg-teal-50 border-b border-teal-100">
                <div>
                    <h3 class="text-xs font-black text-teal-800 uppercase tracking-wider">Post Manual Transaction</h3>
                    <p class="text-[9px] text-teal-500 mt-0.5">Post Debit/Credit entry for <span class="font-bold">{{ $account->name }}</span></p>
                </div>
                <button type="button" onclick="closeModal('addTxnModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-teal-100 text-teal-400 hover:text-teal-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form action="{{ route('accounts.transaction.store', $account->id) }}" method="POST" class="p-5 space-y-4">
                @csrf

                {{-- Date & Type --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Transaction Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400" required>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Transaction Type <span class="text-red-500">*</span></label>
                        <select name="txn_type" id="modal_txn_type" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400 bg-white" required>
                            <option value="debit">Debit (Deposit / Inflow)</option>
                            <option value="credit">Credit (Withdrawal / Outflow)</option>
                        </select>
                    </div>
                </div>

                {{-- Amount & Contra Account --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Amount (PKR) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" min="1" placeholder="e.g. 25000" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400" required>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Counterpart Account <span class="text-red-500">*</span></label>
                        <select name="contra_account_id" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400 bg-white" required>
                            <option value="">— Select Account —</option>
                            @foreach ($otherAccounts as $oAcc)
                                <option value="{{ $oAcc->id }}">{{ $oAcc->name }} ({{ $oAcc->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Cheque Number (Required for Bank Credit/Withdrawals) --}}
                <div id="modal_cheque_container">
                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Cheque Number <span id="modal_cheque_req" class="text-red-500">*</span>
                    </label>
                    <input type="text" name="cheque_no" id="modal_cheque_no" placeholder="e.g. CHQ-8849102"
                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400">
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Description / Notes</label>
                    <input type="text" name="description" placeholder="e.g. Cheque withdrawal for office supplies"
                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('addTxnModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Post Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- EDIT TRANSACTION MODAL --}}
    <div id="editTxnModal" class="fixed inset-0 z-[9999] hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="editTxnCard" class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">
            <div class="flex items-center justify-between px-5 py-3.5 bg-slate-800 text-white">
                <div class="flex items-center gap-2">
                    <i class="bi bi-pencil-square text-teal-400"></i>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider">Edit Transaction</h3>
                </div>
                <button type="button" onclick="closeModal('editTxnModal', 'editTxnCard')" class="text-slate-400 hover:text-white transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form id="editTxnForm" action="" method="POST" class="p-5 space-y-4">
                @csrf @method('PUT')
                <input type="hidden" name="account_id" value="{{ $account->id }}">

                {{-- Date --}}
                <div>
                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Date</label>
                    <input type="date" name="date" id="edit_date" required
                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400">
                </div>

                {{-- Transaction Type --}}
                <div>
                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Transaction Type</label>
                    <select name="txn_type" id="edit_txn_type" required
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400">
                        <option value="debit">Deposit / Inflow (Debit)</option>
                        <option value="credit">Withdrawal / Outflow (Credit)</option>
                    </select>
                </div>

                {{-- Amount & Contra Account --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Amount (PKR)</label>
                        <input type="number" name="amount" id="edit_amount" min="1" step="any" required
                               class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Contra Account</label>
                        <select name="contra_account_id" id="edit_contra_account_id" required
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400">
                            @foreach ($otherAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Cheque Number --}}
                <div id="edit_cheque_container">
                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Cheque Number
                    </label>
                    <input type="text" name="cheque_no" id="edit_cheque_no" placeholder="e.g. CHQ-8849102"
                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400">
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Description / Notes</label>
                    <input type="text" name="description" id="edit_description" placeholder="e.g. Cheque withdrawal"
                           class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('editTxnModal', 'editTxnCard')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Update Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    function openModal(modalId = 'addTxnModal', cardId = 'addTxnCard') {
        const modal = document.getElementById(modalId);
        const card  = document.getElementById(cardId);
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeModal(modalId = 'addTxnModal', cardId = 'addTxnCard') {
        const modal = document.getElementById(modalId);
        const card  = document.getElementById(cardId);
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    function openEditTxnModal(txn) {
        const form = document.getElementById('editTxnForm');
        form.action = `/accounts/transactions/${txn.id}`;
        
        document.getElementById('edit_date').value = txn.date;
        document.getElementById('edit_txn_type').value = txn.txn_type;
        document.getElementById('edit_amount').value = txn.amount;
        document.getElementById('edit_contra_account_id').value = txn.contra_account_id;
        document.getElementById('edit_cheque_no').value = txn.cheque_no || '';
        document.getElementById('edit_description').value = txn.description || '';

        openModal('editTxnModal', 'editTxnCard');
    }

    function confirmDel(event, formId) {
        event.preventDefault();
        Swal.fire({
            title: 'Delete this transaction entry?',
            text: 'This action cannot be undone.',
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
