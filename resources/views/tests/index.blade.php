@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">Home</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Assessment</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-file-earmark-check text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 leading-none mb-1">Assessment</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Monitor academic tests and evaluation progress</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('reports.combined.selector') }}" 
                   class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:text-teal-600 hover:border-teal-200 transition-all">
                   <i class="bi-journals"></i> Combined Report
                </a>
                @can('create', App\Models\Test::class)
                    <a href="{{ route('tests.create') }}" 
                       class="flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all whitespace-nowrap">
                       <i class="bi-plus-lg"></i> New Assessment
                    </a>
                @endcan
            </div>
        </div>

        <!-- Quick Summary Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-teal-600 rounded-3xl p-6 text-white shadow-xl shadow-teal-100 flex flex-col justify-center relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] md:text-[10px] font-black text-teal-100 uppercase tracking-widest">Open Tests</p>
                    <i class="bi-unlock text-white opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-xl md:text-2xl font-black text-white">{{ $tests->where('is_open', true)->count() }}</h2>
                    @if($testsThisWeek->count() > 0)
                        <span class="text-[9px] font-black text-white uppercase opacity-80">+{{ $testsThisWeek->count() }} new</span>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-center">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Data Status</p>
                    <i class="bi-graph-up-arrow text-teal-600 opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-1">
                    <h2 class="text-xl md:text-2xl font-black text-slate-800">{{ $dataProgress }}%</h2>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Yield</span>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Test Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($tests->sortByDesc('created_at') as $test)
                @php
                    $sumbittedCount = $test->testSubjects()->mine()->resultSubmitted()->count();
                    $totalCount = $test->testSubjects()->mine()->count();
                    $percent = $totalCount > 0 ? round(($sumbittedCount / $totalCount) * 100, 0) : 0;
                    $isOpen = $test->is_open;
                @endphp
                
                <a href="{{ $test->user_id ? route('class-tests.show', $test) : route('tests.show', $test) }}"
                   class="group bg-white rounded-2xl border border-slate-100 p-6 hover:shadow-xl hover:shadow-slate-100 hover:border-teal-200 transition-all duration-300">
                    
                    <div class="flex flex-col h-full gap-5">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center group-hover:bg-teal-50 group-hover:text-teal-600 transition-colors">
                                    <i class="bi-journal-text text-lg"></i>
                                </div>
                                <h3 class="font-black text-slate-800 group-hover:text-teal-700 transition-colors leading-tight">{{ $test->title }}</h3>
                            </div>
                            <div class="flex items-center">
                                @if($isOpen)
                                    <span class="px-2 py-1 bg-teal-50 text-teal-600 text-[8px] font-black uppercase tracking-widest rounded-full border border-teal-100">Open</span>
                                @else
                                    <span class="px-2 py-1 bg-slate-50 text-slate-400 text-[8px] font-black uppercase tracking-widest rounded-full border border-slate-100 italic">Closed</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between bg-slate-50/50 p-4 rounded-2xl border border-slate-50">
                            <div class="flex flex-col">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Submissions</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xl font-black text-slate-700 leading-none">{{ $sumbittedCount }}</span>
                                    <span class="text-xs font-bold text-slate-300">/ {{ $totalCount }}</span>
                                </div>
                            </div>
                            
                            <div class="relative flex items-center justify-center">
                                <svg class="w-12 h-12 transform -rotate-90">
                                    <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" class="text-slate-100" />
                                    <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" class="text-teal-500" 
                                        stroke-dasharray="125.6" stroke-dashoffset="{{ 125.6 - (125.6 * $percent / 100) }}"
                                        stroke-linecap="round" />
                                </svg>
                                <span class="absolute text-[10px] font-black text-slate-800">{{ $percent }}%</span>
                            </div>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($test->created_at)->format('D, d M Y') }}</span>
                            <span class="text-[9px] font-black text-teal-500 opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all uppercase">View Details <i class="bi-arrow-right ml-1"></i></span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
