@extends('layouts.app')

@section('page-content')
    <div class="space-y-3.5 pb-4">
        <!-- Header Section -->
        <div class="flex items-center justify-between py-0.5 border-b border-slate-100 pb-1.5">
            <div>
                <div class="flex items-center gap-1.5 text-slate-400 text-[8px] uppercase tracking-[0.1em] font-bold mb-0.5">
                    <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[7px]"></i>
                    <span class="text-indigo-600">Dashboard</span>
                </div>
                <h1 class="text-lg font-bold text-slate-800 tracking-tight leading-none">Dashboard</h1>
            </div>
        </div>

        <!-- Metric Section (Minimalist & Enhanced Students Card - Top) -->
        <a href="{{ route('sections.index') }}" class="relative overflow-hidden bg-gradient-to-r from-indigo-50/30 via-white to-white p-3.5 rounded-xl border border-slate-100 shadow-[0_4px_20px_rgba(79,70,229,0.01)] hover:border-indigo-200 hover:shadow-[0_8px_30px_rgba(79,70,229,0.04)] transition-all duration-300 group flex items-center justify-between block cursor-pointer">
            <!-- Subtle Background Blur Decorative Spot -->
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-indigo-100/40 rounded-full blur-xl pointer-events-none"></div>

            <div class="flex items-center gap-3 relative z-10">
                <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-lg flex items-center justify-center text-sm shadow-[0_4px_12px_rgba(79,70,229,0.15)] group-hover:scale-105 transition-transform duration-300">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h3 class="text-slate-400 text-[8px] font-extrabold uppercase tracking-wider mb-0.5">Students</h3>
                    <div class="flex items-baseline gap-1.5">
                        <p class="text-base font-extrabold text-slate-800 leading-none tracking-tight">{{ number_format($students->count()) }}</p>
                        <span class="text-emerald-600 text-[8px] font-extrabold flex items-center gap-0.5">
                            +{{ $newAdmissions->count() }} <i class="bi bi-arrow-up text-[8px]"></i>
                        </span>
                    </div>
                </div>
            </div>
            
            <span class="relative z-10 bg-indigo-50/70 text-indigo-700 text-[8px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md border border-indigo-100/50">
                Active
            </span>
        </a>

        <!-- Reserved Graph Placeholder (Middle Section - Analytics Module) -->
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center items-center min-h-[200px] relative overflow-hidden group">
            <!-- Decorative subtle mesh background pattern -->
            <div class="absolute inset-0 pointer-events-none opacity-5">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMSIgZmlsbD0iIzAwMCIvPjwvc3ZnPg==')] bg-repeat"></div>
            </div>
            
            <div class="relative z-10 flex flex-col items-center text-center max-w-sm my-auto">
                <div class="w-10 h-10 bg-slate-50 border border-dashed border-slate-200 text-slate-400 rounded-xl flex items-center justify-center mb-2.5 group-hover:scale-105 group-hover:border-slate-300 transition-all duration-300">
                    <i class="bi bi-bar-chart-line text-lg"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-700 mb-1">Analytics Module</h3>
                <p class="text-[11px] text-slate-400 font-medium leading-relaxed mb-2.5">
                    This section is reserved for future analytics and academic visual reporting.
                </p>
                <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-500 px-2.5 py-0.5 rounded-md text-[8px] font-bold border border-slate-100 uppercase tracking-widest">
                    <i class="bi bi-clock-history"></i> Coming Soon
                </span>
            </div>
        </div>

        <!-- Footer Navigation Row (3 Small Boxes in a Single Row Grid - Well Below Analytics) -->
        <div class="pt-4 grid grid-cols-3 gap-2.5 w-full">
            <!-- Attendance Box -->
            <a href="{{ route('attendance.summary') }}" class="group flex flex-col sm:flex-row items-center justify-center gap-1.5 p-2.5 rounded-xl bg-white border border-slate-100 shadow-xs hover:border-emerald-200 hover:shadow-sm transition-all duration-300 hover:-translate-y-0.5 text-center">
                <i class="bi bi-person-check text-base sm:text-lg text-emerald-600 group-hover:text-emerald-500 transition-colors shrink-0"></i>
                <span class="text-[10px] sm:text-xs font-extrabold text-slate-700 group-hover:text-emerald-700 transition-colors truncate">Attendance</span>
            </a>

            <!-- Assessment Box -->
            <a href="{{ route('tests.index') }}" class="group flex flex-col sm:flex-row items-center justify-center gap-1.5 p-2.5 rounded-xl bg-white border border-slate-100 shadow-xs hover:border-amber-200 hover:shadow-sm transition-all duration-300 hover:-translate-y-0.5 text-center">
                <i class="bi bi-journal-check text-base sm:text-lg text-amber-600 group-hover:text-amber-500 transition-colors shrink-0"></i>
                <span class="text-[10px] sm:text-xs font-extrabold text-slate-700 group-hover:text-amber-700 transition-colors truncate">Assessment</span>
            </a>

            <!-- Finance Box -->
            <a href="{{ route('finance.index') }}" class="group flex flex-col sm:flex-row items-center justify-center gap-1.5 p-2.5 rounded-xl bg-white border border-slate-100 shadow-xs hover:border-indigo-200 hover:shadow-sm transition-all duration-300 hover:-translate-y-0.5 text-center">
                <i class="bi bi-wallet2 text-base sm:text-lg text-indigo-600 group-hover:text-indigo-500 transition-colors shrink-0"></i>
                <span class="text-[10px] sm:text-xs font-extrabold text-slate-700 group-hover:text-indigo-700 transition-colors truncate">Finance</span>
            </a>
        </div>
    </div>
@endsection
