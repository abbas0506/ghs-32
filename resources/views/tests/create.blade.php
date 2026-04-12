@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">Create Assessment</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <i class="bi-plus-lg text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 leading-none mb-1">New Test Configuration</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Define your assessment parameters for target demographic</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm">
                @php 
                    $currentRole = session('role') ?? (Auth::user()->roles->first()->name ?? 'teacher'); 
                    $isHead = in_array($currentRole, ['head', 'admin']);
                @endphp

                @if($isHead)
                    <!-- Mode Selector -->
                    <div class="flex p-1 mb-8 bg-slate-50 border border-slate-100 rounded-xl relative">
                        <button type="button" onclick="setMode('combined')" id="btn-combined" class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-lg bg-teal-600 text-white shadow-md transition-all duration-300 transform scale-100">Combined Exam</button>
                        <button type="button" onclick="setMode('individual')" id="btn-individual" class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 rounded-lg hover:text-slate-600 transition-all duration-300 transform scale-95 opacity-80">Subject Test</button>
                    </div>
                @endif

                <form action="{{ route('tests.store') }}" method="POST" id="testForm" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">1. Test Title</label>
                        <input type="text" name="title" required placeholder="e.g., Terminal Examination 2026"
                               class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                        @error('title') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">2. Maximum Marks</label>
                            <input type="number" name="max_marks" required placeholder="e.g., 50"
                                   class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                        </div>
                        <div class="space-y-2" id="date-group" style="display: {{ $isHead ? 'none' : 'block' }};">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">3. Test Date</label>
                            <input type="date" name="test_date" id="date-input" value="{{ date('Y-m-d') }}" {{ $isHead ? '' : 'required' }}
                                   class="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                        </div>
                    </div>

                    <!-- Individual Allocations -->
                    <div class="space-y-4" id="allocations-group" style="display: {{ $isHead ? 'none' : 'block' }};">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{{ $isHead ? '4' : '3' }}. Select Subject Allocation</label>
                        <div class="grid grid-cols-1 gap-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($allocations as $allocation)
                                <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition-all group">
                                    <input type="radio" name="allocation_id" id="alloc-{{ $allocation->id }}" value="{{ $allocation->id }}" class="sr-only peer" {{ $isHead ? '' : 'required' }}>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-200 mr-4 flex items-center justify-center peer-checked:border-teal-600 transition-all">
                                        <div class="w-2.5 h-2.5 rounded-full bg-teal-600 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-slate-700 group-hover:text-teal-600 transition-colors leading-tight">
                                            {{ $allocation->subject->name }}
                                        </span>
                                        <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">
                                            Section: {{ $allocation->section->name }} 
                                            @if($isHead) | Instructor: {{ $allocation->user->profile->name ?? 'N/A' }} @endif
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                            @if(count($allocations) == 0)
                                <p class="text-xs text-rose-500 font-bold p-4 bg-rose-50 rounded-2xl">You have no class allocations.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Combined Sections -->
                    @if($isHead)
                    <div class="space-y-4" id="sections-group" style="display: block;">
                        <div class="flex items-center justify-between ml-1">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">3. Select Target Sections</label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" id="check_all" class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest group-hover:text-teal-600 transition-colors">Select All</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach ($sections as $section)
                                <label class="relative flex items-center p-3 rounded-xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition-all group section-label">
                                    <input type="checkbox" name="sections_array[]" value="{{ $section->id }}" class="section-checkbox sr-only peer">
                                    <div class="w-4 h-4 rounded content-center border-2 border-slate-200 mr-3 flex items-center justify-center peer-checked:bg-teal-600 peer-checked:border-teal-600 transition-all">
                                        <i class="bi-check text-white text-[10px] opacity-0 peer-checked:opacity-100 font-black"></i>
                                    </div>
                                    <span class="text-xs font-black text-slate-700 peer-checked:text-teal-600 transition-colors">{{ $section->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 py-4 bg-teal-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-xl hover:shadow-teal-100 transition-all">
                            <i class="bi-check-lg" id="btn-icon"></i> <span id="btn-text">Generate Assessment Framework</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="hidden lg:block space-y-6">
                <!-- Info banner matching individual context smoothly -->
                <div class="bg-teal-600 rounded-[2rem] p-8 text-white relative overflow-hidden h-full flex flex-col justify-center">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black mb-4 leading-tight">Create your own assessments effortlessly.</h3>
                        <p class="text-teal-50 text-sm font-medium leading-relaxed mb-8 italic opacity-80">
                            "This dynamic assessment module safely unifies institutional and instructor-level exam deployment effortlessly under a single seamless workspace."
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-sm font-bold">
                                <i class="bi-check-circle-fill text-teal-200 text-lg"></i>
                                Universal Subject-Specific Filtering
                            </li>
                            <li class="flex items-center gap-3 text-sm font-bold">
                                <i class="bi-check-circle-fill text-teal-200 text-lg"></i>
                                Automated Multi-class Result Bridging
                            </li>
                            <li class="flex items-center gap-3 text-sm font-bold">
                                <i class="bi-check-circle-fill text-teal-200 text-lg"></i>
                                Advanced Analytics Aggregation
                            </li>
                        </ul>
                    </div>
                    <i class="bi-mortarboard absolute -right-10 -bottom-10 text-[16rem] text-white/10 opacity-20 -rotate-12"></i>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    function setMode(mode) {
        const btnCombined = document.getElementById('btn-combined');
        const btnIndividual = document.getElementById('btn-individual');
        const sectionsGroup = document.getElementById('sections-group');
        const allocGroup = document.getElementById('allocations-group');
        const dateGroup = document.getElementById('date-group');
        
        // Input requirements
        const dateInput = document.getElementById('date-input');
        const checkBoxes = document.querySelectorAll('.section-checkbox');
        const radioBoxes = document.querySelectorAll('input[name="allocation_id"]');

        if(mode === 'combined') {
            // activate combined tab
            btnCombined.className = "flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-lg bg-teal-600 text-white shadow-md transition-all duration-300 transform scale-100";
            btnIndividual.className = "flex-1 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 rounded-lg hover:text-slate-600 hover:bg-white/50 transition-all duration-300 transform scale-95 opacity-80 cursor-pointer";
            
            sectionsGroup.style.display = 'block';
            allocGroup.style.display = 'none';
            dateGroup.style.display = 'none';
            
            dateInput.required = false;
            radioBoxes.forEach(rb => rb.required = false);
            // checkBoxes.forEach(cb => cb.required = true); // logic usually handled by minimum 1 selected manually
        } else {
            // activate individual tab
            btnIndividual.className = "flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-lg bg-teal-600 text-white shadow-md transition-all duration-300 transform scale-100 cursor-pointer";
            btnCombined.className = "flex-1 py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 rounded-lg hover:text-slate-600 hover:bg-white/50 transition-all duration-300 transform scale-95 opacity-80 cursor-pointer";
            
            sectionsGroup.style.display = 'none';
            allocGroup.style.display = 'block';
            dateGroup.style.display = 'block';

            dateInput.required = true;
            radioBoxes.forEach(rb => rb.required = true);
        }
    }

    // Toggle multi-select section checkboxes
    document.getElementById('check_all')?.addEventListener('change', function(e) {
        const isChecked = e.target.checked;
        document.querySelectorAll('.section-checkbox').forEach(cb => {
            cb.checked = isChecked;
        });
    });

    // Form logic execution requirement
    document.getElementById('testForm').addEventListener('submit', function(e) {
        const sectionsGroup = document.getElementById('sections-group');
        if(sectionsGroup && sectionsGroup.style.display !== 'none') {
            const checked = document.querySelectorAll('.section-checkbox:checked');
            if(checked.length === 0) {
                e.preventDefault();
                alert('Please select at least one class section to target the combined assessment.');
            }
        }
    });
</script>
@endsection
