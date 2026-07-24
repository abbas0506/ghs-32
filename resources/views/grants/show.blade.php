@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-16 md:pb-6">

        {{-- Compute totals --}}
        @php
            $totalReceived = $installments->sum('amount');
            $totalGross    = $grant->expenses()->sum('amount');
            $totalNetPaid  = $grant->expenses()->sum('net_amount');
            $totalGst      = $grant->expenses()->sum('gst_amount');
            $totalPst      = $grant->expenses()->sum('pst_amount');
            $totalIt       = $grant->expenses()->sum('it_amount');
        @endphp

        {{-- Page Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <a href="{{ route('finance.index') }}"
                   class="shrink-0 w-7 h-7 flex items-center justify-center bg-teal-50 hover:bg-teal-100 text-teal-600 rounded-lg transition-colors"
                   title="Back to Finance">
                    <i class="bi bi-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">Grant Ledger</p>
                    <h1 class="text-sm font-extrabold text-slate-800 leading-tight truncate">{{ $grant->title }}</h1>
                </div>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <a href="{{ route('grants.pdf', $grant->id) }}" target="_blank"
                    class="flex items-center gap-1 px-2 py-1.5 sm:px-3 text-[10px] font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg shadow-sm transition-all"
                    title="Export PDF Statement">
                    <i class="bi bi-file-earmark-pdf-fill text-[12px]"></i>
                    <span class="hidden sm:inline">Export PDF</span>
                </a>
                {{-- Icon-only on mobile, icon+text on sm+ --}}
                <button type="button" onclick="openModal('addInstallmentModal')"
                    class="flex items-center gap-1 px-2 py-1.5 sm:px-3 text-[10px] font-bold bg-white hover:bg-teal-50 text-teal-700 border border-teal-200 rounded-lg shadow-sm transition-all"
                    title="Add Receipt">
                    <i class="bi bi-arrow-down-circle text-[12px]"></i>
                    <span class="hidden sm:inline">Add Receipt</span>
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

        {{-- Grant Info Banner --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-teal-500 via-teal-600 to-emerald-700 text-white rounded-2xl border border-teal-400/20 shadow-lg">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-6 -bottom-6 w-24 h-24 bg-teal-400/20 rounded-full blur-xl pointer-events-none"></div>

            <div class="relative z-10 px-5 py-3.5">
                {{-- Row 1: Opening Balance --}}
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[8px] font-extrabold uppercase tracking-widest text-teal-200/60">Opening Balance</span>
                    <span class="text-xs font-black text-white">{{ number_format($openingBalance ?? 0) }} <span class="text-[8px] text-teal-300 font-semibold">PKR</span></span>
                </div>

                {{-- Divider --}}
                <div class="border-t border-white/15 mb-2.5"></div>

                {{-- Row 2: Received | Spent | Balance --}}
                <div class="flex items-center justify-between text-center">
                    <div class="flex-1">
                        <p class="text-[7px] font-extrabold uppercase tracking-wider text-teal-200/60 leading-none">Received</p>
                        <p class="text-sm font-black text-emerald-200 leading-tight mt-1">{{ number_format($totalReceived) }}</p>
                        <p class="text-[7px] text-teal-300/70 mt-0.5">PKR</p>
                    </div>
                    <span class="text-white/20 text-base mx-2">|</span>
                    <div class="flex-1">
                        <p class="text-[7px] font-extrabold uppercase tracking-wider text-teal-200/60 leading-none">Spent</p>
                        <p class="text-sm font-black text-rose-200 leading-tight mt-1">{{ number_format($totalGross) }}</p>
                        <p class="text-[7px] text-teal-300/70 mt-0.5">PKR</p>
                    </div>
                    <span class="text-white/20 text-base mx-2">|</span>
                    <div class="flex-1">
                        <p class="text-[7px] font-extrabold uppercase tracking-wider text-white/60 leading-none">Balance</p>
                        <p class="text-sm font-black text-white leading-tight mt-1">{{ number_format($balance) }}</p>
                        <p class="text-[7px] text-teal-200/70 mt-0.5">PKR</p>
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
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <i class="bi bi-journal-x text-3xl text-teal-200 mb-2"></i>
                    <h4 class="text-xs font-bold text-slate-600">Ledger is Empty</h4>
                    <p class="text-[9px] text-slate-400 mt-1">Use the buttons above to add a receipt or expense.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 whitespace-nowrap">Date</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 whitespace-nowrap">Receipt #</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400">Particulars</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">Net Paid</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">GST</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">PST</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">IT</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">Outflow</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">Inflow</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-right whitespace-nowrap">Balance</th>
                                <th class="py-2 px-2.5 text-[8px] font-extrabold uppercase tracking-wider text-slate-400 text-center whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">

                            {{-- Opening Balance Row --}}
                            <tr class="bg-teal-50/60 border-b border-teal-100/60">
                                <td colspan="3" class="py-2 px-2.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 bg-teal-400 rounded-full"></span>
                                        <span class="text-[9px] font-extrabold text-teal-600 uppercase tracking-wider">Opening Balance</span>
                                    </div>
                                </td>
                                <td class="py-2 px-2.5 text-right text-[9px] text-teal-300">—</td>
                                <td class="py-2 px-2.5 text-right text-[9px] text-teal-300">—</td>
                                <td class="py-2 px-2.5 text-right text-[9px] text-teal-300">—</td>
                                <td class="py-2 px-2.5 text-right text-[9px] text-teal-300">—</td>
                                <td class="py-2 px-2.5 text-right text-[9px] text-teal-300">—</td>
                                <td class="py-2 px-2.5 text-right text-[9px] text-teal-300">—</td>
                                <td class="py-2 px-2.5 text-right text-[10px] font-black text-teal-700">0</td>
                                <td class="py-2 px-2.5"></td>
                            </tr>

                            {{-- Chronological ledger rows (ascending) --}}
                            @foreach ($ledger->sortBy('date') as $item)
                                <tr class="hover:bg-slate-50/60 transition-colors duration-100">

                                    {{-- Date --}}
                                    <td class="py-2 px-2.5 text-[9px] text-slate-500 font-medium whitespace-nowrap">
                                        {{ $item->date->format('d M Y') }}
                                    </td>

                                    {{-- Receipt # --}}
                                    <td class="py-2 px-2.5 whitespace-nowrap">
                                        @if ($item->type === 'receipt')
                                            <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[8px] font-bold">RCPT</span>
                                        @else
                                            <span class="text-[9px] font-semibold text-slate-600 uppercase">{{ $item->receipt_no ?? '—' }}</span>
                                        @endif
                                    </td>

                                    {{-- Particulars --}}
                                    <td class="py-2 px-2.5 max-w-[180px]">
                                        <p class="text-[10px] font-semibold text-slate-800 truncate" title="{{ $item->description }}">
                                            {{ $item->description }}
                                        </p>
                                        @if ($item->type === 'expense' && !empty($item->expense_type))
                                            <span class="inline-block px-1 py-0.5 rounded text-[7px] font-extrabold uppercase mt-0.5
                                                @switch($item->expense_type)
                                                    @case('purchase') bg-blue-50 text-blue-500 @break
                                                    @case('service')  bg-violet-50 text-violet-500 @break
                                                    @case('utility')  bg-orange-50 text-orange-500 @break
                                                    @default          bg-slate-100 text-slate-500
                                                @endswitch
                                            ">{{ $item->expense_type }}</span>
                                        @endif
                                    </td>

                                    {{-- Net Paid --}}
                                    <td class="py-2 px-2.5 text-right text-[9px] font-semibold whitespace-nowrap
                                        {{ $item->type === 'expense' ? 'text-slate-600' : 'text-slate-300' }}">
                                        {{ $item->type === 'expense' ? number_format($item->net_amount) : '—' }}
                                    </td>

                                    {{-- GST --}}
                                    <td class="py-2 px-2.5 text-right text-[9px] whitespace-nowrap
                                        {{ ($item->type === 'expense' && $item->gst_amount > 0) ? 'text-slate-500' : 'text-slate-200' }}">
                                        {{ ($item->type === 'expense' && $item->gst_amount > 0) ? number_format($item->gst_amount) : '—' }}
                                    </td>

                                    {{-- PST --}}
                                    <td class="py-2 px-2.5 text-right text-[9px] whitespace-nowrap
                                        {{ ($item->type === 'expense' && $item->pst_amount > 0) ? 'text-slate-500' : 'text-slate-200' }}">
                                        {{ ($item->type === 'expense' && $item->pst_amount > 0) ? number_format($item->pst_amount) : '—' }}
                                    </td>

                                    {{-- IT --}}
                                    <td class="py-2 px-2.5 text-right text-[9px] whitespace-nowrap
                                        {{ ($item->type === 'expense' && $item->it_amount > 0) ? 'text-slate-500' : 'text-slate-200' }}">
                                        {{ ($item->type === 'expense' && $item->it_amount > 0) ? number_format($item->it_amount) : '—' }}
                                    </td>

                                    {{-- Outflow (Gross) --}}
                                    <td class="py-2 px-2.5 text-right text-[9px] font-extrabold whitespace-nowrap
                                        {{ $item->type === 'expense' ? 'text-rose-600' : 'text-slate-200' }}">
                                        {{ $item->type === 'expense' ? number_format($item->amount) : '—' }}
                                    </td>

                                    {{-- Inflow --}}
                                    <td class="py-2 px-2.5 text-right text-[9px] font-extrabold whitespace-nowrap
                                        {{ $item->type === 'receipt' ? 'text-emerald-600' : 'text-slate-200' }}">
                                        {{ $item->type === 'receipt' ? number_format($item->amount) : '—' }}
                                    </td>

                                    {{-- Running Balance --}}
                                    <td class="py-2 px-2.5 text-right text-[9px] font-black text-slate-800 whitespace-nowrap">
                                        {{ number_format($item->running_balance) }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="py-2 px-2.5 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if ($item->type === 'receipt')
                                                <a href="{{ route('grants.installments.edit', [$grant->id, $item->id]) }}"
                                                    class="text-slate-300 hover:text-teal-600 transition-colors" title="Edit">
                                                    <i class="bi bi-pencil-square text-[11px]"></i>
                                                </a>
                                                <form id="del-item-{{ $item->id }}"
                                                    action="{{ route('grants.installments.destroy', [$grant->id, $item->id]) }}"
                                                    method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDel(event, 'del-item-{{ $item->id }}')"
                                                        class="text-slate-300 hover:text-red-500 transition-colors" title="Delete">
                                                        <i class="bi bi-trash text-[11px]"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('expenses.edit', $item->id) }}?redirect_to={{ urlencode(request()->fullUrl()) }}"
                                                    class="text-slate-300 hover:text-teal-600 transition-colors" title="Edit">
                                                    <i class="bi bi-pencil-square text-[11px]"></i>
                                                </a>
                                                <form id="del-exp-{{ $item->id }}"
                                                    action="{{ route('expenses.destroy', $item->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDel(event, 'del-exp-{{ $item->id }}')"
                                                        class="text-slate-300 hover:text-red-500 transition-colors" title="Delete">
                                                        <i class="bi bi-trash text-[11px]"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        {{-- Totals Footer --}}
                        <tfoot>
                            <tr class="bg-slate-800 border-t-2 border-slate-600">
                                <td colspan="3" class="py-2.5 px-2.5">
                                    <div class="flex items-center gap-1.5">
                                        <i class="bi bi-calculator text-teal-400 text-[10px]"></i>
                                        <span class="text-[8px] font-extrabold uppercase tracking-widest text-slate-400">Totals</span>
                                    </div>
                                </td>
                                {{-- Net Paid total --}}
                                <td class="py-2.5 px-2.5 text-right text-[9px] font-black text-slate-300">
                                    {{ number_format($totalNetPaid) }}
                                </td>
                                {{-- GST total --}}
                                <td class="py-2.5 px-2.5 text-right text-[9px] font-bold text-slate-400">
                                    {{ $totalGst > 0 ? number_format($totalGst) : '—' }}
                                </td>
                                {{-- PST total --}}
                                <td class="py-2.5 px-2.5 text-right text-[9px] font-bold text-slate-400">
                                    {{ $totalPst > 0 ? number_format($totalPst) : '—' }}
                                </td>
                                {{-- IT total --}}
                                <td class="py-2.5 px-2.5 text-right text-[9px] font-bold text-slate-400">
                                    {{ $totalIt > 0 ? number_format($totalIt) : '—' }}
                                </td>
                                {{-- Outflow total --}}
                                <td class="py-2.5 px-2.5 text-right text-[9px] font-black text-rose-400">
                                    {{ number_format($totalGross) }}
                                </td>
                                {{-- Inflow total --}}
                                <td class="py-2.5 px-2.5 text-right text-[9px] font-black text-emerald-400">
                                    {{ number_format($totalReceived) }}
                                </td>
                                {{-- Net Balance --}}
                                <td class="py-2.5 px-2.5 text-right text-[10px] font-black text-amber-400">
                                    {{ number_format($balance) }}
                                </td>
                                <td class="py-2.5 px-2.5"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODAL A: ADD RECEIPT (INSTALLMENT)
    ══════════════════════════════════════════════════════ --}}
    <div id="addInstallmentModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="addInstallmentCard"
            class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 bg-teal-50 border-b border-teal-100">
                <div>
                    <h3 class="text-xs font-black text-teal-800 uppercase tracking-wider">Add Receipt</h3>
                    <p class="text-[9px] text-teal-500 mt-0.5">Record funds received for <span class="font-bold">{{ $grant->title }}</span></p>
                </div>
                <button type="button" onclick="closeModal('addInstallmentModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-teal-100 text-teal-400 hover:text-teal-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form action="{{ route('grants.installments.store', $grant->id) }}" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="form_type" value="grant_installment">

                {{-- Amount --}}
                <div>
                    <label for="modal_inst_amount" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Amount Received (PKR) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" id="modal_inst_amount" min="1" placeholder="e.g. 50000"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Date --}}
                <div>
                    <label for="modal_inst_date" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Received Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="received_date" id="modal_inst_date" value="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition" required>
                </div>

                {{-- Cheque No --}}
                <div>
                    <label for="modal_inst_cheque" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Cheque / Ref Number
                    </label>
                    <input type="text" name="cheque_no" id="modal_inst_cheque" placeholder="e.g. CHQ-991823"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition">
                </div>

                {{-- Description --}}
                <div>
                    <label for="modal_inst_desc" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                        Description / Notes
                    </label>
                    <textarea name="description" id="modal_inst_desc" rows="2"
                        placeholder="e.g. First quarter NSB installment..."
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeModal('addInstallmentModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Save Receipt
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODAL B: ADD EXPENSE
    ══════════════════════════════════════════════════════ --}}
    <div id="addExpenseModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="addExpenseCard"
            class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-100 transform transition-all duration-200 scale-95 opacity-0 max-h-[92vh] flex flex-col">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 bg-teal-50 border-b border-teal-100 shrink-0">
                <div>
                    <h3 class="text-xs font-black text-teal-800 uppercase tracking-wider">Add Expense</h3>
                    <p class="text-[9px] text-teal-500 mt-0.5">Record an outflow against <span class="font-bold">{{ $grant->title }}</span></p>
                </div>
                <button type="button" onclick="closeModal('addExpenseModal')"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-teal-100 text-teal-400 hover:text-teal-700 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form action="{{ route('expenses.store') }}" method="POST" class="overflow-y-auto flex-1 p-5 space-y-4">
                @csrf
                <input type="hidden" name="form_type"   value="grant_expense">
                <input type="hidden" name="fund_type"   value="grant">
                <input type="hidden" name="grant_id"    value="{{ $grant->id }}">
                <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">

                {{-- Expense Type — full width at top --}}
                <div>
                    <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Expense Type <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        <label id="card_expense_type_purchase" class="relative flex flex-col items-center gap-1 p-2.5 border-2 rounded-xl cursor-pointer transition-all duration-200 text-center expense-type-card border-slate-200 bg-white text-slate-500 hover:border-teal-300">
                            <input type="radio" name="expense_type" value="purchase" class="sr-only" {{ old('expense_type', 'purchase') == 'purchase' ? 'checked' : '' }} onchange="updateExpenseTypeSelection()">
                            <i class="bi bi-bag text-base text-blue-500"></i>
                            <span class="text-[9px] font-black text-slate-700">Purchase</span>
                            <span class="check-badge hidden absolute top-1 right-1 text-teal-600 text-[10px]"><i class="bi bi-check-circle-fill"></i></span>
                        </label>
                        <label id="card_expense_type_service" class="relative flex flex-col items-center gap-1 p-2.5 border-2 rounded-xl cursor-pointer transition-all duration-200 text-center expense-type-card border-slate-200 bg-white text-slate-500 hover:border-teal-300">
                            <input type="radio" name="expense_type" value="service" class="sr-only" {{ old('expense_type') == 'service' ? 'checked' : '' }} onchange="updateExpenseTypeSelection()">
                            <i class="bi bi-tools text-base text-violet-500"></i>
                            <span class="text-[9px] font-black text-slate-700">Service</span>
                            <span class="check-badge hidden absolute top-1 right-1 text-teal-600 text-[10px]"><i class="bi bi-check-circle-fill"></i></span>
                        </label>
                        <label id="card_expense_type_utility" class="relative flex flex-col items-center gap-1 p-2.5 border-2 rounded-xl cursor-pointer transition-all duration-200 text-center expense-type-card border-slate-200 bg-white text-slate-500 hover:border-teal-300">
                            <input type="radio" name="expense_type" value="utility" class="sr-only" {{ old('expense_type') == 'utility' ? 'checked' : '' }} onchange="updateExpenseTypeSelection()">
                            <i class="bi bi-lightning text-base text-orange-500"></i>
                            <span class="text-[9px] font-black text-slate-700">Utility</span>
                            <span class="check-badge hidden absolute top-1 right-1 text-teal-600 text-[10px]"><i class="bi bi-check-circle-fill"></i></span>
                        </label>
                        <label id="card_expense_type_other" class="relative flex flex-col items-center gap-1 p-2.5 border-2 rounded-xl cursor-pointer transition-all duration-200 text-center expense-type-card border-slate-200 bg-white text-slate-500 hover:border-teal-300">
                            <input type="radio" name="expense_type" value="other" class="sr-only" {{ old('expense_type') == 'other' ? 'checked' : '' }} onchange="updateExpenseTypeSelection()">
                            <i class="bi bi-three-dots text-base text-slate-400"></i>
                            <span class="text-[9px] font-black text-slate-700">Other</span>
                            <span class="check-badge hidden absolute top-1 right-1 text-teal-600 text-[10px]"><i class="bi bi-check-circle-fill"></i></span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- ── LEFT COLUMN ── --}}
                    <div class="space-y-3">

                        {{-- Expense Category --}}
                        <div>
                            <label for="modal_expense_account_id" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                Expense Category <span class="text-red-500">*</span>
                            </label>
                            <select name="expense_account_id" id="modal_expense_account_id"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none bg-white" required>
                                <option value="">— Select Category —</option>
                                @foreach ($expenseAccounts as $acc)
                                    <option value="{{ $acc->id }}" {{ old('expense_account_id') == $acc->id ? 'selected' : '' }}>
                                        {{ $acc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Payment Method --}}
                        <div>
                            <label for="modal_payment_account_id" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                Payment Method <span class="text-red-500">*</span>
                            </label>
                            <select name="payment_account_id" id="modal_payment_account_id"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none bg-white" required>
                                <option value="">— Select Method —</option>
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}"
                                        {{ (old('payment_account_id') == $method->id || $method->code == '1001') ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>



                        {{-- Description --}}
                        <div>
                            <label for="modal_description" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                Description / Detail
                            </label>
                            <input type="text" id="modal_description" name="description" value="{{ old('description', '') }}"
                                placeholder="e.g. Purchased program banners"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition">
                        </div>

                        {{-- Net Amount --}}
                        <div>
                            <label for="modal_amount" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                Net Amount Paid (PKR) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="modal_amount" name="amount" value="{{ old('amount', '') }}" min="1"
                                placeholder="e.g. 7650"
                                class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition" required>
                        </div>

                        {{-- Receipt Number & Cheque Number --}}
                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label for="modal_receipt_no" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                    Receipt Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="modal_receipt_no" name="receipt_no" value="{{ old('receipt_no', '') }}"
                                    placeholder="e.g. REC-994"
                                    class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition" required>
                            </div>
                            <div>
                                <label for="modal_cheque_no_exp" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                    Cheque Number
                                </label>
                                <input type="text" id="modal_cheque_no_exp" name="cheque_no" value="{{ old('cheque_no', '') }}"
                                    placeholder="e.g. CHQ-44012"
                                    class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition">
                            </div>
                        </div>
                    </div>

                    {{-- ── RIGHT COLUMN ── --}}
                    <div class="space-y-4">

                        {{-- Withholding Tax Config --}}
                        <div class="bg-slate-50 rounded-xl border border-slate-100 p-4 space-y-3">
                            <h4 class="text-[9px] font-extrabold text-slate-600 uppercase tracking-wider border-b border-slate-100 pb-1.5">
                                Withholding Tax
                            </h4>

                            {{-- Tax Type Selector --}}
                            <div class="grid grid-cols-3 gap-2">
                                <label class="flex flex-col items-center p-2.5 border border-slate-200 rounded-lg cursor-pointer hover:bg-white transition text-center" id="modal_label_tax_none">
                                    <input type="radio" name="tax_type" value="none" class="sr-only" checked>
                                    <span class="text-[10px] font-bold text-slate-700">No Tax</span>
                                    <span class="text-[7px] text-slate-400 mt-0.5">0%</span>
                                </label>
                                <label class="flex flex-col items-center p-2.5 border border-slate-200 rounded-lg cursor-pointer hover:bg-white transition text-center" id="modal_label_tax_purchase">
                                    <input type="radio" name="tax_type" value="purchase" class="sr-only">
                                    <span class="text-[10px] font-bold text-slate-700">Purchase</span>
                                    <span class="text-[7px] text-slate-400 mt-0.5">GST+IT</span>
                                </label>
                                <label class="flex flex-col items-center p-2.5 border border-slate-200 rounded-lg cursor-pointer hover:bg-white transition text-center" id="modal_label_tax_service">
                                    <input type="radio" name="tax_type" value="service" class="sr-only">
                                    <span class="text-[10px] font-bold text-slate-700">Service</span>
                                    <span class="text-[7px] text-slate-400 mt-0.5">PST+IT</span>
                                </label>
                            </div>

                            {{-- Rate Inputs: GST/PST | IT in same row --}}
                            <div class="grid grid-cols-2 gap-2.5">
                                <div id="modal_gst_rate_container">
                                    <label class="block text-[8px] font-bold text-slate-400 uppercase mb-1">GST Rate (%)</label>
                                    <input type="number" step="0.01" id="modal_gst_rate" name="gst_rate" value="19.00" min="0" max="100"
                                        class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none bg-white">
                                </div>
                                <div id="modal_pst_rate_container">
                                    <label class="block text-[8px] font-bold text-slate-400 uppercase mb-1">PST Rate (%)</label>
                                    <input type="number" step="0.01" id="modal_pst_rate" name="pst_rate" value="20.00" min="0" max="100"
                                        class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none bg-white">
                                </div>
                                <div id="modal_it_rate_container">
                                    <label class="block text-[8px] font-bold text-slate-400 uppercase mb-1">Income Tax (%)</label>
                                    <input type="number" step="0.01" id="modal_it_rate" name="it_rate" value="11.00" min="0" max="100"
                                        class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none bg-white">
                                </div>
                            </div>
                        </div>

                        {{-- Cashbook Preview Card --}}
                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-xl p-4 shadow-lg">
                            <div class="flex items-center justify-between border-b border-white/10 pb-2 mb-3">
                                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Cashbook Preview</span>
                                <span class="px-2 py-0.5 bg-teal-500/80 text-white rounded text-[7px] font-bold uppercase">Grant</span>
                            </div>

                            <div class="space-y-1.5 text-[10px]">
                                <div class="flex items-center justify-between text-slate-300">
                                    <span>Net Paid:</span>
                                    <span class="font-bold text-white"><span id="modal_preview_net">0</span> PKR</span>
                                </div>

                                <div id="modal_taxes_preview" class="space-y-1 pl-2.5 border-l border-white/10">
                                    <div class="flex justify-between text-slate-400" id="modal_preview_gst_row">
                                        <span>GST (<span id="modal_preview_gst_pct">18</span>%):</span>
                                        <span>+ <span id="modal_preview_gst_val">0</span> PKR</span>
                                    </div>
                                    <div class="flex justify-between text-slate-400" id="modal_preview_pst_row">
                                        <span>PST (<span id="modal_preview_pst_pct">20</span>%):</span>
                                        <span>+ <span id="modal_preview_pst_val">0</span> PKR</span>
                                    </div>
                                    <div class="flex justify-between text-slate-400" id="modal_preview_it_row">
                                        <span>IT (<span id="modal_preview_it_pct">5.5</span>%):</span>
                                        <span>+ <span id="modal_preview_it_val">0</span> PKR</span>
                                    </div>
                                    <div class="flex justify-between text-emerald-300 border-t border-white/5 pt-1" id="modal_preview_total_tax_row">
                                        <span class="font-semibold">Total Taxes:</span>
                                        <span class="font-bold">+ <span id="modal_preview_total_tax">0</span> PKR</span>
                                    </div>
                                </div>

                                <div class="flex items-baseline justify-between pt-2 border-t border-white/10">
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Gross Bill:</span>
                                    <span class="text-sm font-black text-amber-400">
                                        <span id="modal_preview_gross">0</span>
                                        <span class="text-[8px] text-amber-500"> PKR</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal('addExpenseModal')"
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                        Save Expense
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
        const card  = document.getElementById(id === 'addExpenseModal' ? 'addExpenseCard' : 'addInstallmentCard');
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const card  = document.getElementById(id === 'addExpenseModal' ? 'addExpenseCard' : 'addInstallmentCard');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    /* Close modal on backdrop click */
    ['addInstallmentModal', 'addExpenseModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function (e) {
            if (e.target === this) closeModal(id);
        });
    });

    /* ─────────── Delete confirmation ─────────── */
    function confirmDel(event, formId) {
        event.preventDefault();
        Swal.fire({
            title: 'Delete this entry?',
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

    /* ─────────── Re-open modal on validation errors ─────────── */
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any() && old('form_type') === 'grant_expense')
            openModal('addExpenseModal');
        @elseif ($errors->any() && old('form_type') === 'grant_installment')
            openModal('addInstallmentModal');
        @endif

        /* ─────────── Tax / Amount Calculator ─────────── */
        const amountInput       = document.getElementById('modal_amount');
        const taxTypeInputs     = document.getElementsByName('tax_type');
        const gstRateContainer  = document.getElementById('modal_gst_rate_container');
        const pstRateContainer  = document.getElementById('modal_pst_rate_container');
        const itRateContainer   = document.getElementById('modal_it_rate_container');
        const gstRateInput      = document.getElementById('modal_gst_rate');
        const pstRateInput      = document.getElementById('modal_pst_rate');
        const itRateInput       = document.getElementById('modal_it_rate');

        const previewNet        = document.getElementById('modal_preview_net');
        const previewGross      = document.getElementById('modal_preview_gross');
        const previewGstPct     = document.getElementById('modal_preview_gst_pct');
        const previewGstVal     = document.getElementById('modal_preview_gst_val');
        const previewPstPct     = document.getElementById('modal_preview_pst_pct');
        const previewPstVal     = document.getElementById('modal_preview_pst_val');
        const previewItPct      = document.getElementById('modal_preview_it_pct');
        const previewItVal      = document.getElementById('modal_preview_it_val');
        const previewTotalTax   = document.getElementById('modal_preview_total_tax');
        const previewGstRow     = document.getElementById('modal_preview_gst_row');
        const previewPstRow     = document.getElementById('modal_preview_pst_row');
        const previewItRow      = document.getElementById('modal_preview_it_row');
        const previewTotalTaxRow = document.getElementById('modal_preview_total_tax_row');

        function recalculate() {
            const amount = parseFloat(amountInput.value) || 0;
            let taxType  = 'none';
            taxTypeInputs.forEach(i => { if (i.checked) taxType = i.value; });

            let gst = 0, pst = 0, it = 0;

            /* Show / hide rate inputs and preview rows */
            if (taxType === 'none') {
                [gstRateContainer, pstRateContainer, itRateContainer].forEach(el => el.classList.add('hidden'));
                [previewGstRow, previewPstRow, previewItRow, previewTotalTaxRow].forEach(el => el.classList.add('hidden'));
            } else if (taxType === 'purchase') {
                gstRateContainer.classList.remove('hidden');
                itRateContainer.classList.remove('hidden');
                pstRateContainer.classList.add('hidden');
                previewGstRow.classList.remove('hidden');
                previewItRow.classList.remove('hidden');
                previewTotalTaxRow.classList.remove('hidden');
                previewPstRow.classList.add('hidden');
                gst = parseFloat(gstRateInput.value) || 0;
                it  = parseFloat(itRateInput.value)  || 0;
            } else {
                pstRateContainer.classList.remove('hidden');
                itRateContainer.classList.remove('hidden');
                gstRateContainer.classList.add('hidden');
                previewPstRow.classList.remove('hidden');
                previewItRow.classList.remove('hidden');
                previewTotalTaxRow.classList.remove('hidden');
                previewGstRow.classList.add('hidden');
                pst = parseFloat(pstRateInput.value) || 0;
                it  = parseFloat(itRateInput.value)  || 0;
            }

            /* Highlight active tax label */
            taxTypeInputs.forEach(i => {
                const lbl = document.getElementById('modal_label_tax_' + i.value);
                if (!lbl) return;
                if (i.checked) lbl.classList.add('border-teal-500', 'bg-teal-50', 'ring-1', 'ring-teal-500');
                else           lbl.classList.remove('border-teal-500', 'bg-teal-50', 'ring-1', 'ring-teal-500');
            });

            /* Calculate gross from net */
            const gstAmt   = Math.round(amount * gst / 100);
            const pstAmt   = Math.round(amount * pst / 100);
            const itAmt    = Math.round(amount * it  / 100);
            const totalTax = gstAmt + pstAmt + itAmt;
            const gross    = amount + totalTax;

            previewNet.textContent        = amount.toLocaleString();
            previewGstPct.textContent     = gst;
            previewGstVal.textContent     = gstAmt.toLocaleString();
            previewPstPct.textContent     = pst;
            previewPstVal.textContent     = pstAmt.toLocaleString();
            previewItPct.textContent      = it;
            previewItVal.textContent      = itAmt.toLocaleString();
            previewTotalTax.textContent   = totalTax.toLocaleString();
            previewGross.textContent      = gross.toLocaleString();
        }

        taxTypeInputs.forEach(i => {
            i.addEventListener('change', function () {
                if (this.value === 'purchase') { gstRateInput.value = '19.00'; itRateInput.value = '11.00'; }
                if (this.value === 'service')  { pstRateInput.value = '20.00'; itRateInput.value = '11.00'; }
                recalculate();
            });
        });

        [amountInput, gstRateInput, pstRateInput, itRateInput].forEach(el => {
            if (el) { el.addEventListener('input', recalculate); el.addEventListener('change', recalculate); }
        });

        window.modalRecalculate = recalculate;
        recalculate();
        updateExpenseTypeSelection();
    });

    function updateExpenseTypeSelection() {
        const radios = document.querySelectorAll('input[name="expense_type"]');
        radios.forEach(radio => {
            const card = document.getElementById('card_expense_type_' + radio.value);
            if (!card) return;
            const badge = card.querySelector('.check-badge');
            if (radio.checked) {
                card.classList.remove('border-slate-200', 'bg-white', 'opacity-70');
                card.classList.add('border-teal-500', 'bg-teal-50/80', 'ring-2', 'ring-teal-400/40', 'shadow-sm', 'opacity-100');
                if (badge) badge.classList.remove('hidden');
            } else {
                card.classList.remove('border-teal-500', 'bg-teal-50/80', 'ring-2', 'ring-teal-400/40', 'shadow-sm');
                card.classList.add('border-slate-200', 'bg-white', 'opacity-70');
                if (badge) badge.classList.add('hidden');
            }
        });
    }
</script>
@endsection
