@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">Create Assessment</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <i class="bi-plus-lg text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 leading-none mb-1">New Test Configuration</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Define your assessment parameters and target demographic</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto w-full">
            <div class="bg-white rounded-[1rem] md:rounded-[2rem] border border-slate-100 p-8 md:p-12 shadow-sm">
                <form action="{{ route('tests.store') }}" method="POST" id="testForm" class="space-y-10">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">1. Test Title</label>
                            <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g., Terminal Examination 2026"
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                            @error('title') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">2. Maximum Marks</label>
                            <input type="number" name="max_marks" required value="{{ old('max_marks', 50) }}" placeholder="e.g., 50"
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-end">
                        <div class="space-y-2 md:col-span-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">3. Test Date</label>
                            <input type="date" name="test_date" id="test_date" value="{{ old('test_date', date('Y-m-d')) }}" required
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 transition-all">
                        </div>
                        
                        <div class="md:col-span-2 flex items-center gap-4 p-4 bg-teal-50/50 rounded-2xl border border-teal-100/50">
                            <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-teal-200">
                                <i class="bi-info-circle text-lg"></i>
                            </div>
                            <p class="text-[11px] font-bold text-teal-800/70 leading-relaxed italic">
                                Tip: You can select different subjects for each class independently. Use "Select All" inside each class card for quick selection.
                            </p>
                        </div>
                    </div>

                    <!-- Target Sections & Subjects -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between ml-1">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">4. Configure Assessment Scope</label>
                            <div class="text-[10px] font-bold text-slate-300 italic uppercase tracking-tighter">Choose subjects per class section</div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($sections as $section)
                                <div class="bg-white rounded-[1rem] md:rounded-[2rem] border border-slate-100 shadow-sm hover:border-teal-200 transition-all group/card flex flex-col overflow-hidden">
                                    <div class="p-5 border-b border-slate-50 bg-slate-50/30 group-hover/card:bg-teal-50/30 transition-colors flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-100 text-teal-600 flex items-center justify-center font-bold text-xs shadow-sm group-hover/card:bg-teal-600 group-hover/card:text-white group-hover/card:border-teal-600 transition-all">
                                                {{ substr($section->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <h3 class="text-xs font-bold text-slate-800 group-hover/card:text-teal-700 transition-colors">{{ $section->name }}</h3>
                                                <p class="text-[8px] text-slate-400 uppercase font-bold tracking-widest">{{ $section->grade->name }}</p>
                                            </div>
                                        </div>
                                        <label class="flex items-center gap-2 cursor-pointer group/toggle">
                                            <input type="checkbox" class="check-all-in-section w-3.5 h-3.5 rounded border-slate-300 text-teal-600 focus:ring-teal-500" data-section="{{ $section->id }}">
                                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest group-hover/toggle:text-teal-600 transition-colors">Select All</span>
                                        </label>
                                    </div>
                                    
                                    <div class="p-3 md:p-5 grid grid-cols-1 md:grid-cols-2 gap-2 flex-1">
                                        @foreach ($section->display_subjects ?? $section->grade->subjects as $subject)
                                            <label class="flex items-center px-3 py-2.5 rounded-xl border border-slate-50 hover:bg-slate-50 cursor-pointer transition-all group/sub relative overflow-hidden">
                                                <input type="checkbox" name="section_subjects[{{ $section->id }}][]" value="{{ $subject->id }}" 
                                                       class="section-subject-checkbox-{{ $section->id }} w-3.5 h-3.5 rounded border-slate-300 text-teal-600 focus:ring-teal-500 mr-2 peer sr-only">
                                                
                                                <div class="w-4 h-4 rounded-md border-2 border-slate-200 mr-2.5 flex items-center justify-center peer-checked:bg-teal-600 peer-checked:border-teal-600 transition-all z-10 shrink-0">
                                                    <i class="bi-check text-white text-[10px] opacity-0 peer-checked:opacity-100 font-bold"></i>
                                                </div>
                                                
                                                <span class="text-[10px] font-bold text-slate-600 peer-checked:text-teal-700 transition-colors z-10 line-clamp-1">{{ $subject->name }}</span>
                                                <div class="absolute inset-0 bg-teal-50/0 peer-checked:bg-teal-50/40 transition-colors"></div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('section_subjects') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-6">
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-3 py-5 bg-teal-600 text-white rounded-[1.5rem] text-[9px] font-bold uppercase tracking-[0.2em] hover:bg-teal-700 shadow-xl shadow-teal-100 hover:shadow-teal-200 transition-all group">
                            <i class="bi-lightning-charge-fill group-hover:rotate-12 transition-transform"></i> 
                            <span>Initialize Assessment</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Toggle all subjects within a section
    document.querySelectorAll('.check-all-in-section').forEach(checkAll => {
        checkAll.addEventListener('change', function() {
            const sectionId = this.getAttribute('data-section');
            const checkboxes = document.querySelectorAll(`.section-subject-checkbox-${sectionId}`);
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
        });
    });

    @if(!Auth::user()->hasAnyRole(['admin', 'head']))
    // Enforcement: Teachers can only select one class at a time
    const allSubjectCheckboxes = document.querySelectorAll('input[name^="section_subjects"]');
    const allSectionToggles = document.querySelectorAll('.check-all-in-section');

    function getSelectedSectionCount() {
        const selectedSections = new Set();
        document.querySelectorAll('input[name^="section_subjects"]:checked').forEach(cb => {
            const match = cb.name.match(/\[(\d+)\]/);
            if (match) selectedSections.add(match[1]);
        });
        return selectedSections;
    }

    allSubjectCheckboxes.forEach(cb => {
        cb.addEventListener('click', function(e) {
            if (!this.checked) return; // Unchecking is always allowed

            const match = this.name.match(/\[(\d+)\]/);
            const sectionId = match ? match[1] : null;
            const selected = getSelectedSectionCount();
            
            // Check if there are any selected sections OTHER than the one we just clicked
            const hasOtherSections = Array.from(selected).some(id => id !== sectionId);

            if (hasOtherSections) {
                if (confirm('Teachers can only select subjects for one class at a time. Switching classes will clear your current selection. Continue?')) {
                    // Uncheck everything else
                    allSubjectCheckboxes.forEach(otherCb => {
                        if (!otherCb.name.includes(`[${sectionId}]`)) otherCb.checked = false;
                    });
                    allSectionToggles.forEach(toggle => {
                        if (toggle.getAttribute('data-section') !== sectionId) toggle.checked = false;
                    });
                } else {
                    e.preventDefault();
                    this.checked = false;
                }
            }
        });
    });

    allSectionToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (!this.checked) return;

            const sectionId = this.getAttribute('data-section');
            const selected = getSelectedSectionCount();
            const hasOtherSections = Array.from(selected).some(id => id !== sectionId);

            if (hasOtherSections) {
                if (confirm('Teachers can only select subjects for one class at a time. Switching classes will clear your current selection. Continue?')) {
                    allSubjectCheckboxes.forEach(otherCb => {
                        if (!otherCb.name.includes(`[${sectionId}]`)) otherCb.checked = false;
                    });
                    allSectionToggles.forEach(otherToggle => {
                        if (otherToggle !== this) otherToggle.checked = false;
                    });
                } else {
                    e.preventDefault();
                    this.checked = false;
                }
            }
        });
    });
    @endif

    // Form logic execution requirement
    document.getElementById('testForm').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('input[name^="section_subjects"]:checked');
        
        if(checked.length === 0) {
            e.preventDefault();
            alert('Please select at least one subject from any class.');
            return;
        }
    });
</script>
@endsection
