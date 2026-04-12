@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">

        @php $missing = $missingStudents->sortBy('rollno'); @endphp

        {{-- ── Header ── --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3 flex-wrap">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('tests.show', $testSubject->test) }}" class="hover:text-teal-600 transition-colors">{{ $testSubject->test->title }}</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('test.test-subjects.show', [$testSubject->test, $testSubject]) }}" class="hover:text-teal-600 transition-colors">{{ $testSubject->subject->name }}</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Import Students</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100 shrink-0">
                        <i class="bi-person-plus-fill text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 leading-none mb-2">Import Students</h1>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-slate-200">
                                <i class="bi-journal-text text-[8px]"></i> {{ $testSubject->test->title }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-[9px] font-black uppercase tracking-widest rounded-full border border-teal-100">
                                <i class="bi-book text-[8px]"></i> {{ $testSubject->subject->name }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-[9px] font-black uppercase tracking-widest rounded-full border border-teal-100">
                                <i class="bi-collection text-[8px]"></i> {{ $testSubject->section->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('test.test-subjects.show', [$testSubject->test, $testSubject]) }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:text-teal-600 hover:border-teal-200 transition-all self-start shrink-0">
                <i class="bi-arrow-left"></i> Back
            </a>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- ── Info Banner ── --}}
        <div class="flex items-start gap-4 p-5 bg-teal-50/70 border border-teal-100 rounded-2xl">
            <div class="w-9 h-9 rounded-xl bg-teal-100 flex items-center justify-center shrink-0 mt-0.5">
                <i class="bi-info-circle-fill text-teal-600"></i>
            </div>
            <div>
                <p class="text-xs font-black text-teal-800 mb-1">Before you import</p>
                <ul class="text-[11px] text-teal-700/80 font-medium space-y-0.5 list-none">
                    <li>· Only students <span class="font-black">not yet enrolled</span> in this test subject are listed below.</li>
                    <li>· Use <span class="font-black">Select All</span> to quickly check or uncheck all visible students.</li>
                    <li>· If a student is missing from the list entirely, contact the administrator.</li>
                </ul>
            </div>
        </div>

        {{-- ── Main Panel ── --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">

            {{-- Toolbar --}}
            <div class="px-6 md:px-8 py-5 border-b border-slate-50 bg-slate-50/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    {{-- Select All toggle --}}
                    <label for="chkAll"
                           class="flex items-center gap-2.5 px-4 py-2.5 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-teal-300 hover:bg-teal-50/40 transition-all group">
                        <div class="relative">
                            <input type="checkbox" id="chkAll" onclick="checkAll()"
                                   class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500 cursor-pointer">
                        </div>
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest group-hover:text-teal-600 transition-colors">Select All</span>
                    </label>

                    <div class="flex items-baseline gap-1">
                        <span id="selectedCount" class="text-xl font-black text-slate-800">0</span>
                        <span class="text-[10px] font-black text-slate-400 uppercase">/ {{ $missing->count() }} selected</span>
                    </div>
                </div>

                {{-- Search --}}
                <div class="relative w-full sm:w-72 group">
                    <input type="text" id="searchby" placeholder="Search student name…"
                           oninput="search(event)"
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-400 transition-all outline-none">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-teal-500 transition-colors text-xs"></i>
                </div>
            </div>

            {{-- Student List Form --}}
            <form action="{{ route('test-subject.import.store', $testSubject) }}" method="post" id="importForm">
                @csrf

                @if($missing->count())
                    <div class="divide-y divide-slate-50">
                        @foreach ($missing as $student)
                            <label for="stu-{{ $student->id }}"
                                   class="tr student-row flex items-center gap-4 px-6 md:px-8 py-4 cursor-pointer hover:bg-slate-50/70 transition-all">
                                {{-- Roll badge --}}
                                <div class="roll-badge w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-[10px] font-black bg-slate-50 text-slate-500 border border-slate-100 transition-colors">
                                    {{ $student->rollno }}
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="student-name text-sm font-black text-slate-800 leading-tight truncate transition-colors">{{ $student->name }}</p>
                                    <p class="text-[10px] font-medium text-slate-400 mt-0.5">{{ $student->father_name }}</p>
                                </div>

                                {{-- Custom Checkbox --}}
                                <div class="shrink-0 flex items-center justify-center">
                                    <div class="chk-box w-5 h-5 rounded-md border-2 border-slate-200 flex items-center justify-center transition-all">
                                        <i class="chk-icon bi-check text-white text-[11px] font-black opacity-0 transition-opacity"></i>
                                    </div>
                                    <input type="checkbox" id="stu-{{ $student->id }}"
                                           name="student_ids_array[]"
                                           value="{{ $student->id }}"
                                           class="student-chk sr-only"
                                           onchange="onStudentCheck(this)">
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Submit footer --}}
                    <div class="px-6 md:px-8 py-6 border-t border-slate-50 bg-slate-50/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-xs text-slate-500 font-medium">
                            <span id="footerCount" class="font-black text-slate-800">0</span> student(s) will be enrolled in this subject test.
                        </p>
                        <button type="submit"
                                class="flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all whitespace-nowrap">
                            <i class="bi-person-check-fill"></i> Import Selected Students
                        </button>
                    </div>
                @else
                    <div class="py-20 text-center">
                        <i class="bi-check-circle text-5xl text-teal-200 mb-4 block"></i>
                        <p class="text-sm font-black text-slate-500">All students are already enrolled.</p>
                        <p class="text-xs text-slate-400 font-medium mt-1">No missing students to import for this subject.</p>
                        <a href="{{ route('test.test-subjects.show', [$testSubject->test, $testSubject]) }}"
                           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 transition-all">
                            <i class="bi-arrow-left"></i> Back to Subject
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    function setRowState(chk, checked) {
        const row = chk.closest('.student-row');
        const badge = row.querySelector('.roll-badge');
        const name  = row.querySelector('.student-name');
        const box   = row.querySelector('.chk-box');
        const icon  = row.querySelector('.chk-icon');

        if (checked) {
            row.classList.add('bg-teal-50/50');
            badge.className = badge.className
                .replace('bg-slate-50 text-slate-500 border-slate-100', '')
                + ' bg-teal-50 text-teal-700 border-teal-100';
            name.classList.add('text-teal-700');
            name.classList.remove('text-slate-800');
            box.classList.add('bg-teal-600', 'border-teal-600');
            box.classList.remove('border-slate-200');
            icon.classList.remove('opacity-0');
        } else {
            row.classList.remove('bg-teal-50/50');
            badge.className = badge.className
                .replace('bg-teal-50 text-teal-700 border-teal-100', '')
                + ' bg-slate-50 text-slate-500 border-slate-100';
            name.classList.remove('text-teal-700');
            name.classList.add('text-slate-800');
            box.classList.remove('bg-teal-600', 'border-teal-600');
            box.classList.add('border-slate-200');
            icon.classList.add('opacity-0');
        }
    }

    function onStudentCheck(chk) {
        setRowState(chk, chk.checked);
        updateCount();
    }

    function updateCount() {
        const count = document.querySelectorAll('.student-chk:checked').length;
        document.getElementById('selectedCount').textContent = count;
        const footer = document.getElementById('footerCount');
        if (footer) footer.textContent = count;
    }

    function checkAll() {
        const master = document.getElementById('chkAll');
        document.querySelectorAll('.student-chk').forEach(chk => {
            chk.checked = master.checked;
            setRowState(chk, master.checked);
        });
        updateCount();
    }

    function search(event) {
        const q = event.target.value.toLowerCase();
        document.querySelectorAll('.student-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }
</script>
@endsection
