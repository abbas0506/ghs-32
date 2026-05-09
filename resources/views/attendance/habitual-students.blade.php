@extends('layouts.app')

@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3">
                    <a href="{{ route('attendance.summary') }}" class="hover:text-teal-600 transition-colors">Attendance Center</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Habitual Students</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center shadow-lg shadow-rose-100">
                        <i class="bi-stars text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 leading-none mb-1">Habitual Students</h1>
                        <p class="text-slate-400 text-[10px] font-medium italic">Absence rate above 75%</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('attendance.summary') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-[10px] font-bold text-slate-600 shadow-sm transition-all hover:bg-slate-50 uppercase tracking-widest">
                    Back
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Reporting Window Card -->
            <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Reporting Window</p>
                    <div class="flex flex-wrap items-center gap-2">
                         <span class="text-xs font-bold text-slate-700">{{ $sessionStart->format('d M Y') }} - {{ $reportDate->format('d M Y') }}</span>
                         <span class="text-[10px] font-bold text-teal-600 bg-teal-50 px-2 py-0.5 rounded-full uppercase tracking-tighter">{{ $highlightedStudents }} Students Found</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                    <i class="bi bi-calendar3"></i>
                </div>
            </div>

            <!-- Print Action Card -->
            <div class="bg-slate-900 rounded-2xl p-4 shadow-xl shadow-slate-100 flex items-center justify-between text-white">
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Report Generation</p>
                    <h3 class="text-[11px] font-bold text-slate-100">Export detailed PDF report</h3>
                </div>
                <a href="{{ route('attendance.habitual-students.pdf') }}" target="_blank"
                    class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all backdrop-blur-sm border border-white/10 flex items-center gap-2">
                    <i class="bi bi-printer-fill"></i> Print
                </a>
            </div>
        </div>

        @if ($highlightedStudents === 0)
            <div class="rounded-[28px] border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-slate-400">No habitual students found</p>
                <p class="mt-3 text-sm font-semibold text-slate-600">No student has recorded absences in the current reporting window.</p>
            </div>
        @else
            <div class="grid gap-6 2xl:grid-cols-2">
                @foreach ($sectionsReport as $item)
                    <section class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
                        <div class="border-b border-slate-50 bg-slate-50/50 px-6 py-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Class {{ $item['class_label'] }}</h3>
                                <span class="rounded-full bg-teal-100 px-3 py-1 text-[10px] font-bold text-teal-700 uppercase">{{ $item['students']->count() }} Students</span>
                            </div>
                        </div>

                        <div class="p-4">
                            @if ($item['students']->isEmpty())
                                <div class="px-5 py-8 text-center text-[11px] font-bold text-slate-400">
                                    No student with recorded absences in this class.
                                </div>
                            @else
                                <div class="divide-y divide-slate-50">
                                    @foreach ($item['students'] as $index => $student)
                                        <article class="group py-4 first:pt-0 last:pb-0">
                                            <div class="flex items-start gap-4">
                                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-500 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                                                    {{ $index + 1 }}
                                                </div>
                                                
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                                        <div>
                                                            <h4 class="text-xs md:text-sm font-bold text-slate-900 leading-tight">{{ $student->name }}</h4>
                                                            <p class="text-[10px] md:text-[11px] text-slate-500 font-medium">S/O {{ $student->father_name ?: '---' }} • Roll: {{ $student->rollno ?: 'N/A' }}</p>
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <div class="flex flex-col items-end">
                                                                <span class="text-[10px] md:text-xs font-bold text-rose-600">{{ $student->absence_count }} Absences</span>
                                                                <span class="text-[9px] md:text-[10px] font-bold text-slate-400">{{ $student->absence_rate }}% Rate</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-1">
                                                        <div class="flex items-center gap-2">
                                                            <i class="bi bi-telephone text-[10px] text-slate-400"></i>
                                                            <span class="text-[10px] md:text-[11px] font-bold text-slate-600">{{ $student->phone ?: 'No Phone' }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <i class="bi bi-geo-alt text-[10px] text-slate-400"></i>
                                                            <span class="text-[10px] md:text-[11px] font-bold text-slate-600 truncate">{{ $student->address ?: 'No Address' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </section>
                @endforeach
            </div>
        @endif
    </div>
@endsection
