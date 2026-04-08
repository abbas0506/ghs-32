@extends('layouts.app')
@section('page-content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 pb-6 border-b border-slate-200">
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 uppercase tracking-widest mb-3">
                    Grade {{ $grade->grade_no }} • {{ $subject->name }}
                </span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Lesson Plan</h1>
                <p class="text-slate-500 mt-2 font-medium">Chronological roadmap of topics and objectives</p>
            </div>
            <div class="mt-6 md:mt-0">
                <a href="{{ route('user-schedule.show') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-xl font-bold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Back to Schedule
                </a>
            </div>
        </div>

        {{-- Timeline / Lessons List --}}
        <div class="relative">
            {{-- Vertical Line --}}
            <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-0.5 bg-slate-200 -translate-x-1/2 hidden md:block"></div>

            <div class="space-y-12">
                @forelse ($lessons as $index => $lesson)
                    <div class="relative flex flex-col md:flex-row items-center">
                        {{-- Timeline Dot --}}
                        <div class="absolute left-4 md:left-1/2 w-8 h-8 bg-emerald-500 border-4 border-white rounded-full -translate-x-1/2 z-10 shadow-md hidden md:block"></div>

                        {{-- Box Content --}}
                        <div class="w-full md:w-[45%] {{ $index % 2 == 0 ? 'md:mr-auto' : 'md:ml-auto' }} bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg transition-all p-6 relative">
                             <div class="flex items-center justify-between mb-4">
                                 <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">Lesson #{{ $lesson->lesson_no }}</span>
                             </div>

                             <h4 class="text-xl font-bold text-slate-800 mb-2">{{ $lesson->title }}</h4>
                             
                             @if($lesson->objective)
                             <div class="bg-slate-50 rounded-xl p-4 mb-4">
                                 <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Learning Objective</p>
                                 <p class="text-sm text-slate-600 leading-relaxed">{{ $lesson->objective }}</p>
                             </div>
                             @endif

                             @if($lesson->activity)
                             <div class="mb-4">
                                 <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Activity</p>
                                 <p class="text-sm text-slate-600">{{ $lesson->activity }}</p>
                             </div>
                             @endif

                             @if($lesson->homework)
                             <div class="pt-4 border-t border-slate-50 flex items-start">
                                 <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center mr-3 flex-shrink-0">
                                     <i class="bi bi-house-door text-orange-500"></i>
                                 </div>
                                 <div class="flex-1">
                                     <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">Homework</p>
                                     <p class="text-xs text-slate-600">{{ $lesson->homework }}</p>
                                 </div>
                             </div>
                             @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                            <i class="bi bi-journal-x text-2xl text-slate-300"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">No Lessons Found</h3>
                        <p class="text-slate-500 text-sm">A lesson plan hasn't been created for this subject yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
