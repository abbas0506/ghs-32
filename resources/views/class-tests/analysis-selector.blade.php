@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-3">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">Analysis Engine</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-graph-up-arrow text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 leading-none mb-1">Comparative Performance</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Track progress across multiple individual assessments</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" form="analysisForm" 
                    class="flex items-center gap-2 px-8 py-3 bg-teal-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                    <i class="bi-bar-chart-line"></i> Perform Analysis
                </button>
            </div>
        </div>

        <form action="{{ route('class-tests.analysis') }}" method="GET" id="analysisForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Allocation Selection -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm h-full">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                            <i class="bi-journals"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 uppercase tracking-tighter">1. Select Subject</h3>
                    </div>
                    
                    <div class="space-y-3 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($allocations as $allocation)
                            @php 
                                $key = $allocation->section_id . '-' . $allocation->subject_id . '-' . $allocation->user_id;
                            @endphp
                            <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition-all group">
                                <input type="radio" name="allocation_id" value="{{ $allocation->id }}" 
                                    class="sr-only peer" required onchange="filterTests('{{ $key }}')">
                                <div class="w-4 h-4 rounded-full border-2 border-slate-200 mr-4 peer-checked:border-teal-600 peer-checked:border-[5px] transition-all"></div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-slate-700 group-hover:text-teal-600 transition-colors uppercase leading-tight">{{ $allocation->subject->name }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase">{{ $allocation->section->name }}</span>
                                    @if(auth()->user()->hasAnyRole(['admin', 'head']))
                                        <span class="text-[8px] font-medium text-slate-400 italic">{{ $allocation->user->profile->name ?? 'Unknown' }}</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Test Selection -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden h-full flex flex-col">
                    <div class="p-8 border-b border-slate-50">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                                    <i class="bi-check2-all"></i>
                                </div>
                                <h3 class="font-bold text-slate-800 uppercase tracking-tighter">2. Choose Assessments</h3>
                            </div>
                            <div class="flex items-center gap-4">
                                <button type="button" onclick="selectAllTests()" id="selectAllBtn" class="hidden text-[10px] font-bold text-teal-600 uppercase tracking-widest hover:text-teal-700 transition-colors">Select All</button>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic" id="countDisplay">0 Selected</span>
                            </div>
                        </div>
                        
                        <div class="relative group">
                            <input type="text" id="testSearch" placeholder="Filter by test title..." oninput="searchTests(event)"
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors"></i>
                        </div>
                    </div>

                    <div id="testContainer" class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3 flex-grow overflow-y-auto max-h-[500px]">
                        @foreach($testSubjects as $ts)
                            @php 
                                $key = $ts->section_id . '-' . $ts->subject_id . '-' . $ts->user_id;
                            @endphp
                            <label class="test-item hidden relative flex items-center p-4 rounded-2xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition-all group" 
                                data-key="{{ $key }}">
                                <input type="checkbox" name="test_ids[]" value="{{ $ts->test_id }}" class="sr-only peer" onchange="updateCount()">
                                <div class="w-5 h-5 rounded-lg border-2 border-slate-200 mr-4 flex items-center justify-center peer-checked:bg-teal-600 peer-checked:border-teal-600 transition-all">
                                    <i class="bi bi-check-lg text-white opacity-0 peer-checked:opacity-100 text-xs translate-y-px"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="test-title text-xs font-bold text-slate-700 group-hover:text-teal-600 transition-colors leading-tight uppercase">{{ $ts->test->title }}</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">{{ optional($ts->test_date)->format('d M Y') ?? 'No Date' }}</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                        <span class="text-[9px] font-bold text-teal-600 tracking-tighter">MAX: {{ $ts->max_marks }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach

                        <div id="noTestsMessage" class="col-span-full py-12 flex flex-col items-center text-center opacity-40">
                            <i class="bi bi-info-circle text-2xl mb-2"></i>
                            <p class="text-[10px] font-bold uppercase tracking-widest">Select a subject to view tests</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        let currentKey = '';

        window.filterTests = function(key) {
            currentKey = key;
            const items = document.querySelectorAll('.test-item');
            const container = document.getElementById('noTestsMessage');
            const selectAllBtn = document.getElementById('selectAllBtn');
            let count = 0;

            items.forEach(item => {
                const isMatch = item.getAttribute('data-key') === key;
                item.classList.toggle('hidden', !isMatch);
                if (isMatch) {
                    count++;
                }
                // Always uncheck when switching subjects
                item.querySelector('input').checked = false;
            });

            container.classList.toggle('hidden', count > 0);
            selectAllBtn.classList.toggle('hidden', count === 0);
            updateCount();
            
            // Clear search
            document.getElementById('testSearch').value = '';
        };

        window.selectAllTests = function() {
            const items = document.querySelectorAll('.test-item:not(.hidden)');
            items.forEach(item => {
                item.querySelector('input').checked = true;
            });
            updateCount();
        };

        window.searchTests = function(event) {
            const search = event.target.value.toLowerCase();
            const items = document.querySelectorAll('.test-item');
            
            items.forEach(item => {
                if (item.getAttribute('data-key') === currentKey) {
                    const title = item.querySelector('.test-title').innerText.toLowerCase();
                    item.classList.toggle('hidden', !title.includes(search));
                }
            });
        };

        window.updateCount = function() {
            const checked = document.querySelectorAll('input[name="test_ids[]"]:checked').length;
            document.getElementById('countDisplay').innerText = checked + ' Selected';
        };
    </script>
@endsection
