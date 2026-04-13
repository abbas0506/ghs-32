@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">

        @php $results = $testSubject->results->sortBy('student.rollno'); @endphp

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
                    <span class="text-teal-600">Enter Marks</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100 shrink-0">
                        <i class="bi-pencil-square text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 leading-none mb-2">Enter Marks</h1>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-[9px] font-black uppercase tracking-widest rounded-full border border-teal-100">
                                <i class="bi-book text-[8px]"></i> {{ $testSubject->subject->name }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-slate-200">
                                <i class="bi-collection text-[8px]"></i> {{ $testSubject->section->name }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-slate-200">
                                <i class="bi-people text-[8px]"></i> {{ $results->count() }} students
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

        {{-- ══ STEP 1: Max Marks Prompt ══ --}}
        <div id="step-max-marks" class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 md:px-8 py-8">
                <div class="max-w-sm mx-auto text-center">
                    <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mx-auto mb-5 shadow-sm">
                        <i class="bi-award-fill text-3xl"></i>
                    </div>
                    <h2 class="text-lg font-black text-slate-800 mb-1">Set Maximum Marks</h2>
                    <p class="text-xs text-slate-400 font-medium mb-8 leading-relaxed">
                        Confirm the total marks for this test before entering student results.
                        Current value is pre-filled from the test record.
                    </p>

                    <div class="flex items-center justify-center gap-3 mb-8">
                        <div class="flex flex-col items-start">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Maximum Marks</label>
                            <div class="flex items-center gap-2">
                                <input type="number" id="max_marks_step1"
                                       value="{{ $testSubject->max_marks }}"
                                       min="1" max="500"
                                       class="w-28 px-4 py-3 bg-slate-50 border-2 border-teal-200 rounded-2xl text-2xl font-black text-teal-700 text-center focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all"
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();confirmMaxMarks();}"
                                >
                                <span class="text-sm font-black text-slate-400">pts</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="confirmMaxMarks()"
                            class="w-full flex items-center justify-center gap-2 p-4 bg-teal-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                        <i class="bi-pencil-square"></i> Proceed to Enter Marks
                    </button>
                </div>
            </div>
        </div>

        {{-- ══ STEP 2: Marks Entry Panel (hidden until Step 1 confirmed) ══ --}}
        <div id="step-entry" class="hidden">
            {{-- ── Form Panel ── --}}
            <form action="{{ route('test-subject.results.update', [$testSubject, 1]) }}" method="post"
                  id="marksForm" onsubmit="return validate(event)">
                @csrf
                @method('patch')

                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">

                    {{-- Toolbar: Max Marks (readonly summary) + student count + save --}}
                    <div class="px-6 md:px-8 py-5 border-b border-slate-50 bg-slate-50/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Max Marks</span>
                                <div class="flex items-center gap-1.5">
                                    <input type="number" id="max_marks" name="max_marks"
                                           value="{{ $testSubject->max_marks }}"
                                           min="1" max="500"
                                           class="w-20 px-3 py-1.5 bg-white border-2 border-teal-200 rounded-xl text-lg font-black text-teal-700 text-center focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all">
                                    <span class="text-[10px] font-black text-slate-400">pts</span>
                                </div>
                            </div>
                            <div class="w-px h-10 bg-slate-100"></div>
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Students</span>
                                <span class="text-lg font-black text-slate-800">{{ $results->count() }}</span>
                            </div>
                        </div>
                        <button type="submit"
                                class="flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all whitespace-nowrap">
                            <i class="bi-check-lg"></i> Save Marks
                        </button>
                    </div>

                    {{-- Mark Entry Rows --}}
                    @if($results->count())
                        <div class="divide-y divide-slate-50">
                            @foreach ($results as $result)
                                <div class="flex flex-wrap items-center gap-3 px-6 md:px-8 py-4 hover:bg-slate-50/50 transition-all">
                                    {{-- Roll --}}
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-[10px] font-black bg-teal-50 text-teal-700 border border-teal-100">
                                        {{ $result->student->rollno }}
                                    </div>

                                    {{-- Name — takes remaining space on desktop, full width minus badge on mobile --}}
                                    <div class="flex-1 min-w-[120px]">
                                        <p class="text-sm font-black text-slate-800 leading-tight">{{ $result->student->name }}</p>
                                        <p class="text-[10px] font-medium text-slate-400 mt-0.5">{{ $result->student->father_name }}</p>
                                    </div>

                                    <input type="hidden" name="result_ids_array[]" value="{{ $result->id }}">

                                    {{-- Marks Input — always on same row as name on md+, may wrap on xs --}}
                                    <div class="flex items-center gap-2 ml-auto">
                                        <input type="number"
                                               name="obtained_marks_array[]"
                                               value="{{ $result->obtained_marks }}"
                                               min="0" max="500"
                                               onclick="this.select()"
                                               oninput="highlightRow(this)"
                                               class="obtained-marks w-20 px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-black text-slate-700 text-center hover:border-teal-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                                        <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap">/ <span class="max-label">{{ $testSubject->max_marks }}</span></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="px-6 md:px-8 py-6 border-t border-slate-50 bg-slate-50/40 flex justify-end">
                            <button type="submit"
                                    class="flex items-center gap-2 px-8 py-3 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                                <i class="bi-check-lg"></i> Save All Marks
                            </button>
                        </div>
                    @else
                        <div class="py-20 text-center">
                            <i class="bi-people text-5xl text-slate-200 mb-4 block"></i>
                            <p class="text-sm font-black text-slate-400">No students enrolled.</p>
                            <a href="{{ route('test-subject.import.index', $testSubject) }}"
                               class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 transition-all">
                                <i class="bi-person-plus"></i> Import Students
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    function confirmMaxMarks() {
        const val = parseFloat(document.getElementById('max_marks_step1').value);
        if (!val || val < 1) {
            document.getElementById('max_marks_step1').focus();
            return;
        }

        // Sync value into the form's max_marks input
        document.getElementById('max_marks').value = val;

        // Update all max-label spans
        document.querySelectorAll('.max-label').forEach(el => el.textContent = val);

        // Animate step 1 out, step 2 in
        const s1 = document.getElementById('step-max-marks');
        const s2 = document.getElementById('step-entry');

        s1.style.opacity = '0';
        s1.style.transform = 'translateY(-12px)';
        s1.style.transition = 'opacity 0.25s ease, transform 0.25s ease';

        setTimeout(() => {
            s1.classList.add('hidden');
            s2.classList.remove('hidden');
            s2.style.opacity = '0';
            s2.style.transform = 'translateY(12px)';
            s2.style.transition = 'none';
            // trigger reflow
            void s2.offsetHeight;
            s2.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            s2.style.opacity = '1';
            s2.style.transform = 'translateY(0)';

            // Focus first marks input
            const firstInput = s2.querySelector('.obtained-marks');
            if (firstInput) { firstInput.focus(); firstInput.select(); }
        }, 260);
    }

    // Keep max_marks_step1 and max_marks in sync
    document.getElementById('max_marks_step1')?.addEventListener('input', function () {
        document.getElementById('max_marks').value = this.value;
        document.querySelectorAll('.max-label').forEach(el => el.textContent = this.value || '?');
    });

    document.getElementById('max_marks')?.addEventListener('input', function () {
        document.querySelectorAll('.max-label').forEach(el => el.textContent = this.value || '?');
    });

    function highlightRow(input) {
        const max = parseFloat(document.getElementById('max_marks').value);
        const val = parseFloat(input.value);
        if (!isNaN(val) && !isNaN(max) && val > max) {
            input.classList.add('border-rose-400', 'bg-rose-50', 'text-rose-700');
            input.classList.remove('border-slate-200');
        } else {
            input.classList.remove('border-rose-400', 'bg-rose-50', 'text-rose-700');
            input.classList.add('border-slate-200');
        }
    }

    function validate(event) {
        const maxMarks = parseFloat(document.getElementById('max_marks').value);
        const inputs = document.querySelectorAll('.obtained-marks');
        for (let i = 0; i < inputs.length; i++) {
            const obtained = parseFloat(inputs[i].value);
            if (obtained > maxMarks) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Marks',
                    text: `Obtained (${obtained}) exceeds maximum (${maxMarks}).`,
                    confirmButtonText: 'Fix it',
                    confirmButtonColor: '#0d9488'
                }).then(() => { inputs[i].focus(); inputs[i].select(); });
                return false;
            }
        }
        return true;
    }
</script>
@endsection
