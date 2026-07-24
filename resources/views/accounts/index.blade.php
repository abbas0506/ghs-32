@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-16 md:pb-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
            <div class="flex items-center gap-2">
                <a href="{{ route('finance.index') }}"
                   class="w-7 h-7 flex items-center justify-center bg-teal-50 hover:bg-teal-100 text-teal-600 rounded-lg transition-colors"
                   title="Back to Finance">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Accounting</p>
                    <h1 class="text-sm font-extrabold text-slate-800 leading-tight">Bank & General Accounts</h1>
                </div>
            </div>
            <button type="button" onclick="openModal('addAccountModal')"
                class="flex items-center gap-1 px-3 py-1.5 text-[10px] font-bold bg-teal-600 hover:bg-teal-700 text-white rounded-lg shadow-sm transition-all">
                <i class="bi bi-plus-lg"></i>
                + New Account
            </button>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Bank Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            {{-- FTF Bank Account Card --}}
            <div class="bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 text-white rounded-2xl p-4 shadow-md relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <span class="px-2 py-0.5 bg-white/15 border border-white/20 rounded-full text-[8px] font-extrabold uppercase tracking-widest text-emerald-100">Bank Account</span>
                        <h2 class="text-base font-black tracking-tight mt-1.5">FTF Bank Account</h2>
                        <p class="text-[9px] text-emerald-100/70">Code: 1002 • Farogh-e-Taleem Fund</p>
                    </div>
                    @if ($ftfAccount)
                        <a href="{{ route('accounts.show', $ftfAccount->id) }}"
                           class="px-2.5 py-1 text-[9px] font-extrabold bg-white text-emerald-700 hover:bg-emerald-50 rounded-lg shadow transition">
                            View Ledger <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>
                <div class="mt-4 pt-3 border-t border-white/15 grid grid-cols-3 gap-2 text-center">
                    <div>
                        <p class="text-[7px] font-bold uppercase tracking-wider text-emerald-200/70">Total Deposits</p>
                        <p class="text-xs font-black text-white mt-0.5">{{ number_format($ftfDeposits) }}</p>
                    </div>
                    <div>
                        <p class="text-[7px] font-bold uppercase tracking-wider text-emerald-200/70">Total Withdrawals</p>
                        <p class="text-xs font-black text-rose-200 mt-0.5">{{ number_format($ftfWithdrawals) }}</p>
                    </div>
                    <div>
                        <p class="text-[7px] font-bold uppercase tracking-wider text-white">Live Balance</p>
                        <p class="text-xs font-black text-emerald-100 mt-0.5">{{ number_format($ftfBalance) }} PKR</p>
                    </div>
                </div>
            </div>

            {{-- SMC Bank Account Card --}}
            <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-slate-800 text-white rounded-2xl p-4 shadow-md relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="flex items-start justify-between">
                    <div>
                        <span class="px-2 py-0.5 bg-white/15 border border-white/20 rounded-full text-[8px] font-extrabold uppercase tracking-widest text-blue-100">Bank Account</span>
                        <h2 class="text-base font-black tracking-tight mt-1.5">SMC Bank Account</h2>
                        <p class="text-[9px] text-blue-100/70">Code: 1007 • School Management Committee (Grants)</p>
                    </div>
                    @if ($smcAccount)
                        <a href="{{ route('accounts.show', $smcAccount->id) }}"
                           class="px-2.5 py-1 text-[9px] font-extrabold bg-white text-blue-700 hover:bg-blue-50 rounded-lg shadow transition">
                            View Ledger <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>
                <div class="mt-4 pt-3 border-t border-white/15 grid grid-cols-3 gap-2 text-center">
                    <div>
                        <p class="text-[7px] font-bold uppercase tracking-wider text-blue-200/70">Grant Receipts</p>
                        <p class="text-xs font-black text-emerald-200 mt-0.5">{{ number_format($smcReceipts) }}</p>
                    </div>
                    <div>
                        <p class="text-[7px] font-bold uppercase tracking-wider text-blue-200/70">Grant Expenses</p>
                        <p class="text-xs font-black text-rose-200 mt-0.5">{{ number_format($smcExpenses) }}</p>
                    </div>
                    <div>
                        <p class="text-[7px] font-bold uppercase tracking-wider text-white">Live Balance</p>
                        <p class="text-xs font-black text-blue-100 mt-0.5">{{ number_format($smcBalance) }} PKR</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accounts Categorized Table --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden space-y-4 p-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <div class="flex items-center gap-2">
                    <i class="bi bi-diagram-3 text-teal-600 text-sm"></i>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Chart of Accounts</h3>
                </div>
            </div>

            @foreach (['asset' => 'Assets', 'liability' => 'Liabilities', 'equity' => 'Equity', 'income' => 'Income', 'expense' => 'Expenses'] as $typeKey => $typeName)
                @if (isset($accounts[$typeKey]) && $accounts[$typeKey]->count() > 0)
                    <div class="border border-slate-100 rounded-xl overflow-hidden">
                        <div class="px-3 py-1.5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-[9px] font-black uppercase tracking-wider text-slate-600">{{ $typeName }}</span>
                            <span class="text-[8px] font-bold px-2 py-0.5 bg-slate-200/60 rounded text-slate-600">{{ $accounts[$typeKey]->count() }} accounts</span>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @foreach ($accounts[$typeKey] as $acc)
                                <div class="px-3 py-2 flex items-center justify-between hover:bg-slate-50/50 transition">
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[9px] font-mono font-bold">{{ $acc->code }}</span>
                                        <div>
                                            <a href="{{ route('accounts.show', $acc->id) }}" class="text-xs font-bold text-slate-800 hover:text-teal-600 transition">
                                                {{ $acc->name }}
                                            </a>
                                            @if ($acc->is_payment_method)
                                                <span class="ml-1.5 px-1.5 py-0.5 bg-emerald-50 text-emerald-600 text-[7px] font-bold uppercase rounded">Payment Method</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-xs font-black text-slate-800">
                                            {{ number_format($acc->balance()) }} <span class="text-[8px] text-slate-400 font-normal">PKR</span>
                                        </span>
                                        <a href="{{ route('accounts.show', $acc->id) }}" class="text-teal-600 hover:text-teal-700 text-[10px] font-bold flex items-center gap-1">
                                            Ledger <i class="bi bi-chevron-right text-[9px]"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODAL: CREATE ACCOUNT
    ══════════════════════════════════════════════════════ --}}
    <div id="addAccountModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="addAccountCard"
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            <div class="flex items-center justify-between px-5 py-3.5 bg-teal-50 border-b border-teal-100">
                <div>
                    <h3 class="text-xs font-black text-teal-800 uppercase tracking-wider">Create New Account</h3>
                    <p class="text-[9px] text-teal-500 mt-0.5">Add an account to the Chart of Accounts</p>
                </div>
                <button type="button" onclick="closeModal('addAccountModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-teal-100 text-teal-400 hover:text-teal-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form action="{{ route('accounts.store') }}" method="POST" class="p-5 space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" placeholder="e.g. 1008" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400" required>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Type <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400 bg-white" required>
                            <option value="asset">Asset</option>
                            <option value="liability">Liability</option>
                            <option value="equity">Equity</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" placeholder="e.g. HBL Bank Account" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-teal-400" required>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_payment_method" value="1" id="modal_is_pm" class="rounded text-teal-600 focus:ring-teal-400">
                    <label for="modal_is_pm" class="text-xs font-semibold text-slate-700">Allow as Payment Method (for Expenses/Payments)</label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeModal('addAccountModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Save Account
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        const card  = document.getElementById('addAccountCard');
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const card  = document.getElementById('addAccountCard');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
</script>
@endsection
