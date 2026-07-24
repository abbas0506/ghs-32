@extends('layouts.app')

@section('page-content')
    <div class="space-y-3 pb-16 md:pb-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div>
                <div class="flex items-center gap-1.5 text-slate-400 text-[8px] uppercase tracking-[0.1em] font-bold mb-0.5">
                    <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[7px]"></i>
                    <span class="text-indigo-600">Finance</span>
                </div>
                <h1 class="text-base font-bold text-slate-800 tracking-tight leading-none">Finance Dashboard</h1>
            </div>
            <!-- Quick Actions -->
            <div class="flex items-center gap-1.5">
                <a href="{{ route('grants.index') }}" 
                    class="px-2 py-1 text-[8px] font-extrabold uppercase tracking-wider text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 border border-slate-200/50 rounded-lg transition-all flex items-center gap-1"
                    title="Manage Grants">
                    <i class="bi bi-gear text-[10px]"></i>
                    <span class="hidden xs:inline">Grants Admin</span>
                </a>
                <a href="{{ route('accounts.index') }}" 
                    class="px-2 py-1 text-[8px] font-extrabold uppercase tracking-wider text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200/60 rounded-lg transition-all flex items-center gap-1"
                    title="Bank Accounts & Ledger">
                    <i class="bi bi-bank text-[10px]"></i>
                    <span class="hidden xs:inline">Bank Accounts</span>
                </a>
            </div>
        </div>

        <!-- Metric Section (Bank Accounts & Grant Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
            
            {{-- FTF Bank Account Card --}}
            @php
                $ftfBankBal = $ftfAccount ? $ftfAccount->balance() : $ftfBalance;
            @endphp
            <a href="{{ $ftfAccount ? route('accounts.show', $ftfAccount->id) : route('accounts.index') }}" 
               class="relative overflow-hidden bg-gradient-to-r from-emerald-50/40 via-white to-white p-3 rounded-xl border border-slate-100 shadow-sm hover:border-emerald-250 hover:-translate-y-0.5 transition-all duration-300 group flex items-center justify-between cursor-pointer">
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-lg flex items-center justify-center text-sm shadow-sm group-hover:scale-105 transition-transform duration-300">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div>
                        <h3 class="text-slate-400 text-[8px] font-extrabold uppercase tracking-wider mb-0.5">
                            FTF Bank Account
                        </h3>
                        <div class="flex items-baseline gap-1">
                            <p class="text-sm font-black text-slate-800 leading-none tracking-tight">{{ number_format($ftfBankBal) }} <span class="text-[8px] font-bold text-slate-400">PKR</span></p>
                        </div>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-emerald-600 transition-colors duration-200 relative z-10 pr-1">
                    <i class="bi bi-chevron-right text-xs"></i>
                </div>
            </a>

            {{-- SMC Bank Account Card --}}
            @php
                $smcBankBal = $smcAccount ? $smcAccount->balance() : 0;
            @endphp
            <a href="{{ $smcAccount ? route('accounts.show', $smcAccount->id) : route('accounts.index') }}" 
               class="relative overflow-hidden bg-gradient-to-r from-blue-50/40 via-white to-white p-3 rounded-xl border border-slate-100 shadow-sm hover:border-blue-250 hover:-translate-y-0.5 transition-all duration-300 group flex items-center justify-between cursor-pointer">
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg flex items-center justify-center text-sm shadow-sm group-hover:scale-105 transition-transform duration-300">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <h3 class="text-slate-400 text-[8px] font-extrabold uppercase tracking-wider mb-0.5">
                            SMC Bank Account
                        </h3>
                        <div class="flex items-baseline gap-1">
                            <p class="text-sm font-black text-slate-800 leading-none tracking-tight">{{ number_format($smcBankBal) }} <span class="text-[8px] font-bold text-slate-400">PKR</span></p>
                        </div>
                    </div>
                </div>
                <div class="text-slate-300 group-hover:text-blue-600 transition-colors duration-200 relative z-10 pr-1">
                    <i class="bi bi-chevron-right text-xs"></i>
                </div>
            </a>

            <!-- Individual Grant Cards -->
            @foreach ($specialGrants as $grant)
                @php
                    $isNsb = (str_contains(strtolower($grant->title), 'nsb') || str_contains(strtolower($grant->title), 'non-salary'));
                    $gradientClass = $isNsb ? 'from-amber-50/30 via-white to-white' : 'from-teal-50/30 via-white to-white';
                    $borderHover = $isNsb ? 'hover:border-amber-250' : 'hover:border-teal-250';
                    $iconBg = $isNsb ? 'from-amber-500 to-amber-600' : 'from-teal-500 to-teal-600';
                    $iconClass = $isNsb ? 'bi bi-wallet2' : 'bi bi-gift';
                    $chevronColor = $isNsb ? 'group-hover:text-amber-600' : 'group-hover:text-teal-600';
                @endphp
                <a href="{{ route('grants.show', $grant->id) }}" class="relative overflow-hidden bg-gradient-to-r {{ $gradientClass }} p-3 rounded-xl border border-slate-100 shadow-sm {{ $borderHover }} hover:-translate-y-0.5 transition-all duration-300 group flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3 relative z-10">
                        <div class="w-9 h-9 bg-gradient-to-br {{ $iconBg }} text-white rounded-lg flex items-center justify-center text-sm shadow-sm group-hover:scale-105 transition-transform duration-300">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                        <div>
                            <h3 class="text-slate-400 text-[8px] font-extrabold uppercase tracking-wider mb-0.5" title="{{ $grant->title }}">{{ Str::limit($grant->title, 22) }}</h3>
                            <div class="flex items-baseline gap-1">
                                <p class="text-sm font-black text-slate-800 leading-none tracking-tight">{{ number_format($grant->balance) }} <span class="text-[8px] font-bold text-slate-400">PKR</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="text-slate-300 {{ $chevronColor }} transition-colors duration-200 relative z-10 pr-1">
                        <i class="bi bi-chevron-right text-xs font-bold"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
