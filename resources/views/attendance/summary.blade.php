@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-600 flex items-center justify-center text-white shadow-lg shadow-teal-100">
                    <i class="bi-calendar-check text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800 leading-tight">Attendance Summary</h1>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</p>
                </div>
            </div>

            <!-- Date Selector -->
            <form action="{{ route('attendance.filter') }}" method="post" id="form_filter">
                @csrf
                <input type="hidden" name="date" id="date" value="{{ $date }}">
                <input type="date" id='filter_date' value="{{ $date }}"
                    class="px-4 py-2 bg-white border border-slate-100 rounded-xl text-slate-700 font-bold text-xs focus:ring-4 focus:ring-teal-500/10 cursor-pointer transition-all">
            </form>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        @php
            $percentage = $overallAttendanceCount > 0 ? round(($overallPresenceCount / $overallAttendanceCount) * 100, 1) : 0;
            $markedPercentage = $sections->count() > 0 ? round(($sectionsMarked / $sections->count()) * 100) : 0;
        @endphp

        <!-- Key Metrics Cards (Minimal) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-teal-600 rounded-3xl p-6 text-white shadow-xl shadow-teal-100 flex items-center justify-between relative overflow-hidden">
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
                    <h2 class="text-3xl font-black text-slate-800">{{ $sectionsMarked }}<span class="text-slate-200">/{{ $sections->count() }}</span></h2>
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

        <!-- Section Grid (Minimal Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($sections as $section)
                @php
                    $isMarked = $section->attendanceCount > 0;
                    $todayPercentage = $section->totalStudents > 0 
                        ? round(($section->presenceCount / $section->totalStudents) * 100, 1) 
                        : 0;
                    
                    $displayPercentage = $isMarked ? $todayPercentage : ($section->averageAttendance() ?? 0);
                    
                    $themeColor = $displayPercentage >= 90 ? 'teal' : 
                        ($displayPercentage >= 75 ? 'cyan' : 'rose');
                @endphp
                
                <a href="@if ($isMarked) {{ route('section.attendance.index', $section) }} @elseif(\Carbon\Carbon::parse($date)->isToday()){{ route('section.attendance.create', $section) }} @else {{ route('section.attendance.index', $section) }} @endif"
                   class="group bg-white rounded-2xl border border-slate-100 p-5 hover:border-{{ $themeColor }}-300 hover:shadow-lg hover:shadow-slate-100 transition-all duration-300">
                    
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 group-hover:text-{{ $themeColor }}-700 transition-colors leading-none mb-1">{{ $section->name }}</h3>
                            <div class="flex items-center gap-1.5">
                                @if(!$isMarked)
                                    <span class="text-[9px] font-black text-orange-500 uppercase tracking-tighter">Awaiting</span>
                                @else
                                    <span class="text-[9px] font-black text-teal-500 uppercase tracking-tighter">Marked</span>
                                @endif
                                <span class="w-0.5 h-0.5 rounded-full bg-slate-300"></span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $isMarked ? 'Today' : 'Avg' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                             <span class="text-lg font-black text-slate-800 tracking-tighter">{{ $displayPercentage }}%</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-end justify-between">
                            <div class="flex flex-col">
                                <div class="flex items-baseline gap-1 leading-none">
                                    <span class="text-sm font-black text-slate-700">{{ $isMarked ? $section->presenceCount : '-' }}</span>
                                    <span class="text-[10px] font-bold text-slate-300">/ {{ $section->totalStudents }}</span>
                                </div>
                            </div>
                            
                            @if($isMarked)
                                <div class="flex items-center gap-0.5 {{ $todayPercentage > $section->averageAttendance() ? 'text-teal-600' : 'text-rose-600' }}">
                                    <i class="bi-{{ $todayPercentage > $section->averageAttendance() ? 'arrow-up-short' : 'arrow-down-short' }} text-base"></i>
                                    <span class="text-[9px] font-black uppercase tracking-tighter">{{ $todayPercentage > $section->averageAttendance() ? '+' : '-' }}{{ abs($todayPercentage - $section->averageAttendance()) }}%</span>
                                </div>
                            @endif
                        </div>

                        <!-- Minimal Progress Bar -->
                        <div class="w-full h-1 bg-slate-50 rounded-full overflow-hidden">
                            <div class="h-full bg-{{ $themeColor }}-500 transition-all duration-700"
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
