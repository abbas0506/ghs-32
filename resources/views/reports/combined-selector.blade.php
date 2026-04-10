@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">Combined Report</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-journals text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 leading-none mb-1">Combined Reports</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Select multiple assessments to generate aggregate results</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" form="combinedReportForm" 
                    onclick="document.getElementById('combinedReportForm').action='{{ route('reports.combined.pdf') }}'"
                    class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:text-teal-600 hover:border-teal-200 transition-all">
                    <i class="bi-file-earmark-pdf"></i> Result Sheet
                </button>
                <button type="submit" form="combinedReportForm" 
                    onclick="document.getElementById('combinedReportForm').action='{{ route('reports.combined.report-cards') }}'"
                    class="flex items-center gap-2 px-8 py-3 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                    <i class="bi-person-badge"></i> Report Cards
                </button>
            </div>
        </div>

        <form action="{{ route('reports.combined.pdf') }}" method="post" id="combinedReportForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            
            <!-- Left Column: Section Selection -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                            <i class="bi-people-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-800 uppercase tracking-tighter">1. Select Class</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($sections->sortBy('name') as $section)
                            <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition-all group">
                                <input type="radio" name="section_id" value="{{ $section->id }}" class="sr-only peer" required>
                                <div class="w-4 h-4 rounded-full border-2 border-slate-200 mr-4 peer-checked:border-teal-600 peer-checked:border-[5px] transition-all"></div>
                                <span class="text-xs font-bold text-slate-600 group-hover:text-teal-600 transition-colors">{{ $section->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Test Selection -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-50">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                                    <i class="bi-check2-all"></i>
                                </div>
                                <h3 class="font-black text-slate-800 uppercase tracking-tighter">2. Select Assessments</h3>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic" id="countDisplay">0 Selected</span>
                        </div>
                        
                        <div class="relative group">
                            <input type="text" id="testSearch" placeholder="Filter assessments by title..." oninput="filterTests(event)"
                                class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors"></i>
                        </div>
                    </div>

                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[500px] overflow-y-auto">
                        @foreach($tests->sortByDesc('created_at') as $test)
                            <label class="test-item relative flex items-center p-4 rounded-2xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition-all group">
                                <input type="checkbox" name="test_ids[]" value="{{ $test->id }}" class="sr-only peer" onchange="updateCount()">
                                <div class="w-5 h-5 rounded-lg border-2 border-slate-200 mr-4 flex items-center justify-center peer-checked:bg-teal-600 peer-checked:border-teal-600 transition-all">
                                    <i class="bi bi-check-lg text-white opacity-0 peer-checked:opacity-100 text-xs translate-y-px"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="test-title text-xs font-bold text-slate-700 group-hover:text-teal-600 transition-colors leading-tight">{{ $test->title }}</span>
                                    <span class="text-[9px] font-medium text-slate-400 mt-1 uppercase">{{ \Carbon\Carbon::parse($test->created_at)->format('d M Y') }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    
                    <div class="bg-slate-50/50 p-6 border-t border-slate-100">
                        <p class="text-[10px] text-slate-400 italic">Note: The system will automatically aggregate results for students in the selected class across all chosen assessments.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="module">
        window.filterTests = function(event) {
            const search = event.target.value.toLowerCase();
            document.querySelectorAll('.test-item').forEach(item => {
                const title = item.querySelector('.test-title').innerText.toLowerCase();
                item.classList.toggle('hidden', !title.includes(search));
            });
        };

        window.updateCount = function() {
            const checked = document.querySelectorAll('input[name="test_ids[]"]:checked').length;
            document.getElementById('countDisplay').innerText = checked + ' Selected';
        };
    </script>
@endsection
