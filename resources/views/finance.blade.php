@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-16 md:pb-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between py-2 border-b border-slate-100 pb-3">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-0.5">
                    <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-indigo-600">Finance</span>
                </div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight leading-none">Finance</h1>
            </div>
            <!-- Minimal Quick Actions for Expenses -->
            <div class="flex items-center gap-2.5">
                <a href="{{ route('expenses.index') }}" 
                    class="px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-wider text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 border border-slate-200/50 rounded-lg transition-all flex items-center gap-1.5"
                    title="View School Expenses">
                    <i class="bi bi-receipt text-[11px]"></i>
                    <span class="hidden xs:inline">Expenses</span>
                </a>
            </div>
        </div>

        <!-- Metric Section (FTF, NSB, & Special Grants Cards - Optimized & Enhanced) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- FTF Card -->
            <a href="{{ route('ftf-vouchers.index') }}" class="relative overflow-hidden bg-gradient-to-r from-emerald-50/30 via-white to-white p-4 rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgba(16,185,129,0.01)] hover:border-emerald-250 hover:shadow-[0_8px_30px_rgba(16,185,129,0.06)] hover:-translate-y-0.5 transition-all duration-300 group flex items-center justify-between cursor-pointer">
                <!-- Subtle Background Blur Decorative Spot -->
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-100/40 rounded-full blur-xl pointer-events-none"></div>

                <div class="flex items-center gap-3.5 relative z-10">
                    <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-xl flex items-center justify-center text-base shadow-[0_4px_12px_rgba(16,185,129,0.15)] group-hover:scale-105 transition-transform duration-300">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div>
                        <h3 class="text-slate-400 text-[9px] font-extrabold uppercase tracking-wider mb-0.5">
                            FTF Balance
                            @if($currentSession)
                                <span class="text-indigo-500 font-extrabold text-[8px] tracking-normal lowercase">({{ $currentSession->name }})</span>
                            @endif
                        </h3>
                        <div class="flex items-baseline gap-1.5">
                            <p class="text-base font-extrabold text-slate-800 leading-none tracking-tight">{{ number_format($ftfBalance) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                            <span class="text-emerald-600 text-[9px] font-extrabold flex items-center gap-0.5">
                                +{{ $ftfChange }}% <i class="bi bi-arrow-up text-[9px]"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-slate-350 group-hover:text-emerald-600 transition-colors duration-250 relative z-10 pr-1">
                    <i class="bi bi-chevron-right text-sm"></i>
                </div>
            </a>

            <!-- NSB Card -->
            <a href="{{ route('nsb-receipts.index') }}" class="relative overflow-hidden bg-gradient-to-r from-amber-50/30 via-white to-white p-4 rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgba(245,158,11,0.01)] hover:border-amber-250 hover:shadow-[0_8px_30px_rgba(245,158,11,0.06)] hover:-translate-y-0.5 transition-all duration-300 group flex items-center justify-between cursor-pointer">
                <!-- Subtle Background Blur Decorative Spot -->
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-100/40 rounded-full blur-xl pointer-events-none"></div>

                <div class="flex items-center gap-3.5 relative z-10">
                    <div class="w-11 h-11 bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-xl flex items-center justify-center text-base shadow-[0_4px_12px_rgba(245,158,11,0.15)] group-hover:scale-105 transition-transform duration-300">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <h3 class="text-slate-400 text-[9px] font-extrabold uppercase tracking-wider mb-0.5">NSB Balance</h3>
                        <div class="flex items-baseline gap-1.5">
                            <p class="text-base font-extrabold text-slate-800 leading-none tracking-tight">{{ number_format($nsbBalance) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                            <span class="text-amber-600 text-[9px] font-extrabold flex items-center gap-0.5">
                                +{{ $nsbChange }}% <i class="bi bi-arrow-up text-[9px]"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-slate-350 group-hover:text-amber-600 transition-colors duration-250 relative z-10 pr-1">
                    <i class="bi bi-chevron-right text-sm"></i>
                </div>
            </a>

            <!-- Special Grants Card (Teal Theme) -->
            <a href="{{ route('special-grants.index') }}" class="relative overflow-hidden bg-gradient-to-r from-teal-50/30 via-white to-white p-4 rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgba(20,184,166,0.01)] hover:border-teal-250 hover:shadow-[0_8px_30px_rgba(20,184,166,0.06)] hover:-translate-y-0.5 transition-all duration-300 group flex items-center justify-between cursor-pointer">
                <!-- Subtle Background Blur Decorative Spot -->
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-teal-100/40 rounded-full blur-xl pointer-events-none"></div>

                <div class="flex items-center gap-3.5 relative z-10">
                    <div class="w-11 h-11 bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-xl flex items-center justify-center text-base shadow-[0_4px_12px_rgba(20,184,166,0.15)] group-hover:scale-105 transition-transform duration-300">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div>
                        <h3 class="text-slate-400 text-[9px] font-extrabold uppercase tracking-wider mb-0.5">Special Grants</h3>
                        <div class="flex items-baseline gap-1.5">
                            <p class="text-base font-extrabold text-slate-800 leading-none tracking-tight">{{ number_format($specialGrantsBalance) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                            <span class="text-teal-600 text-[9px] font-extrabold flex items-center gap-0.5">
                                +{{ $specialGrantsChange }}% <i class="bi bi-arrow-up text-[9px]"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-slate-350 group-hover:text-teal-600 transition-colors duration-250 relative z-10 pr-1">
                    <i class="bi bi-chevron-right text-sm"></i>
                </div>
            </a>
        </div>

        <!-- Innovative Expenses Section (Minimal & Interactive Card) -->
        <div class="relative overflow-hidden bg-gradient-to-r from-rose-50/20 via-white to-white p-4 rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgba(244,63,94,0.01)] hover:border-rose-200 hover:shadow-[0_8px_30px_rgba(244,63,94,0.05)] transition-all duration-300 group flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-2">
            <!-- Subtle background blur spot -->
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-rose-100/30 rounded-full blur-xl pointer-events-none"></div>

            <div class="flex items-center gap-3.5 relative z-10">
                <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-rose-600 text-white rounded-xl flex items-center justify-center text-sm shadow-[0_4px_12px_rgba(244,63,94,0.12)] group-hover:scale-105 transition-transform duration-300">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800 leading-none">School Expenses</h4>
                    <p class="text-[9px] text-slate-400 mt-1">Record purchases, utility payments, and payroll cash outflows</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 w-full sm:w-auto relative z-10 self-stretch sm:self-auto">
                <a href="{{ route('expenses.index') }}"
                    class="flex-1 sm:flex-initial text-center px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-wider bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-800 border border-slate-200/60 rounded-lg transition-all">
                    View list
                </a>
                <a href="{{ route('expenses.create') }}"
                    class="flex-1 sm:flex-initial text-center px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-wider bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition-all">
                    + Add New
                </a>
            </div>
        </div>

        <!-- Footer Navigation Row (Clean Icons + Text Only, Floating on Mobile) -->
        <div class="fixed bottom-0 left-0 right-0 md:relative bg-white/95 md:bg-transparent backdrop-blur-md border-t border-slate-100 md:border-t-0 py-2 md:py-0 px-4 md:px-0 grid grid-cols-3 gap-2 z-40 shadow-[0_-8px_30px_rgba(0,0,0,0.03)] md:shadow-none">
            <!-- Attendance Box -->
            <a href="{{ route('attendance.summary') }}" class="group flex flex-col items-center justify-center py-1 transition-all duration-300 hover:-translate-y-0.5 text-center">
                <i class="bi bi-person-check text-lg md:text-2xl mb-0.5 text-slate-400 group-hover:text-emerald-500 transition-colors"></i>
                <span class="text-[9px] md:text-xs font-bold text-slate-500 group-hover:text-emerald-650 transition-colors tracking-tighter md:tracking-normal">Attendance</span>
            </a>

            <!-- Assessment Box -->
            <a href="{{ route('tests.index') }}" class="group flex flex-col items-center justify-center py-1 transition-all duration-300 hover:-translate-y-0.5 text-center">
                <i class="bi bi-journal-check text-lg md:text-2xl mb-0.5 text-slate-400 group-hover:text-amber-500 transition-colors"></i>
                <span class="text-[9px] md:text-xs font-bold text-slate-500 group-hover:text-amber-650 transition-colors tracking-tighter md:tracking-normal">Assessment</span>
            </a>

            <!-- Finance Box (Active) -->
            <a href="{{ route('finance.index') }}" class="group flex flex-col items-center justify-center py-1 transition-all duration-300 hover:-translate-y-0.5 text-center">
                <i class="bi bi-wallet2 text-lg md:text-2xl mb-0.5 text-indigo-600 group-hover:text-indigo-500 transition-colors"></i>
                <span class="text-[9px] md:text-xs font-bold text-indigo-600 group-hover:text-indigo-650 transition-colors tracking-tighter md:tracking-normal">Finance</span>
            </a>
        </div>
    </div>
@endsection
