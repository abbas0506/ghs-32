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
                    <h2 class="text-lg font-bold text-slate-800 leading-tight truncate">Attendance Summary</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate">
                        {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto shrink-0">
                <a href="{{ route('attendance.analytics') }}"
                    class="w-full md:w-auto px-5 py-2.5 bg-teal-50 text-teal-600 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-teal-100 hover:text-teal-700 transition-all shadow-sm flex items-center justify-center gap-2 border border-teal-100">
                    <i class="bi-bar-chart-fill text-sm"></i> 15-Days Analysis
                </a>
                <a href="{{ route('attendance.habitual-students') }}"
                    class="w-full md:w-auto px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-800 transition-all shadow-sm flex items-center justify-center gap-2 border border-slate-900">
                    <i class="bi-stars text-sm"></i> Habitually Absent
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
                class="bg-teal-600 rounded-[1rem] md:rounded-[1.5rem] p-5 text-white shadow-xl shadow-teal-100 flex items-center justify-between relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="relative z-10">
                    <p class="text-[9px] font-semibold text-teal-100 uppercase tracking-widest mb-1">Total Presence</p>
                    <p class="text-lg font-bold leading-none">{{ $overallPresenceCount }}<span class="text-teal-200 font-bold"> / {{ $overallAttendanceCount }}</span></p>
                </div>
                <div class="text-right relative z-10">
                    <h2 class="text-lg font-bold text-white">{{ $percentage }}%</h2>
                </div>
            </div>

            <div class="bg-white rounded-[1rem] md:rounded-[1.5rem] p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-widest mb-1">Reporting Status</p>
                    <h2 class="text-xl font-bold text-slate-800">{{ $sectionsMarked }}<span
                            class="text-slate-200"> /{{ $sections->count() }}</span></h2>
                </div>
                <div class="w-12 h-12 rounded-full border-4 border-slate-50 flex items-center justify-center">
                    <span class="text-xs font-bold text-slate-400">{{ $markedPercentage }}%</span>
                </div>
            </div>

            <div class="bg-teal-50 rounded-[1rem] md:rounded-[1.5rem] p-5 border border-teal-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white text-teal-600 flex items-center justify-center shadow-sm">
                    <i class="bi-lightning-fill"></i>
                </div>
                <div>
                    <p class="text-[9px] font-semibold text-teal-700 uppercase tracking-widest mb-0.5">Quick Insight</p>
                    <p class="text-sm font-bold text-teal-900 leading-tight">
                        {{ $percentage >= 90 ? 'High stability today' : ($percentage >= 75 ? 'Healthy participation' : 'Noticeable absences today') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Section Grid (Minimal Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($sections as $section)
                @php
                    $isMarked = $section->attendanceCount > 0;
                    $todayPercentage =
                        $section->totalStudents > 0
                            ? round(($section->presenceCount / $section->totalStudents) * 100, 1)
                            : 0;

                    $avgPercentage = round($section->averageAttendance() ?? 0, 1);
                    $isAboveAvg = $todayPercentage >= $avgPercentage;
                    $diff = abs($todayPercentage - $avgPercentage);

                    $statusColor = 'slate';
                    if ($isMarked) {
                        if ($todayPercentage >= 90) {
                            $statusColor = 'teal';
                        } elseif ($todayPercentage >= 75) {
                            $statusColor = 'blue';
                        } else {
                            $statusColor = 'rose';
                        }
                    }
                @endphp

                <a href="@if ($isMarked) {{ route('section.attendance.index', $section) }} @elseif(\Carbon\Carbon::parse($date)->isToday()){{ route('section.attendance.create', $section) }} @else {{ route('section.attendance.index', $section) }} @endif"
                    class="group bg-white rounded-[1rem] md:rounded-[1.5rem] border border-slate-100 p-5 hover:border-{{ $statusColor }}-200 hover:shadow-xl hover:shadow-{{ $statusColor }}-50/50 transition-all duration-300">
                    
                    <div class="flex items-center justify-between">
                        <!-- Left: Section Info -->
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <h3 class="text-[9px] font-black uppercase tracking-widest text-slate-800 group-hover:text-{{ $statusColor }}-600 transition-colors">
                                    {{ $section->name }}
                                </h3>
                                @if (!$isMarked)
                                    <span class="px-2 py-0.5 font-semibold rounded-full bg-orange-50 text-orange-500 text-[8px] uppercase tracking-tighter">Awaiting</span>
                                @else
                                    <i class="bi bi-check-circle-fill text-{{ $statusColor }}-500 text-xs"></i>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xs font-bold text-slate-600">{{ $isMarked ? $section->presenceCount : '-' }}</span>
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">/ {{ $section->totalStudents }}</span>
                                </div>
                                
                                @if ($isMarked)
                                    <div class="flex items-center gap-0.5 {{ $isAboveAvg ? 'text-teal-600' : 'text-rose-600' }}">
                                        <i class="bi-arrow-{{ $isAboveAvg ? 'up' : 'down' }}-short text-base leading-none"></i>
                                        <span class="text-[10px] font-bold tracking-tighter">
                                            {{ round($diff, 1) }}%
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Performance Indicator -->
                        <div class="flex flex-col items-end gap-1">
                            <div class="flex items-baseline gap-0.5">
                                <span class="text-sm font-bold text-slate-800 tracking-tight">{{ $todayPercentage }}</span>
                                <span class="text-sm font-bold text-slate-400">%</span>
                            </div>
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
