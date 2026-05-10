@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">Home</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Assessment</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-file-earmark-check text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 leading-none mb-1">Assessment</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Monitor academic tests and evaluation progress</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="{{ route('reports.combined.selector') }}" 
                   class="flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-[9px] md:text-[10px] font-bold uppercase tracking-widest hover:text-teal-600 hover:border-teal-200 transition-all">
                   <i class="bi-journals"></i> Combined Report
                </a>
                @can('create', App\Models\Test::class)
                    <a href="{{ route('tests.create') }}" 
                       class="flex items-center justify-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-xl text-[9px] md:text-[10px] font-bold uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
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
                    <p class="text-[9px] md:text-[10px] font-bold text-teal-100 uppercase tracking-widest">Open Tests</p>
                    <i class="bi-unlock text-white opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-xl md:text-2xl font-bold text-white">{{ $tests->where('is_open', true)->count() }}</h2>
                    @if($testsThisWeek->count() > 0)
                        <span class="text-[9px] font-bold text-white uppercase opacity-80">+{{ $testsThisWeek->count() }} new</span>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-center">
                <div class="flex items-center justify-between mb-1 space-x-1">
                    <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest">Data Status</p>
                    <i class="bi-graph-up-arrow text-teal-600 opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-1">
                    <h2 class="text-xl md:text-xl font-bold text-slate-800">{{ $dataProgress }}%</h2>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Test Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach ($tests->sortByDesc('created_at') as $test)
                @php
                    $sumbittedCount = $test->testSubjects()->mine()->resultSubmitted()->count();
                    $totalCount = $test->testSubjects()->mine()->count();
                    $percent = $totalCount > 0 ? round(($sumbittedCount / $totalCount) * 100, 0) : 0;
                    $isOpen = $test->is_open;
                @endphp
                
                <a href="{{ $test->user_id ? route('class-tests.show', $test) : route('tests.show', $test) }}"
                   class="group bg-white rounded-xl border border-slate-100 p-4 hover:shadow-lg hover:shadow-slate-100 hover:border-teal-200 transition-all duration-300">
                    
                    <div class="flex flex-col h-full gap-3">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 shrink-0 rounded-lg bg-slate-50 text-slate-500 flex items-center justify-center group-hover:bg-teal-50 group-hover:text-teal-600 transition-colors">
                                    <i class="bi-journal-text text-base"></i>
                                </div>
                                <div class="flex flex-col">
                                    <h3 class="font-bold text-xs text-slate-800 group-hover:text-teal-700 transition-colors leading-tight">{{ $test->title }}</h3>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($test->created_at)->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                @if($isOpen)
                                   <i class="bi-unlock text-[10px] text-green-500"></i>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between bg-slate-50/50 p-3 rounded-xl border border-slate-50">
                            <div class="flex flex-col">
                                <p class="text-[8px] uppercase font-bold text-slate-400">Submissions</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-sm font-bold text-slate-700 leading-none">{{ $sumbittedCount }}</span>
                                    <span class="text-[10px] font-bold text-slate-300">/ {{ $totalCount }}</span>
                                </div>
                            </div>
                            @if($percent==100)
                                <div class="flex items-center justify-center">
                                   <i class="bi-check-circle-fill text-xs text-green-500"></i>    
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endsection
