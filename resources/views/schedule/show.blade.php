@extends('layouts.app')
@section('page-content')
    <style>
        .allocation-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .allocation-card:hover {
            transform: translateY(-5px);
        }
        .action-btn {
            @apply flex flex-col items-center justify-center p-4 rounded-xl transition-all duration-200;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 md:mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold md:font-extrabold text-slate-900 tracking-tight">My Schedule</h1>
                <div class="flex items-center mt-1 md:mt-2 text-xs md:text-sm text-slate-500 font-medium">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
                    <span class="mx-2 text-slate-300">/</span>
                    <span class="text-blue-600">Allocations</span>
                </div>
            </div>
            <div class="mt-3 md:mt-0 flex items-center bg-white px-3 py-1.5 md:px-4 md:py-2 rounded-lg border border-slate-200 shadow-sm">
                <i class="bi bi-calendar2-week text-blue-500 mr-2 text-base md:text-lg"></i>
                <span class="text-slate-700 text-xs md:text-sm font-semibold md:font-bold">{{ $schedules->count() }} Total Allocations</span>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors' />
        @else
            <x-message />
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">
            @foreach ($schedules->sortBy('lecture_no') as $schedule)
                <div class="allocation-card bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl overflow-hidden group">
                    <!-- Card Top: Subject & Class -->
                    <div class="p-4 md:p-6 pb-3 md:pb-4 border-b border-slate-50">
                        <div class="flex justify-between items-start mb-1 md:mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 uppercase tracking-wider">
                                Period {{ $schedule->lecture_no }}
                            </span>
                        </div>
                        <h3 class="text-lg md:text-xl font-semibold md:font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                            {{ $schedule->subject?->name }}
                        </h3>
                        <div class="flex items-center mt-1 text-slate-500">
                             <i class="bi bi-door-open mr-2 opacity-70 text-xs md:text-sm"></i>
                             <span class="font-medium md:font-semibold text-xs md:text-sm">{{ $schedule->section->name }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 md:p-6 pt-4 md:pt-5">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 md:mb-4">Quick Actions</p>
                        
                        <div class="grid grid-cols-2 gap-3 md:gap-4">
                            <!-- Assessment Action -->
                            <a href="{{ route('tests.index') }}" 
                               class="flex flex-col items-center justify-center p-3 md:p-4 rounded-xl md:rounded-2xl bg-indigo-50/50 text-indigo-700 hover:bg-indigo-600 hover:text-white transition-all group/btn shadow-sm hover:shadow-md">
                                <div class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-lg md:rounded-xl bg-white group-hover/btn:bg-indigo-500/20 mb-2 md:mb-3 transition-colors">
                                    <i class="bi bi-award-fill text-xl md:text-2xl group-hover/btn:scale-110 transition-transform"></i>
                                </div>
                                <span class="text-[9px] md:text-xs font-bold uppercase tracking-tight">Assessment</span>
                            </a>

                            <!-- Lesson Plan Action -->
                            <a href="{{ route('lesson-plans.full-plan', [$schedule->section->grade, $schedule->subject]) }}" 
                               class="flex flex-col items-center justify-center p-3 md:p-4 rounded-xl md:rounded-2xl bg-emerald-50/50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-all group/btn shadow-sm hover:shadow-md">
                                <div class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-lg md:rounded-xl bg-white group-hover/btn:bg-emerald-500/20 mb-2 md:mb-3 transition-colors">
                                    <i class="bi bi-journal-richtext text-xl md:text-2xl group-hover/btn:scale-110 transition-transform"></i>
                                </div>
                                <span class="text-[9px] md:text-xs font-bold uppercase tracking-tight">Lesson Plan</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Card Footer: Room or Details -->
                    @if($schedule->room_no)
                    <div class="px-6 py-3 bg-slate-50/50 border-t border-slate-50 flex items-center text-slate-400">
                        <i class="bi bi-geo-alt mr-2 text-xs"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Room: {{ $schedule->room_no }}</span>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($schedules->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300 mt-8">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-calendar-x text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No Allocations Found</h3>
                <p class="text-slate-500 max-w-sm mx-auto mt-1">You haven't been assigned any teaching periods yet. Please contact the headmaster if this is an error.</p>
            </div>
        @endif
    </div>
@endsection
