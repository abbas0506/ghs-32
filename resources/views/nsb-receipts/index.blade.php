@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-6">
        <!-- Minimal Header Section (Teal Theme) -->
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('finance.index') }}" class="text-slate-400 hover:text-teal-600 text-xs transition-colors" title="Back to Finance">
                    <i class="bi bi-arrow-left text-sm font-bold"></i>
                </a>
                <span class="text-xs font-extrabold text-slate-800 tracking-tight uppercase">NSB Receipts & Ledger</span>
            </div>
            <a href="{{ route('nsb-receipts.create') }}"
                class="px-2.5 py-1 text-[10px] bg-teal-50 hover:bg-teal-100 text-teal-700 rounded-lg font-bold transition-all shadow-sm">
                + Add Receipt
            </a>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Session Balance Summary Card (Teal Theme) -->
        <div class="relative overflow-hidden bg-gradient-to-br from-teal-500 to-emerald-600 text-white p-4 rounded-xl border border-teal-400/20 shadow-sm flex flex-col gap-3">
            <div class="absolute -right-10 -bottom-10 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-[8px] font-black uppercase tracking-wider text-teal-100/80">Active Session NSB</p>
                    <h2 class="text-sm font-extrabold tracking-tight">
                        Session: {{ $session ? $session->name : 'No Active Session' }}
                    </h2>
                    @if ($session)
                        <p class="text-[9px] text-teal-100/90 mt-0.5 uppercase tracking-wider font-bold">
                            opening balance: <span class="text-white">{{ number_format($session->nsb_start) }}</span>
                        </p>
                    @endif
                </div>
            </div>

            @if ($session)
                <div class="grid grid-cols-3 gap-4 relative z-10 border-t border-white/10 pt-2.5">
                    <div>
                        <p class="text-[8px] uppercase tracking-wider font-extrabold text-teal-100/80">received</p>
                        <p class="text-xs font-black leading-none mt-0.5 text-emerald-100">{{ number_format($receipts->sum('amount')) }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase tracking-wider font-extrabold text-teal-100/80">spent</p>
                        <p class="text-xs font-black leading-none mt-0.5 text-rose-100">{{ number_format($session->nsb_expenses) }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase tracking-wider font-extrabold text-teal-100/80">balance</p>
                        <p class="text-xs font-black leading-none mt-0.5 text-teal-50">{{ number_format($session->nsb_balance) }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- NSB Cashbook Unified Ledger -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-3 py-2 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider">NSB Cashbook Ledger</h3>
                <span class="px-1.5 py-0.5 bg-slate-200/60 rounded text-[8px] font-bold text-slate-655">
                    {{ $ledger->count() }} transactions
                </span>
            </div>

            @if ($session)
                <div class="px-3 py-2 bg-teal-50/40 border-b border-slate-100 flex items-center justify-between text-[10px] text-slate-600 font-bold uppercase tracking-wider">
                    <span class="flex items-center gap-1.5">
                        <i class="bi bi-wallet2 text-teal-600"></i>
                        opening balance
                    </span>
                    <span class="font-extrabold text-teal-800 font-mono">{{ number_format($session->nsb_start) }}</span>
                </div>
            @endif

            @if ($ledger->count() == 0)
                <div class="flex flex-col items-center justify-center py-8 px-4 text-center">
                    <i class="bi bi-wallet2 text-lg text-slate-400 mb-2"></i>
                    <h4 class="text-xs font-bold text-slate-755">No NSB Transactions Found</h4>
                </div>
            @else
                <div class="overflow-auto max-h-[380px] md:max-h-[420px] scrollbar-thin">
                    <table class="w-full text-left border-collapse relative">
                        <thead class="sticky top-0 z-20 bg-slate-50 shadow-sm">
                            <tr class="border-b border-slate-100">
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left">Particulars</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left w-[85px] min-w-[85px]">Date</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left w-[80px]">Receipt #</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left w-[80px]">Net Paid</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left w-[65px]">GST</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left w-[65px]">PST</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left w-[65px]">IT</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left w-[80px]">Outflow</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left w-[80px]">Inflow</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-50 text-left w-[95px]">Running Amount</th>
                                <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase text-slate-450 bg-slate-55 bg-slate-50 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            {{-- Unified statement rows including receipts and expenses --}}
                            @foreach ($ledger as $item)
                                <tr class="hover:bg-slate-50/60 transition-colors duration-150 text-[11px] text-slate-655">
                                    {{-- Particulars --}}
                                    <td class="py-2 px-3">
                                        @if ($item->type === 'receipt')
                                            <div class="font-bold text-slate-800 text-[11px] flex items-center gap-1">
                                                <span class="inline-flex items-center px-1 py-0.2 rounded text-[7px] font-bold uppercase bg-teal-50 text-teal-700">
                                                    Q{{ $item->quarter }}
                                                </span>
                                                {{ $item->description }}
                                            </div>
                                        @else
                                            <div class="font-bold text-slate-800 text-[11px] flex items-center gap-1">
                                                <span class="inline-flex items-center px-1 py-0.2 rounded text-[7px] font-bold uppercase bg-rose-50 text-rose-700">
                                                    Debit
                                                </span>
                                                {{ $item->description }}
                                            </div>
                                        @endif
                                    </td>
                                    
                                    {{-- Date --}}
                                    <td class="py-2 px-3 font-semibold text-slate-500 text-[10px] w-[85px] min-w-[85px]">
                                        {{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}
                                    </td>
                                    
                                    {{-- Receipt # --}}
                                    <td class="py-2 px-3 font-semibold text-slate-700 text-[10px] w-[80px] font-mono">
                                        @if ($item->type === 'expense')
                                            {{ $item->raw_model->receipt_no }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Net Paid --}}
                                    <td class="py-2 px-3 font-bold text-slate-800 text-[11px] w-[80px]">
                                        @if ($item->type === 'expense')
                                            {{ number_format($item->raw_model->net_amount) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- GST --}}
                                    <td class="py-2 px-3 text-slate-500 text-[10px] w-[65px]">
                                        @if ($item->type === 'expense' && $item->raw_model->gst_amount > 0)
                                            <div class="font-semibold">{{ number_format($item->raw_model->gst_amount) }}</div>
                                            <div class="text-[7px] text-slate-400 font-bold">({{ $item->raw_model->gst_rate }}%)</div>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- PST --}}
                                    <td class="py-2 px-3 text-slate-500 text-[10px] w-[65px]">
                                        @if ($item->type === 'expense' && $item->raw_model->pst_amount > 0)
                                            <div class="font-semibold">{{ number_format($item->raw_model->pst_amount) }}</div>
                                            <div class="text-[7px] text-slate-400 font-bold">({{ $item->raw_model->pst_rate }}%)</div>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- IT --}}
                                    <td class="py-2 px-3 text-slate-500 text-[10px] w-[65px]">
                                        @if ($item->type === 'expense' && $item->raw_model->it_amount > 0)
                                            <div class="font-semibold">{{ number_format($item->raw_model->it_amount) }}</div>
                                            <div class="text-[7px] text-slate-400 font-bold">({{ $item->raw_model->it_rate }}%)</div>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Outflow (Gross) --}}
                                    <td class="py-2 px-3 font-bold text-rose-600 text-[11px] w-[80px]">
                                        @if ($item->type === 'expense')
                                            {{ number_format($item->amount) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Inflow --}}
                                    <td class="py-2 px-3 font-bold text-emerald-600 text-[11px] w-[80px]">
                                        @if ($item->type === 'receipt')
                                            {{ number_format($item->amount) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Running Amount --}}
                                    <td class="py-2 px-3 font-extrabold text-slate-900 text-[11px] w-[95px]">
                                        {{ number_format($item->balance_after) }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="py-2 px-3 text-right">
                                        @if ($item->type === 'receipt')
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('nsb-receipts.edit', $item->id) }}"
                                                    class="text-slate-400 hover:text-teal-650 transition-colors"
                                                    title="Edit Receipt">
                                                    <i class="bi bi-pencil-square text-xs"></i>
                                                </a>
                                                <form id="delete-form-{{ $item->id }}"
                                                    action="{{ route('nsb-receipts.destroy', $item->id) }}"
                                                    method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        onclick="confirmDelete(event, {{ $item->id }})"
                                                        class="text-red-500 hover:text-red-750 transition-colors p-0.5"
                                                        title="Delete Receipt">
                                                        <i class="bi bi-trash text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-[8px] text-slate-400 font-bold uppercase tracking-wider bg-slate-100 px-1 py-0.2 rounded">Locked</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="sticky bottom-0 z-20 bg-slate-100 border-t-2 border-slate-200 shadow-[0_-2px_6px_rgba(0,0,0,0.05)]">
                            <tr class="font-extrabold text-[9px] text-slate-800">
                                <td class="py-2 px-3 bg-slate-100">Total</td>
                                <td class="py-2 px-3 bg-slate-100"></td>
                                <td class="py-2 px-3 bg-slate-100"></td>
                                <td class="py-2 px-3 bg-slate-100 text-slate-800">
                                    {{ number_format($ledger->where('type', 'expense')->sum(fn($i) => $i->raw_model->net_amount)) }}
                                </td>
                                <td class="py-2 px-3 bg-slate-100 text-slate-700">
                                    {{ number_format($ledger->where('type', 'expense')->sum(fn($i) => $i->raw_model->gst_amount)) }}
                                </td>
                                <td class="py-2 px-3 bg-slate-100 text-slate-700">
                                    {{ number_format($ledger->where('type', 'expense')->sum(fn($i) => $i->raw_model->pst_amount)) }}
                                </td>
                                <td class="py-2 px-3 bg-slate-100 text-slate-700">
                                    {{ number_format($ledger->where('type', 'expense')->sum(fn($i) => $i->raw_model->it_amount)) }}
                                </td>
                                <td class="py-2 px-3 bg-slate-100 text-rose-700">
                                    {{ number_format($ledger->where('type', 'expense')->sum('amount')) }}
                                </td>
                                <td class="py-2 px-3 bg-slate-100 text-emerald-700">
                                    {{ number_format($ledger->where('type', 'receipt')->sum('amount')) }}
                                </td>
                                <td class="py-2 px-3 bg-teal-50 text-slate-900 font-extrabold">
                                    {{ number_format($session ? $session->nsb_balance : 0) }}
                                </td>
                                <td class="py-2 px-3 bg-slate-100"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        function confirmDelete(event, id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Delete this NSB receipt? Cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed || result.value) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
