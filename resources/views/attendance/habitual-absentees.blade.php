@extends('layouts.app')

@section('page-content')
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <h2><i class="ri-user-star-line"></i> Habitual Absentees</h2>
            <div class="bread-crumb">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <a href="{{ route('attendance.summary') }}">Attendance</a>
                <div>/</div>
                <div>Habitual Absentees</div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('attendance.summary') }}"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:shadow-md">Back
                to Summary</a>
            <a href="{{ route('attendance.habitual-absentees.pdf') }}" target="_blank"
                class="rounded-xl bg-gradient-to-r from-slate-900 via-cyan-900 to-teal-700 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-cyan-900/20 transition-all hover:scale-[1.02]">
                <i class="bi bi-printer mr-1"></i> Print PDF
            </a>
        </div>
    </div>

    <div
        class="mt-6 overflow-hidden rounded-[28px] bg-gradient-to-br from-slate-950 via-slate-900 to-teal-900 text-white shadow-2xl shadow-slate-900/20">
        <div class="grid gap-6 px-6 py-7 md:grid-cols-[2fr,1fr] md:px-10 md:py-9">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-cyan-200/80">Attendance Intelligence</p>
                <h3 class="mt-3 text-2xl font-bold leading-tight md:text-4xl">Top 3 habitual absentees from each class.
                </h3>
                <p class="mt-3 max-w-2xl text-sm text-slate-200/90 md:text-base">
                    Session window: {{ $sessionStart->format('d M Y') }} to {{ $reportDate->format('d M Y') }}.
                    Students are ranked by total absences, then absence rate within the marked attendance days.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 md:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-4 backdrop-blur-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-cyan-100/70">Classes covered</p>
                    <p class="mt-2 text-3xl font-bold">{{ $sectionsReport->count() }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-4 backdrop-blur-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-cyan-100/70">Classes flagged</p>
                    <p class="mt-2 text-3xl font-bold">{{ $classesWithAbsentees }}</p>
                </div>
                <div
                    class="col-span-2 rounded-2xl border border-white/10 bg-white/10 px-4 py-4 backdrop-blur-sm md:col-span-1">
                    <p class="text-xs uppercase tracking-[0.25em] text-cyan-100/70">Students highlighted</p>
                    <p class="mt-2 text-3xl font-bold">{{ $highlightedStudents }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid gap-5 xl:grid-cols-2">
        @foreach ($sectionsReport as $item)
            <section class="overflow-hidden rounded-[26px] border border-slate-200 bg-white shadow-lg shadow-slate-200/60">
                <div class="border-b border-slate-100 bg-gradient-to-r from-amber-50 via-white to-cyan-50 px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Class</p>
                            <h3 class="mt-2 text-xl font-bold text-slate-900">{{ $item['class_label'] }}</h3>
                        </div>
                        <div class="min-w-[120px] rounded-2xl bg-slate-900 px-4 py-3 text-right text-white">
                            <p class="text-[11px] uppercase tracking-[0.24em] text-cyan-100/70">Top students</p>
                            <p class="mt-1 text-2xl font-bold">{{ $item['students']->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if ($item['students']->isEmpty())
                        <div
                            class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center text-slate-500">
                            No recorded absences for this class in the selected session window.
                        </div>
                    @else
                        <div class="grid gap-4">
                            @foreach ($item['students'] as $index => $student)
                                <article
                                    class="rounded-[22px] border border-slate-100 bg-gradient-to-br from-white to-slate-50 px-5 py-5 shadow-sm">
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-lg font-bold text-white shadow-lg shadow-orange-200">
                                                {{ $index + 1 }}
                                            </div>
                                            <div>
                                                <p class="text-lg font-bold text-slate-900">{{ $student->name }}</p>
                                                <p class="text-sm text-slate-500">S/O {{ $student->father_name ?: 'N/A' }}
                                                </p>
                                                <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
                                                    <span
                                                        class="rounded-full bg-red-50 px-3 py-1 text-red-700">{{ $student->absence_count }}
                                                        absences</span>
                                                    <span
                                                        class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">{{ $student->absence_rate }}%
                                                        absence rate</span>
                                                    <span class="rounded-full bg-cyan-50 px-3 py-1 text-cyan-800">Roll #
                                                        {{ $student->rollno ?: 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid gap-3 text-sm text-slate-600 sm:grid-cols-2 lg:min-w-[320px]">
                                            <div>
                                                <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">Phone</p>
                                                <p class="mt-1 font-semibold text-slate-800">
                                                    {{ $student->phone ?: 'Not provided' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">Attendance
                                                    marks</p>
                                                <p class="mt-1 font-semibold text-slate-800">
                                                    {{ $student->attendance_count }} days</p>
                                            </div>
                                            <div>
                                                <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">Class</p>
                                                <p class="mt-1 font-semibold text-slate-800">{{ $item['class_label'] }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">Report
                                                    date</p>
                                                <p class="mt-1 font-semibold text-slate-800">
                                                    {{ $reportDate->format('d M Y') }}</p>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <p class="text-[11px] uppercase tracking-[0.22em] text-slate-400">Address
                                                </p>
                                                <p class="mt-1 font-semibold text-slate-800">
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
@endsection
