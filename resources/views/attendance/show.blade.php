@extends('layouts.app')
@section('page-content')
    <div class="space-y-8 pb-12">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 mb-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('section.attendance.index', $section) }}" class="hover:text-teal-600 transition-colors">Attendance Details</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">History</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-clock-history text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 leading-none mb-1">Attendance Record</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Detailed historical view of student absences</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto mt-4 md:mt-0">
                <a href="{{ route('attendance.student.analytics', ['section' => $section->id, 'attendance' => $attendance->id]) }}" class="w-full md:w-auto px-5 py-2.5 bg-teal-50 text-teal-600 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-teal-100 hover:text-teal-700 transition-all shadow-sm flex items-center justify-center gap-2 border border-teal-100">
                    <i class="bi-bar-chart-fill text-sm"></i> Analytics View
                </a>
                <a href="{{ route('section.attendance.index', $section) }}" class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-slate-600 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-50 border border-slate-200 transition-all shadow-sm">
                    <i class="bi-arrow-left text-sm"></i> Back to Roster
                </a>
            </div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="max-w-4xl mx-auto mt-6 space-y-8">
            <!-- Student Header Profile -->
            <div class="bg-white border border-slate-100 rounded-[2rem] shadow-xl shadow-slate-200/40 p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
                
                <div class="flex flex-col md:flex-row items-start md:items-center gap-5 relative z-10 w-full md:w-auto">
                    <!-- @php
                        $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', explode(' ', $student->name))));
                    @endphp
                    <div class="hidden md:flex w-16 h-16 rounded-[1.5rem] bg-teal-50 border border-teal-100 text-teal-600 items-center justify-center text-xl font-bold shadow-sm shrink-0">
                        {{ $initials }}
                    </div> -->
                    <div class="overflow-hidden w-full md:w-auto">
                        <div class="flex flex-wrap md:flex-nowrap items-center gap-2 md:gap-3 mb-1">
                            <h2 class="text-xl md:text-2xl font-bold text-slate-800 truncate">{{ $student->name }}</h2>
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-slate-200 whitespace-nowrap">Roll # {{ $student->rollno }}</span>
                        </div>
                        <p class="text-xs uppercase tracking-widest text-slate-400 font-bold flex items-center gap-2 mt-1 md:mt-0">
                            <i class="bi-person text-sm block md:inline"></i> {{ $student->father_name }}
                        </p>
                    </div>
                </div>
                
                <div class="h-10 w-px bg-slate-200 hidden lg:block shrink-0 relative z-10"></div>
                
                <div class="flex items-center gap-3 bg-white px-5 py-3 border border-slate-100 rounded-2xl shadow-sm w-full md:w-auto relative z-10 shrink-0">
                    <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 shrink-0">
                        <i class="bi-mortarboard"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-0.5">Enrolled Class</p>
                        <h3 class="text-sm font-bold text-slate-700 whitespace-nowrap">{{ $student->section->name }}</h3>
                    </div>
                </div>
            </div>

            <!-- Metrics Grid -->
            @php
                $trending = $monthAttendancePercentage >= $sessionAttendancePercentage ? 'up' : 'down';
                $trendColor = $trending === 'up' ? 'text-teal-400' : 'text-rose-400';
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Session Metric -->
                <div class="bg-teal-600 rounded-lg p-5 text-white shadow-xl shadow-teal-100 flex items-center justify-between relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-[2rem] rotate-12"></div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest mb-1.5">Total Session Attendance</p>
                        <div class="flex items-end gap-3">
                            <h2 class="text-3xl font-bold text-white leading-none">{{ $sessionAttendancePercentage }}%</h2>
                            <span class="text-white/60 text-[10px] font-bold uppercase tracking-widest mb-0.5 pb-px">Since {{ $sessionStart->format('M Y') }}</span>
                        </div>
                    </div>
                    <div class="relative z-10 w-12 h-12 rounded-full border-2 {{ $sessionAttendancePercentage >= 80 ? 'border-teal-400 text-teal-100' : 'border-rose-400 text-rose-100' }} flex items-center justify-center shadow-lg bg-teal-700/50">
                        <i class="bi-{{ $sessionAttendancePercentage >= 80 ? 'emoji-smile' : 'emoji-frown' }} text-xl"></i>
                    </div>
                </div>

                <!-- Monthly Metric -->
                <div class="bg-white rounded-lg p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Current Month Rate</p>
                        <div class="flex items-center gap-3">
                            <h2 class="text-3xl font-bold text-slate-800 leading-none">{{ $monthAttendancePercentage }}%</h2>
                            <span class="{{ $trendColor }} text-[10px] font-bold bg-slate-50 px-2 py-0.5 rounded border border-slate-100 flex items-center gap-1">
                                <i class="bi-graph-{{ $trending }}"></i> {{ ucfirst($trending) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Absence Data -->
            @if ($sessionAbsences->count())
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 md:px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="bi-calendar-x text-rose-500 text-xl"></i>
                            <h3 class="font-bold text-slate-800 text-sm">Absence Log</h3>
                        </div>
                        <span class="px-3 py-1 bg-rose-50 text-rose-600 text-[8px] md:text-[10px] font-bold uppercase tracking-widest rounded-full border border-rose-100">{{ $sessionAbsences->count() }} Entries</span>
                    </div>
                    
                    <div class="overflow-x-auto p-4 md:p-6">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr>
                                    <th class="w-10 md:w-16 px-2 md:px-4 py-2 md:py-3 text-center text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">#</th>
                                    <th class="px-2 md:px-4 py-2 md:py-3 text-left text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Date Logged</th>
                                    <th class="w-20 md:w-32 px-2 md:px-4 py-2 md:py-3 text-center text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($sessionAbsences->sortByDesc('date') as $attendance)
                                    <tr class="group hover:bg-slate-50 transition-colors duration-150">
                                        <td class="px-2 md:px-4 py-2 md:py-3 text-center">
                                            <div class="w-6 h-6 md:w-8 md:h-8 rounded-lg bg-slate-50 text-slate-400 font-bold text-[9px] md:text-xs flex items-center justify-center mx-auto border border-slate-100 group-hover:bg-white transition-colors">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td class="px-2 md:px-4 py-2 md:py-3">
                                            <div class="flex flex-col">
                                                <span class="text-xs md:text-sm font-bold text-slate-700">{{ $attendance->date->format('d M, Y') }}</span>
                                                <span class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $attendance->date->format('l') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-2 md:px-4 py-2 md:py-3 text-center">
                                            <span class="inline-block px-2 md:px-3 py-1 bg-rose-50 text-rose-600 text-[8px] md:text-[10px] font-bold uppercase tracking-widest rounded-full border border-rose-100 shadow-sm">
                                                Absent
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="mt-8 text-center py-16 bg-white rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-24 h-24 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi-award text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Perfect Attendance!</h3>
                    <p class="text-slate-500 font-medium max-w-sm mx-auto">Outstanding! {{ $student->name }} has zero recorded absences during this academic session.</p>
                </div>
            @endif

        </div>
    </div>
@endsection


