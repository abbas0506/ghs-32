@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">

        {{-- ── Header ── --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-3 flex-wrap">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('tests.show', $test) }}" class="hover:text-teal-600 transition-colors">{{ $test->title }}</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Add Subjects</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100 shrink-0">
                        <i class="bi-plus-circle-fill text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 leading-none mb-2">Subject Allocation</h1>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-[9px] font-bold uppercase tracking-widest rounded-full border border-teal-100">
                            <i class="bi-journal-check text-[8px]"></i> {{ $test->title }}
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('tests.show', $test) }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-bold uppercase tracking-widest hover:text-teal-600 hover:border-teal-200 transition-all self-start shrink-0">
                <i class="bi-arrow-left"></i> Back
            </a>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="max-w-5xl mx-auto w-full">
            <form action="{{ route('test.test-subjects.store', $test) }}" method="POST" id="testForm" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($sections as $section)
                        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:border-teal-200 transition-all group/card flex flex-col overflow-hidden">
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
                            
                            <div class="p-5 grid grid-cols-2 gap-2 flex-1">
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

                <div class="sticky bottom-6 pt-4">
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-3 py-5 bg-teal-600 text-white rounded-[1.5rem] text-xs font-bold uppercase tracking-[0.2em] hover:bg-teal-700 shadow-xl shadow-teal-100 hover:shadow-teal-200 transition-all border-4 border-white group">
                        <i class="bi-check2-circle text-lg group-hover:scale-110 transition-transform"></i> 
                        <span>Confirm & Add Selected Subjects</span>
                    </button>
                </div>
            </form>
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

    // Form validation
    document.getElementById('testForm').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('input[name^="section_subjects"]:checked');
        if (checked.length === 0) {
            e.preventDefault();
            alert('Please select at least one subject from any class.');
        }
    });
</script>
@endsection
