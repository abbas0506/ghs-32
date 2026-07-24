@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-6">
        {{-- Minimal Header Section --}}
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('finance.index') }}" class="text-slate-400 hover:text-teal-600 text-xs transition-colors" title="Back to Finance">
                    <i class="bi bi-arrow-left text-sm font-bold"></i>
                </a>
                <span class="text-xs font-extrabold text-slate-800 tracking-tight uppercase">School Expenses</span>
            </div>
            <a href="{{ route('expenses.create') }}"
                class="px-2.5 py-1 text-[10px] bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg font-bold transition-all shadow-sm">
                + Add Expense
            </a>
        </div>

        {{-- Page Messages --}}
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Search and Filter Controls (Minimal & Responsive) --}}
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            {{-- Search Input --}}
            <div class="flex flex-1 items-center bg-white border border-slate-100 rounded-xl px-3 py-1.5 shadow-sm max-w-md">
                <i class="bi bi-search text-slate-400 mr-2 text-xs"></i>
                <input type="text" id="searchby" placeholder="Search expense details..." 
                    class="w-full text-xs text-slate-800 placeholder-slate-400 bg-transparent outline-none"
                    oninput="filterExpenses()">
            </div>
            
            {{-- Category Filter Select --}}
            <div class="flex items-center bg-white border border-slate-100 rounded-xl px-2 py-1 shadow-sm max-w-[220px]">
                <i class="bi bi-funnel text-slate-400 mr-1.5 text-xs pl-1"></i>
                <select id="category_filter" onchange="filterExpenses()"
                    class="text-xs text-slate-700 bg-transparent outline-none border-0 pr-6 py-0.5 focus:ring-0 font-semibold cursor-pointer">
                    <option value="all">All Funds</option>
                    <option value="ftf">Farogh-e-Taleem Fund (FTF)</option>
                    <option value="nsb">Non-Salary Budget (NSB)</option>
                    <optgroup label="Special Grants">
                        @foreach ($specialGrants as $grant)
                            <option value="grant-{{ $grant->id }}">{{ $grant->title }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
        </div>

        {{-- Expenses Table --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-3 py-2 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider">Cashbook Expenses</h3>
                <span id="visible_count_badge" class="px-1.5 py-0.5 bg-slate-200/60 rounded text-[8px] font-bold text-slate-655">
                    {{ $expenses->count() }} records
                </span>
            </div>

            @if ($expenses->count() == 0)
                <div class="flex flex-col items-center justify-center py-8 px-4 text-center">
                    <i class="bi bi-receipt text-lg text-slate-400 mb-2"></i>
                    <h4 class="text-xs font-bold text-slate-755">No Expenses Recorded</h4>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/20">
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left">Details</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left">Fund</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left w-[110px] min-w-[110px]">Date</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left">Tax Details</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left">Gross</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left">Net Paid</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($expenses as $expense)
                                <tr class="tr hover:bg-slate-50/60 transition-colors duration-150 text-xs text-slate-655"
                                    data-fund-type="{{ $expense->fund_type }}"
                                    data-grant-id="{{ $expense->special_grant_id ?? '' }}">
                                    <td class="py-2.5 px-3">
                                         <div class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                             {{ $expense->expenseAccount->name }}
                                             @if($expense->expense_type)
                                                 <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[8px] font-bold uppercase bg-slate-100 text-slate-600">
                                                     {{ $expense->expense_type }}
                                                 </span>
                                             @endif
                                         </div>
                                         @if($expense->description)
                                             <div class="text-[11px] text-slate-600 font-medium mt-0.5">{{ $expense->description }}</div>
                                         @endif
                                         <div class="text-[9px] text-slate-400 font-semibold mt-0.5">
                                             Paid via {{ $expense->paymentAccount->name }}
                                             @if ($expense->fund_type === 'special_grant' && $expense->specialGrant)
                                                 &middot; <span class="text-indigo-600">{{ $expense->specialGrant->title }}</span>
                                             @endif
                                         </div>
                                     </td>
                                    <td class="py-2.5 px-3">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-bold uppercase {{ $expense->fund_type == 'ftf' ? 'bg-emerald-50 text-emerald-700' : ($expense->fund_type == 'nsb' ? 'bg-amber-50 text-amber-700' : 'bg-teal-50 text-teal-700') }}">
                                            {{ $expense->fund_type }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-600 text-[11px] w-[110px] min-w-[110px]">
                                        {{ $expense->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-[10px]">
                                        @if ($expense->tax_type === 'none')
                                            <span class="text-slate-400 italic">No Tax Deducted</span>
                                        @else
                                            <div class="space-y-0.5 text-[9px] font-medium text-slate-500">
                                                @if ($expense->gst_amount > 0)
                                                    <div>GST ({{ $expense->gst_rate }}%): <span class="font-semibold">{{ number_format($expense->gst_amount) }}</span></div>
                                                @endif
                                                @if ($expense->pst_amount > 0)
                                                    <div>PST ({{ $expense->pst_rate }}%): <span class="font-semibold">{{ number_format($expense->pst_amount) }}</span></div>
                                                @endif
                                                @if ($expense->it_amount > 0)
                                                    <div>IT ({{ $expense->it_rate }}%): <span class="font-semibold">{{ number_format($expense->it_amount) }}</span></div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-500 text-xs">
                                        {{ number_format($expense->amount) }}
                                    </td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800 text-xs">
                                        {{ number_format($expense->net_amount ?? $expense->amount) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        @if ($expense->status)
                                            <span class="inline-flex items-center gap-1 text-emerald-600 text-[10px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Paid
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-amber-500 text-[10px] font-bold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                Pending
                                            </span>
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
@endsection

@section('script')
    <script>
        function filterExpenses() {
            const searchText = document.getElementById('searchby').value.toLowerCase();
            const filterVal = document.getElementById('category_filter').value;
            const rows = document.querySelectorAll('.tr');
            
            let visibleCount = 0;

            rows.forEach(row => {
                const detailText = row.querySelector('div').textContent.toLowerCase();
                const rowFund = row.getAttribute('data-fund-type');
                const rowGrant = row.getAttribute('data-grant-id');

                const matchesSearch = detailText.includes(searchText);

                let matchesFilter = false;
                if (filterVal === 'all') {
                    matchesFilter = true;
                } else if (filterVal === 'ftf' && rowFund === 'ftf') {
                    matchesFilter = true;
                } else if (filterVal === 'nsb' && rowFund === 'nsb') {
                    matchesFilter = true;
                } else if (filterVal.startsWith('grant-')) {
                    const grantId = filterVal.split('-')[1];
                    if (rowFund === 'special_grant' && rowGrant === grantId) {
                        matchesFilter = true;
                    }
                }

                if (matchesSearch && matchesFilter) {
                    row.classList.remove('hidden');
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            const badge = document.getElementById('visible_count_badge');
            if (badge) {
                badge.textContent = visibleCount + ' / ' + rows.length + ' visible';
            }
        }
    </script>
@endsection
