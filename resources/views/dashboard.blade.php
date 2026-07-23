@extends('layouts.app')

@section('page-content')
    <div class="space-y-6 pb-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between py-2 border-b border-slate-100 pb-3">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-0.5">
                    <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-indigo-600">Dashboard</span>
                </div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight leading-none">Dashboard</h1>
            </div>
        </div>

        <!-- Metric Section (Minimalist & Enhanced Students Card) -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-50/30 via-white to-white p-4 rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgba(79,70,229,0.01)] hover:border-indigo-200 hover:shadow-[0_8px_30px_rgba(79,70,229,0.04)] transition-all duration-300 group flex items-center justify-between">
            <!-- Subtle Background Blur Decorative Spot -->
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-100/40 rounded-full blur-xl pointer-events-none"></div>

            <div class="flex items-center gap-3.5 relative z-10">
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-xl flex items-center justify-center text-base shadow-[0_4px_12px_rgba(79,70,229,0.15)] group-hover:scale-105 transition-transform duration-300">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h3 class="text-slate-400 text-[9px] font-extrabold uppercase tracking-wider mb-0.5">Students</h3>
                    <div class="flex items-baseline gap-1.5">
                        <p class="text-lg font-extrabold text-slate-800 leading-none tracking-tight">{{ number_format($students->count()) }}</p>
                        <span class="text-emerald-600 text-[9px] font-extrabold flex items-center gap-0.5">
                            +{{ $newAdmissions->count() }} <i class="bi bi-arrow-up text-[9px]"></i>
                        </span>
                    </div>
                </div>
            </div>
            
            <span class="relative z-10 bg-indigo-50/70 text-indigo-700 text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg border border-indigo-100/50">
                Active
            </span>
        </div>

        <!-- Reserved Graph Placeholder (Full-Width, Optimized Space) -->
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center items-center min-h-[200px] relative overflow-hidden group">
            <!-- Decorative subtle mesh background pattern -->
            <div class="absolute inset-0 pointer-events-none opacity-5">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMSIgZmlsbD0iIzAwMCIvPjwvc3ZnPg==')] bg-repeat"></div>
            </div>
            
            <div class="relative z-10 flex flex-col items-center text-center max-w-sm">
                <div class="w-10 h-10 bg-slate-50 border border-dashed border-slate-200 text-slate-400 rounded-xl flex items-center justify-center mb-3 group-hover:scale-105 group-hover:border-slate-300 transition-all duration-300">
                    <i class="bi bi-bar-chart-line text-lg"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-700 mb-1">Analytics Module</h3>
                <p class="text-[11px] text-slate-400 font-medium leading-relaxed mb-2.5">
                    This section is reserved for future analytics and academic visual reporting.
                </p>
                <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-500 px-2 py-0.5 rounded-lg text-[8px] font-bold border border-slate-100 uppercase tracking-widest">
                    <i class="bi bi-clock-history"></i> Coming Soon
                </span>
            </div>
        </div>

        <!-- Footer Navigation Row (Clean Icons + Text Only, Floating on Mobile) -->
        <div class="fixed bottom-0 left-0 right-0 md:relative bg-white/95 md:bg-transparent backdrop-blur-md border-t border-slate-100 md:border-t-0 py-2 md:py-0 px-4 md:px-0 grid grid-cols-3 gap-2 z-40 shadow-[0_-8px_30px_rgba(0,0,0,0.03)] md:shadow-none">
            <!-- Attendance Box -->
            <a href="{{ route('attendance.summary') }}" class="group flex flex-col items-center justify-center py-1 transition-all duration-300 hover:-translate-y-0.5 text-center">
                <i class="bi bi-person-check text-lg md:text-2xl mb-0.5 text-emerald-600 group-hover:text-emerald-500 transition-colors"></i>
                <span class="text-[9px] md:text-xs font-bold text-slate-600 group-hover:text-emerald-650 transition-colors tracking-tighter md:tracking-normal">Attendance</span>
            </a>

            <!-- Assessment Box -->
            <a href="{{ route('tests.index') }}" class="group flex flex-col items-center justify-center py-1 transition-all duration-300 hover:-translate-y-0.5 text-center">
                <i class="bi bi-journal-check text-lg md:text-2xl mb-0.5 text-amber-600 group-hover:text-amber-500 transition-colors"></i>
                <span class="text-[9px] md:text-xs font-bold text-slate-600 group-hover:text-amber-650 transition-colors tracking-tighter md:tracking-normal">Assessment</span>
            </a>

            <!-- Finance Box -->
            <a href="{{ route('finance.index') }}" class="group flex flex-col items-center justify-center py-1 transition-all duration-300 hover:-translate-y-0.5 text-center">
                <i class="bi bi-wallet2 text-lg md:text-2xl mb-0.5 text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                <span class="text-[9px] md:text-xs font-bold text-slate-500 group-hover:text-indigo-650 transition-colors tracking-tighter md:tracking-normal">Finance</span>
            </a>
        </div>
    </div>
@endsection
