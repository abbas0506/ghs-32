@extends('layouts.app')

@section('page-content')
    <div class="space-y-6 overflow-x-hidden">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0">
                <h2><i class="ri-user-star-line"></i> Habitual Students</h2>
                <div class="bread-crumb">
                    <a href="{{ url('/') }}">Home</a>
                    <div>/</div>
                    <a href="{{ route('attendance.summary') }}">Attendance</a>
                    <div>/</div>
                    <div>Habitual Students</div>
                </div>
            </div>

            <div class="flex w-full flex-wrap gap-3 md:w-auto md:justify-end">
                <a href="{{ route('attendance.summary') }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:shadow-md">
                    Back to Summary
                </a>
                <a href="{{ route('attendance.habitual-students.pdf') }}" target="_blank"
                    class="rounded-xl border border-teal-100 bg-teal-50 px-4 py-2.5 text-sm font-semibold text-teal-700 shadow-sm transition-all hover:border-teal-200 hover:bg-teal-100">
                    <i class="bi bi-printer mr-1"></i> Print PDF
                </a>
            </div>
        </div>

        <section class="overflow-hidden rounded-[28px] border border-teal-100 bg-white shadow-sm shadow-slate-200/70">
            <div class="border-b border-slate-100 bg-gradient-to-r from-teal-50 via-white to-cyan-50 px-6 py-6 md:px-8">
                <div class="grid gap-6 lg:grid-cols-[1.8fr,1fr] lg:items-end">
                    <div class="min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-[0.35em] text-teal-600">Attendance Intelligence
                        </p>
                        <h1 class="mt-3 text-2xl font-black leading-tight text-slate-900 md:text-4xl">Top 3 habitual
                            students from each class</h1>
                        <p class="mt-3 max-w-2xl text-sm text-slate-600 md:text-base">
                            Ranked from {{ $sessionStart->format('d M Y') }} to {{ $reportDate->format('d M Y') }} using
                            total absences first,
                            then absence rate across marked attendance days, with key contact details ready for follow-up.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-1">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                            <p class="text-[11px] font-black uppercase tracking-[0.24em] text-slate-400">Students listed</p>
                            <p class="mt-2 text-3xl font-black text-slate-900">{{ $highlightedStudents }}</p>
                        </div>
                        <div class="rounded-2xl border border-teal-100 bg-teal-50 px-4 py-4">
                            <p class="text-[11px] font-black uppercase tracking-[0.24em] text-teal-700">Classes covered</p>
                            <p class="mt-2 text-3xl font-black text-teal-900">{{ $sectionsCount }}</p>
                        </div>
                        <div class="col-span-2 rounded-2xl border border-amber-100 bg-amber-50 px-4 py-4 md:col-span-1">
                            <p class="text-[11px] font-black uppercase tracking-[0.24em] text-amber-700">Classes flagged</p>
                            <p class="mt-2 text-3xl font-black text-amber-900">{{ $classesWithStudents }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 px-6 py-5 md:grid-cols-3 md:px-8">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-slate-400">Reporting window</p>
                    <p class="mt-2 text-sm font-bold text-slate-800 md:text-base">{{ $sessionStart->format('d M Y') }} -
                        {{ $reportDate->format('d M Y') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-slate-400">Students with marked
                        attendance</p>
                    <p class="mt-2 text-2xl font-black text-slate-900">{{ $studentsWithAttendance }}</p>
                </div>
                <div class="rounded-2xl border border-rose-100 bg-rose-50 px-4 py-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.28em] text-rose-600">Absences in highlighted
                        students</p>
                    <p class="mt-2 text-2xl font-black text-rose-900">{{ $totalAbsences }}</p>
                </div>
            </div>
        </section>

        @if ($highlightedStudents === 0)
            <div class="rounded-[28px] border border-dashed border-slate-300 bg-white px-6 py-16 text-center shadow-sm">
                <p class="text-sm font-black uppercase tracking-[0.3em] text-slate-400">No habitual students found</p>
                <p class="mt-3 text-base font-semibold text-slate-600">No student has recorded absences in the current
                    reporting window.</p>
            </div>
        @else
            <div class="grid gap-5 2xl:grid-cols-2">
                @foreach ($sectionsReport as $item)
                    <section
                        class="overflow-hidden rounded-[26px] border border-slate-200 bg-white shadow-lg shadow-slate-200/60">
                        <div
                            class="border-b border-slate-100 bg-gradient-to-r from-amber-50 via-white to-cyan-50 px-6 py-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Class</p>
                                    <h3 class="mt-2 break-words text-xl font-black text-slate-900">
                                        {{ $item['class_label'] }}</h3>
                                </div>
                                <div
                                    class="min-w-[120px] rounded-2xl border border-teal-100 bg-teal-50 px-4 py-3 text-right">
                                    <p class="text-[11px] uppercase tracking-[0.24em] text-teal-700">Top students</p>
                                    <p class="mt-1 text-2xl font-black text-teal-900">{{ $item['students']->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            @if ($item['students']->isEmpty())
                                <div
                                    class="rounded-[24px] border border-dashed border-slate-200 bg-slate-50 px-5 py-10 text-center text-sm font-semibold text-slate-500">
                                    No student with recorded absences in this class during the selected window.
                                </div>
                            @else
                                <div class="grid gap-4">
                                    @foreach ($item['students'] as $index => $student)
                                        <article
                                            class="overflow-hidden rounded-[22px] border border-slate-100 bg-gradient-to-br from-white to-slate-50 px-5 py-5 shadow-sm">
                                            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-start gap-4">
                                                        <div
                                                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-lg font-black text-white shadow-lg shadow-orange-200">
                                                            {{ $index + 1 }}
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p
                                                                class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                                                                Student</p>
                                                            <p
                                                                class="mt-1 break-words text-lg font-black leading-tight text-slate-900 md:text-xl">
                                                                {{ $student->name }}</p>
                                                            <p class="mt-1 break-words text-sm text-slate-500">S/O
                                                                {{ $student->father_name ?: 'Not provided' }}</p>
                                                            <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
                                                                <span
                                                                    class="rounded-full bg-red-50 px-3 py-1 text-red-700">{{ $student->absence_count }}
                                                                    absences</span>
                                                                <span
                                                                    class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">{{ $student->absence_rate }}%
                                                                    absence rate</span>
                                                                <span
                                                                    class="rounded-full bg-cyan-50 px-3 py-1 text-cyan-800">Roll
                                                                    {{ $student->rollno ?: 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="grid min-w-0 gap-3 text-sm text-slate-600 sm:grid-cols-2 xl:w-[280px] xl:flex-none 2xl:w-[320px]">
                                                    <div class="min-w-0">
                                                        <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">
                                                            Phone</p>
                                                        <p class="mt-1 break-all font-semibold text-slate-800">
                                                            {{ $student->phone ?: 'Not provided' }}</p>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">
                                                            Attendance marks</p>
                                                        <p class="mt-1 font-semibold text-slate-800">
                                                            {{ $student->attendance_count }} days</p>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">
                                                            Class</p>
                                                        <p class="mt-1 break-words font-semibold text-slate-800">
                                                            {{ $item['class_label'] }}</p>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">
                                                            Report date</p>
                                                        <p class="mt-1 font-semibold text-slate-800">
                                                            {{ $reportDate->format('d M Y') }}</p>
                                                    </div>
                                                    <div class="min-w-0 sm:col-span-2">
                                                        <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">
                                                            Address</p>
                                                        <p
                                                            class="mt-1 break-all font-semibold leading-relaxed text-slate-800">
                                                            {{ $student->address ?: 'Not provided' }}</p>
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
