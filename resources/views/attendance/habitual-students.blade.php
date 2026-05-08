@extends('layouts.app')

@section('page-content')
    <div class="space-y-6 overflow-x-hidden">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-slate-800"><i class="ri-user-star-line text-teal-600"></i> Habitual Students</h2>
                <div class="bread-crumb mt-1">
                    <a href="{{ url('/') }}">Home</a>
                    <div>/</div>
                    <a href="{{ route('attendance.summary') }}">Attendance</a>
                    <div>/</div>
                    <div class="text-teal-600">Habitual Students</div>
                </div>
            </div>

            <div class="flex w-full flex-wrap gap-3 md:w-auto md:justify-end">
                <a href="{{ route('attendance.summary') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 shadow-sm transition-all hover:bg-slate-50">
                    Back to Summary
                </a>
                <a href="{{ route('attendance.habitual-students.pdf') }}" target="_blank"
                    class="rounded-xl border border-teal-100 bg-teal-50 px-4 py-2 text-xs font-bold text-teal-700 shadow-sm transition-all hover:bg-teal-100">
                    <i class="bi bi-printer mr-1"></i> Print PDF
                </a>
            </div>
        </div>

        <!-- Summary Bar -->
        <div class="flex flex-wrap items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-3 pr-4 border-r border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Reporting Window</span>
                <span class="text-xs font-bold text-slate-700">{{ $sessionStart->format('d M Y') }} - {{ $reportDate->format('d M Y') }}</span>
            </div>
            <div class="flex items-center gap-3 pr-4 border-r border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Top Students</span>
                <span class="text-xs font-bold text-teal-600">{{ $highlightedStudents }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Absences</span>
                <span class="text-xs font-bold text-rose-500">{{ $totalAbsences }}</span>
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
                                                            <h4 class="text-sm font-bold text-slate-900 leading-tight">{{ $student->name }}</h4>
                                                            <p class="text-[11px] text-slate-500 font-medium">S/O {{ $student->father_name ?: '---' }} • Roll: {{ $student->rollno ?: 'N/A' }}</p>
                                                        </div>
                                                        <div class="flex gap-2">
                                                            <div class="flex flex-col items-end">
                                                                <span class="text-xs font-bold text-rose-600">{{ $student->absence_count }} Absences</span>
                                                                <span class="text-[10px] font-bold text-slate-400">{{ $student->absence_rate }}% Rate</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-1">
                                                        <div class="flex items-center gap-2">
                                                            <i class="bi bi-telephone text-[10px] text-slate-400"></i>
                                                            <span class="text-[11px] font-bold text-slate-600">{{ $student->phone ?: 'No Phone' }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <i class="bi bi-geo-alt text-[10px] text-slate-400"></i>
                                                            <span class="text-[11px] font-bold text-slate-600 truncate">{{ $student->address ?: 'No Address' }}</span>
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
