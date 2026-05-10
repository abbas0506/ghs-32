@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">

        @php
            $isSubmitted  = $testSubject->hasBeenSubmitted();
            $studentCount = $testSubject->appearingStudents->count();
            $results      = $testSubject->results->sortBy('student.rollno');
            $maxMarks     = $testSubject->max_marks;
            $totalObtained = $results->sum('obtained_marks');
            $classAvg     = $studentCount > 0 ? round($totalObtained / $studentCount, 1) : 0;
            $passCount    = $results->filter(fn($r) => $r->obtained_marks >= ($maxMarks * 0.4))->count();
        @endphp

        {{-- ── Breadcrumb & Header ── --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3 flex-wrap">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('tests.show', $testSubject->test) }}" class="hover:text-teal-600 transition-colors">{{ $testSubject->test->title }}</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">{{ $testSubject->subject->name }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100 shrink-0">
                        <i class="bi-journal-text text-2xl"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h1 class="text-xl font-bold text-slate-800 leading-none mb-2 truncate">
                            {{ $testSubject->subject->name }}
                        </h1>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-600 text-[9px] font-bold uppercase tracking-widest rounded-full border border-slate-200">
                                <i class="bi-collection text-[8px]"></i> {{ $testSubject->section->name }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-600 text-[9px] font-bold uppercase tracking-widest rounded-full border border-indigo-100">
                                <i class="bi-person text-[8px]"></i> {{ $testSubject->user?->profile->short_name ?? '—' }}
                            </span>
                            @if($isSubmitted)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-teal-50 text-teal-700 text-[9px] font-bold uppercase tracking-widest rounded-full border border-teal-100">
                                    <i class="bi-check-circle-fill text-[8px]"></i> Submitted {{ $testSubject->result_date->format('d M Y') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 text-[9px] font-bold uppercase tracking-widest rounded-full border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                </span>
                            @endif
                            @if($testSubject->test_date)
                                <span class="text-[9px] font-bold text-slate-400">
                                    <i class="bi-calendar3 mr-1"></i>{{ $testSubject->test_date->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Action Buttons ── --}}
            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                <a href="{{ route('subject-result', $testSubject) }}" target="_blank"
                   class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:text-rose-600 hover:border-rose-200 transition-all"
                   title="Print Result Sheet">
                    <i class="bi-printer"></i> Print
                </a>

                @if($isSubmitted)
                    @can('unlock', $testSubject)
                        <form action="{{ route('test-subject.unlock', $testSubject) }}" method="post">
                            @csrf @method('patch')
                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-amber-600 transition-all">
                                <i class="bi-unlock-fill"></i> Reopen
                            </button>
                        </form>
                    @else
                        <button disabled class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-400 rounded-xl text-[9px] font-bold uppercase tracking-widest cursor-not-allowed">
                            <i class="bi-lock-fill"></i> Locked
                        </button>
                    @endcan
                @else
                    {{-- Import students --}}
                    <a href="{{ route('test-subject.import.index', $testSubject) }}"
                       class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:text-indigo-600 hover:border-indigo-200 transition-all"
                       title="Import Students">
                        <i class="bi-person-plus"></i> Import
                    </a>
                    @if($studentCount)
                        <a href="{{ route('test-subject.results.edit', [$testSubject, 0]) }}"
                           class="flex items-center gap-2 px-4 py-2.5 bg-teal-600 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                            <i class="bi-pencil-square"></i> Enter Marks
                        </a>
                    @endif
                    @can('delete', $testSubject)
                        <form action="{{ route('test.test-subjects.destroy', [$testSubject->test, $testSubject]) }}"
                              method="POST" onsubmit="confirmDel(event)">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center bg-white border border-slate-200 text-rose-500 rounded-xl hover:bg-rose-50 hover:border-rose-200 transition-all">
                                <i class="bi-trash3 text-xs"></i>
                            </button>
                        </form>
                    @endcan
                @endif
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- ── Stat Strip ── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Students --}}
            <div class="bg-teal-600 rounded-2xl p-4 text-white shadow-xl shadow-teal-100 relative overflow-hidden flex flex-col justify-between">
                <div class="absolute -right-5 -bottom-5 w-20 h-20 bg-white/10 rounded-full"></div>
                <div class="w-9 h-9 rounded-2xl bg-white/20 flex items-center justify-center mb-4">
                    <i class="bi-people-fill text-white text-lg"></i>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-teal-200 uppercase tracking-widest mb-1">Students</p>
                    <p class="text-xl font-bold leading-none">{{ $studentCount }}</p>
                </div>
            </div>

            {{-- Max Marks --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div class="w-9 h-9 rounded-2xl bg-teal-50 flex items-center justify-center mb-4">
                    <i class="bi-award-fill text-teal-500 text-lg"></i>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Max Marks</p>
                    <p class="text-xl font-bold text-slate-800 leading-none">{{ $maxMarks }}</p>
                </div>
            </div>

            {{-- Class Average --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div class="w-9 h-9 rounded-2xl bg-indigo-50 flex items-center justify-center mb-4">
                    <i class="bi-bar-chart-fill text-indigo-500 text-lg"></i>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Class Avg</p>
                    <p class="text-xl font-bold text-slate-800 leading-none">{{ $classAvg }}<span class="text-slate-400 text-base font-bold"> / {{ $maxMarks }}</span></p>
                </div>
            </div>

            {{-- Pass Count --}}
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div class="w-9 h-9 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                    <i class="bi-check-circle-fill text-emerald-500 text-lg"></i>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Passed</p>
                    <p class="text-xl font-bold text-slate-800 leading-none">{{ $passCount }}<span class="text-slate-400 text-base font-bold"> / {{ $studentCount }}</span></p>
                </div>
            </div>
        </div>

        {{-- ── Results Panel ── --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">

            {{-- Toolbar --}}
            <div class="px-6 md:px-8 py-5 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/40">
                <div>
                    <p class="text-sm font-bold text-slate-700">Student Results</p>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">
                        {{ $studentCount }} enrolled &nbsp;·&nbsp; Max {{ $maxMarks }} pts each
                    </p>
                </div>
                <div class="relative w-full sm:w-72 group">
                    <input type="text" id="searchby" placeholder="Search student or father name…"
                           oninput="search(event)"
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-400 transition-all outline-none">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-teal-500 transition-colors text-xs"></i>
                </div>
            </div>

            {{-- Results table --}}
            <div class="overflow-x-auto">
                @if($results->count())
                    <table class=" table-fixed w-full border-collapse">
                        <thead>
                            <tr class="text-left border-b border-slate-50">
                                <th class="w-12 md:px-8 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest">#</th>
                                <th class="w-32 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Student</th>
                                <th class="w-24 py-4 text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">Marks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($results as $result)
                                @php
                                    $pct = $maxMarks > 0 ? round(($result->obtained_marks / $maxMarks) * 100) : 0;
                                    $passed = $result->obtained_marks >= ($maxMarks * 0.4);
                                    if($pct >= 90)      { $grade = 'A+'; $gradeColor = 'text-teal-700 bg-teal-50 border-teal-100'; }
                                    elseif($pct >= 80)  { $grade = 'A';  $gradeColor = 'text-teal-600 bg-teal-50 border-teal-100'; }
                                    elseif($pct >= 70)  { $grade = 'B';  $gradeColor = 'text-indigo-600 bg-indigo-50 border-indigo-100'; }
                                    elseif($pct >= 60)  { $grade = 'C';  $gradeColor = 'text-amber-700 bg-amber-50 border-amber-100'; }
                                    elseif($pct >= 40)  { $grade = 'D';  $gradeColor = 'text-orange-700 bg-orange-50 border-orange-100'; }
                                    else                { $grade = 'F';  $gradeColor = 'text-rose-700 bg-rose-50 border-rose-100'; }
                                @endphp
                                <tr class="tr group hover:bg-slate-50/60 transition-all">
                                    <td class="py-2 md:py-4">
                                        <div class="w-6 h-6 md:w-8 md:h-8 mx-auto rounded-xl flex items-center justify-center text-[9px] font-bold
                                            {{ $passed ? 'bg-teal-50 text-teal-700 border border-teal-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                            {{ $result->student->rollno }}
                                        </div>
                                    </td>
                                    <td class="py-2 md:py-4">
                                        <p class="text-[10px] font-bold text-slate-800 leading-tight">{{ $result->student->name }}</p>
                                        <p class="text-[9px] font-medium text-slate-400 mt-0.5">{{ $result->student->father_name }}</p>
                                    </td>
                                    <td class="py-2 md:py-4 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-[10px] font-bold {{ $passed ? 'text-slate-800' : 'text-rose-600' }}">
                                                {{ $result->obtained_marks }}
                                            </span>
                                            <div class="w-16 h-1 rounded-full bg-slate-100 overflow-hidden">
                                                <div class="h-full rounded-full {{ $passed ? 'bg-teal-500' : 'bg-rose-400' }}"
                                                     style="width: {{ $pct }}%"></div>
                                            </div>
                                            <span class="text-[9px] font-bold text-slate-400">{{ $pct }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="py-20 text-center">
                        <i class="bi-person-dash text-5xl text-slate-200 mb-4 block"></i>
                        <p class="text-sm font-bold text-slate-400">No students enrolled yet.</p>
                        @if(!$isSubmitted)
                            <a href="{{ route('test-subject.import.index', $testSubject) }}"
                               class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-teal-600 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-teal-700 transition-all">
                                <i class="bi-person-plus"></i> Import Students
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Final Submission CTA --}}
            @if(!$isSubmitted && $studentCount)
                <div class="px-6 md:px-8 py-8 border-t border-slate-50 bg-amber-50/40">
                    <div class="flex flex-col md:flex-row md:items-center gap-6">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-10 h-10 rounded-2xl bg-amber-100 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="bi-exclamation-triangle-fill text-amber-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 mb-1">Final Submission Required</p>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                    Once you submit, the result will be locked and will count towards official records.
                                    Ensure all marks have been entered before proceeding.
                                </p>
                            </div>
                        </div>
                        <form action="{{ route('test-subject.lock', $testSubject) }}" method="post" class="shrink-0">
                            @csrf @method('patch')
                            <button type="submit"
                                class="flex items-center gap-2 px-6 py-3 bg-amber-500 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-amber-600 hover:shadow-lg hover:shadow-amber-100 transition-all whitespace-nowrap">
                                <i class="bi-lock-fill"></i> Make Final Submission
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function search(event) {
            const q = event.target.value.toLowerCase();
            document.querySelectorAll('.tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        }

        function confirmDel(event) {
            event.preventDefault();
            const form = event.target;
            Swal.fire({
                title: 'Delete Subject Entry?',
                text: "All student results for this subject will be removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Yes, remove it'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }
    </script>
@endsection
