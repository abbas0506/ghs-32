@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-teal-600 flex items-center justify-center text-white shadow-lg shadow-teal-100 shrink-0">
                    <i class="bi-calendar-check text-xl"></i>
                </div>
                <div class="overflow-hidden">
                    <h1 class="text-xl font-bold text-slate-800 leading-tight truncate">Attendance Summary</h1>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest truncate">
                        {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto shrink-0">
                <a href="{{ route('attendance.analytics') }}"
                    class="w-full md:w-auto px-5 py-2.5 bg-teal-50 text-teal-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-teal-100 hover:text-teal-700 transition-all shadow-sm flex items-center justify-center gap-2 border border-teal-100">
                    <i class="bi-bar-chart-fill text-sm"></i> Analytics View
                </a>
                <a href="{{ route('attendance.habitual-students') }}"
                    class="w-full md:w-auto px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-sm flex items-center justify-center gap-2 border border-slate-900">
                    <i class="bi-stars text-sm"></i> Top Habitual Students
                </a>
                <form action="{{ route('attendance.filter') }}" method="post" id="form_filter"
                    class="w-full md:w-auto shrink-0">
                    @csrf
                    <input type="hidden" name="date" id="date" value="{{ $date }}">
                    <input type="date" id='filter_date' value="{{ $date }}"
                        class="w-full md:w-auto px-4 py-2.5 bg-white border border-slate-100 rounded-xl text-slate-700 font-bold text-xs focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-300 cursor-pointer transition-all shadow-sm">
                </form>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        @php
            $percentage =
                $overallAttendanceCount > 0 ? round(($overallPresenceCount / $overallAttendanceCount) * 100, 1) : 0;
            $markedPercentage = $sections->count() > 0 ? round(($sectionsMarked / $sections->count()) * 100) : 0;
        @endphp

        <!-- Key Metrics Cards (Minimal) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-teal-600 rounded-3xl p-6 text-white shadow-xl shadow-teal-100 flex items-center justify-between relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-teal-100 uppercase tracking-widest mb-1">Total Presence</p>
                    <h2 class="text-3xl font-black text-white">{{ $percentage }}%</h2>
                </div>
                <div class="text-right relative z-10">
                    <p class="text-lg font-bold leading-none">{{ number_format($overallPresenceCount) }}</p>
                    <p class="text-[10px] font-bold text-teal-100 uppercase tracking-tighter">Students</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Reporting Status</p>
                    <h2 class="text-3xl font-black text-slate-800">{{ $sectionsMarked }}<span
                            class="text-slate-200">/{{ $sections->count() }}</span></h2>
                </div>
                <div class="w-12 h-12 rounded-full border-4 border-slate-50 flex items-center justify-center">
                    <span class="text-xs font-black text-slate-400">{{ $markedPercentage }}%</span>
                </div>
            </div>

            <div class="bg-teal-50 rounded-3xl p-6 border border-teal-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white text-teal-600 flex items-center justify-center shadow-sm">
                    <i class="bi-lightning-fill"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-teal-700 uppercase tracking-widest mb-0.5">Quick Insight</p>
                    <p class="text-sm font-bold text-teal-900 leading-tight">
                        {{ $percentage >= 90 ? 'High stability today' : ($percentage >= 75 ? 'Healthy participation' : 'Noticeable absences today') }}
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('attendance.habitual-students') }}"
            class="group block overflow-hidden rounded-[28px] bg-gradient-to-r from-slate-950 via-slate-900 to-teal-900 p-[1px] shadow-xl shadow-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-slate-300/70">
            <div
                class="rounded-[27px] bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.18),_transparent_34%),linear-gradient(135deg,_#020617_0%,_#0f172a_55%,_#115e59_100%)] px-6 py-6 text-white md:px-8">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-cyan-200/80">Premium Attendance
                            Insight</p>
                        <h2 class="mt-3 text-2xl font-black leading-tight md:text-3xl">Review the top 3 most habitual
                            students with full contact details.</h2>
                        <p class="mt-3 text-sm font-medium leading-relaxed text-slate-200/90">Open the dedicated page for
                            ranked students, polished profile cards, and a compact printable PDF.</p>
                    </div>

                    <div class="flex items-center gap-4 self-start md:self-center">
                        <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-4 text-center backdrop-blur-sm">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-cyan-100/80">Shortcut</p>
                            <p class="mt-2 text-lg font-black">Top 3</p>
                        </div>
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-slate-900 shadow-lg shadow-black/20 transition-transform duration-300 group-hover:scale-105">
                            <i class="bi-arrow-up-right text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>



        <!-- Section Grid (Minimal Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($sections as $section)
                @php
                    $isMarked = $section->attendanceCount > 0;
                    $todayPercentage =
                        $section->totalStudents > 0
                            ? round(($section->presenceCount / $section->totalStudents) * 100, 1)
                            : 0;

                    $displayPercentage = $todayPercentage;
                    $avgPercentage = round($section->averageAttendance() ?? 0, 1);

                    if (!$isMarked) {
                        $borderClass = 'hover:border-slate-300';
                        $textClass = 'group-hover:text-slate-700';
                        $bgClass = 'bg-slate-500';
                    } elseif ($displayPercentage >= 90) {
                        $borderClass = 'hover:border-teal-300';
                        $textClass = 'group-hover:text-teal-700';
                        $bgClass = 'bg-teal-500';
                    } elseif ($displayPercentage >= 75) {
                        $borderClass = 'hover:border-cyan-300';
                        $textClass = 'group-hover:text-cyan-700';
                        $bgClass = 'bg-cyan-500';
                    } else {
                        $borderClass = 'hover:border-rose-300';
                        $textClass = 'group-hover:text-rose-700';
                        $bgClass = 'bg-rose-500';
                    }
                @endphp

                <a href="@if ($isMarked) {{ route('section.attendance.index', $section) }} @elseif(\Carbon\Carbon::parse($date)->isToday()){{ route('section.attendance.create', $section) }} @else {{ route('section.attendance.index', $section) }} @endif"
                    class="group bg-white rounded-2xl border border-slate-100 p-5 {{ $borderClass }} hover:shadow-lg hover:shadow-slate-100 transition-all duration-300">

                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 {{ $textClass }} transition-colors leading-none mb-1">
                                {{ $section->name }}</h3>
                            <div class="flex items-center gap-1.5">
                                @if (!$isMarked)
                                    <span
                                        class="text-[9px] font-black text-orange-500 uppercase tracking-tighter">Awaiting</span>
                                @else
                                    <span
                                        class="text-[9px] font-black text-teal-500 uppercase tracking-tighter">Marked</span>
                                @endif
                                <span class="w-0.5 h-0.5 rounded-full bg-slate-300"></span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Avg
                                    {{ $avgPercentage }}%</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="text-lg font-black text-slate-800 tracking-tighter">{{ $displayPercentage }}%</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-end justify-between">
                            <div class="flex flex-col">
                                <div class="flex items-baseline gap-1 leading-none">
                                    <span
                                        class="text-sm font-black text-slate-700">{{ $isMarked ? $section->presenceCount : '-' }}</span>
                                    <span class="text-[10px] font-bold text-slate-300">/
                                        {{ $section->totalStudents }}</span>
                                </div>
                            </div>

                            @if ($isMarked)
                                <div
                                    class="flex items-center gap-0.5 {{ $todayPercentage > $section->averageAttendance() ? 'text-teal-600' : 'text-rose-600' }}">
                                    <i
                                        class="bi-{{ $todayPercentage > $section->averageAttendance() ? 'arrow-up-short' : 'arrow-down-short' }} text-base"></i>
                                    <span
                                        class="text-[9px] font-black uppercase tracking-tighter">{{ $todayPercentage > $section->averageAttendance() ? '+' : '-' }}{{ abs($todayPercentage - $section->averageAttendance()) }}%</span>
                                </div>
                            @endif
                        </div>

                        <!-- Minimal Progress Bar -->
                        <div class="w-full h-1 bg-slate-50 rounded-full overflow-hidden">
                            <div class="h-full {{ $bgClass }} transition-all duration-700"
                                style="width: {{ $displayPercentage }}%"></div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        $(document).ready(function() {
            $('#filter_date').on('change', function() {
                let selected = $(this).val();
                $('#date').val(selected);
                $('#form_filter').submit();
            });
        });
    </script>
@endsection
