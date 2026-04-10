@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">Home Dashboard</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">Fee Vouchers</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-wallet2 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 leading-none mb-1">Financial Center</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Manage and track student fee collections</p>
                    </div>
                </div>
            </div>

            @role('head')
                <a href="{{ route('fee-vouchers.create') }}" 
                   class="flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                   <i class="bi-plus-lg"></i> Create Voucher
                </a>
            @endrole
        </div>

        @php
            $totalCollection = $feeVouchers->sum(fn($v) => $v->sumOfPaidAmount());
            $totalDue = $feeVouchers->sum(fn($v) => $v->sumOfDueAmount());
            $collectionRate = $totalDue > 0 ? round(($totalCollection / $totalDue) * 100, 1) : 0;
        @endphp

        <!-- Quick Summary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-teal-600 rounded-3xl p-6 text-white shadow-xl shadow-teal-100 flex items-center justify-between relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-teal-100 uppercase tracking-widest mb-1">Total Collection</p>
                    <h2 class="text-2xl font-black">Rs. {{ number_format($totalCollection) }}</h2>
                </div>
                <div class="text-right relative z-10">
                    <p class="text-xs font-bold text-teal-100 uppercase tracking-tighter">{{ $collectionRate }}% Rate</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Active Vouchers</p>
                    <h2 class="text-2xl font-black text-slate-800">{{ $feeVouchers->count() }}</h2>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center">
                    <i class="bi-clipboard-check"></i>
                </div>
            </div>

            <div class="bg-rose-50 rounded-3xl p-6 border border-rose-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white text-rose-600 flex items-center justify-center shadow-sm">
                    <i class="bi-exclamation-circle-fill"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-rose-700 uppercase tracking-widest mb-0.5">Outstanding</p>
                    <p class="text-sm font-bold text-rose-900 leading-tight">Rs. {{ number_format($totalDue - $totalCollection) }}</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- List Section -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <!-- Search Control -->
            <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100">
                <div class="relative w-full md:w-80 group">
                    <input type="text" id='searchby' placeholder="Search vouchers..." oninput="search(event)"
                        class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors"></i>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-fixed w-full border-collapse">
                    <thead>
                        <tr class="text-left">
                            <th class="w-16 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">#</th>
                            <th class="w-48 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Voucher Details</th>
                            <th class="w-24 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-center">Collection Status</th>
                            <th class="w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($feeVouchers->sortByDesc('due_date') as $feeVoucher)
                            @php
                                $paid = $feeVoucher->sumOfPaidAmount();
                                $due = $feeVoucher->sumOfDueAmount();
                                $rate = $due > 0 ? round(($paid / $due) * 100, 1) : 0;
                            @endphp
                            <tr class="tr group hover:bg-slate-50/80 transition-all">
                                <td class="py-2">
                                    <div class="w-8 h-8 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400 border border-slate-200">
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td class="py-2">
                                    <div class="flex flex-col">
                                        <a href="{{ route('fee-vouchers.show', $feeVoucher) }}" class="text-sm text-left font-black text-slate-800 hover:text-teal-600 transition-colors leading-tight">
                                            {{ $feeVoucher->description }}
                                        </a>
                                        <div class="flex items-center gap-3 mt-1.5">
                                            <span class="text-[10px] text-teal-600 font-bold uppercase tracking-tight">Rs. {{ number_format($feeVoucher->amount) }}</span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Due: {{ $feeVoucher->due_date->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="flex items-center justify-between w-full max-w-[120px]">
                                            <span class="text-[9px] font-black text-slate-400 uppercase">{{ $rate }}%</span>
                                            <span class="text-[9px] font-black text-slate-700">Rs. {{ number_format($paid) }}</span>
                                        </div>
                                        <div class="w-full max-w-[120px] h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-teal-500 rounded-full transition-all duration-700" style="width:{{ $rate }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2 text-enter">
                                    <a href="{{ route('fee-vouchers.show', $feeVoucher) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-teal-600 hover:border-teal-200 transition-all">
                                        <i class="bi-chevron-right text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="module">
        window.search = function(event) {
            const searchtext = event.target.value.toLowerCase();
            document.querySelectorAll('.tr').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.classList.toggle('hidden', !text.includes(searchtext));
            });
        };
    </script>
@endsection
