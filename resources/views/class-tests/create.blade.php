@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-3">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">Create Assessment</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <i class="bi-plus-lg text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 leading-none mb-1">New Individual Test</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Define your class test parameters for result entry</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm">
                <form action="{{ route('class-tests.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">1. Test Title</label>
                        <input type="text" name="title" required placeholder="e.g., Weekly Quiz #1, Monthly Revision"
                               class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                        @error('title') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">2. Maximum Marks</label>
                            <input type="number" name="max_marks" required placeholder="e.g., 20"
                                   class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">3. Test Date</label>
                            <input type="date" name="test_date" required value="{{ date('Y-m-d') }}"
                                   class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">4. Select Subject & Class</label>
                        <div class="grid grid-cols-1 gap-3 max-h-[300px] overflow-y-auto pr-2">
                            @foreach($allocations as $allocation)
                                <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition-all group">
                                    <input type="radio" name="allocation_id" value="{{ $allocation->id }}" class="sr-only peer" required>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 mr-4 flex items-center justify-center peer-checked:border-teal-600 transition-all">
                                        <div class="w-2.5 h-2.5 rounded-full bg-teal-600 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-700 group-hover:text-teal-600 transition-colors leading-tight">
                                            {{ $allocation->subject->name }}
                                        </span>
                                        <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">
                                            Section: {{ $allocation->section->name }} 
                                            @if(auth()->user()->hasAnyRole(['admin', 'head']))
                                                | Instructor: {{ $allocation->user->profile->name ?? 'N/A' }}
                                            @endif
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 py-4 bg-teal-600 text-white rounded-2xl text-xs font-bold uppercase tracking-widest hover:bg-teal-700 hover:shadow-xl hover:shadow-teal-100 transition-all">
                            <i class="bi-check-lg"></i> Create Test & Start Entries
                        </button>
                    </div>
                </form>
            </div>

            <div class="hidden lg:block space-y-6">
                <div class="bg-teal-600 rounded-[2rem] p-8 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold mb-4 leading-tight">Create your own assessments effortlessly.</h3>
                        <p class="text-teal-50 text-xs font-medium leading-relaxed mb-6 italic opacity-80">
                            "This module allows you to track individual progress beyond scheduled departmental exams. 
                            Use these tests for your weekly evaluations and compare student growth over time."
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-xs font-bold">
                                <i class="bi-check-circle-fill text-teal-200 text-lg"></i>
                                Subject-Specific Focus
                            </li>
                            <li class="flex items-center gap-3 text-xs font-bold">
                                <i class="bi-check-circle-fill text-teal-200 text-lg"></i>
                                Real-time Comparative Analysis
                            </li>
                            <li class="flex items-center gap-3 text-xs font-bold">
                                <i class="bi-check-circle-fill text-teal-200 text-lg"></i>
                                Automatic Data Aggregation
                            </li>
                        </ul>
                    </div>
                    <i class="bi-mortarboard absolute -right-10 -bottom-10 text-[12rem] text-white/10 opacity-20 -rotate-12"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
