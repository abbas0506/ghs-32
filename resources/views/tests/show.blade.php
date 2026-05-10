@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">

        @php
            $submitted  = $test->testSubjects()->mine()->resultSubmitted()->count();
            $total      = $test->testSubjects()->mine()->count();
            $percent    = $total > 0 ? round(($submitted / $total) * 100, 0) : 0;
            $todaySubmitted = $test->testSubjects()->resultSubmitted()->today()->count();
            $pending    = $total - $submitted;
        @endphp

        {{-- ── Header ── --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Details</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100 shrink-0">
                        <i class="bi-journal-check text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg md:text-xl font-bold text-slate-800 leading-none mb-2">{{ $test->title }}</h1>
                        <div class="flex items-center gap-2 flex-wrap">
                            @if($test->is_open)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-teal-700 text-[9px] font-bold uppercase tracking-widest rounded-full border border-teal-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span> Live
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-500 text-[9px] font-bold uppercase tracking-widest rounded-full border border-slate-200">
                                    <i class="bi-lock-fill text-[9px]"></i> Locked
                                </span>
                            @endif
                            <span class="text-[9px] font-bold text-slate-400">
                                <i class="bi-calendar3 mr-1"></i>{{ \Carbon\Carbon::parse($test->created_at)->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Bar --}}
            <div class="flex items-center gap-2 shrink-0">
                @role('admin|head')
                    @if ($test->is_open)
                        <a href="{{ route('test.test-subjects.create', $test) }}"
                           class="flex items-center gap-2 px-4 py-2.5 bg-slate-800 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-slate-600 hover:shadow-lg hover:shadow-slate-100 transition-all"
                           title="Add Subject">
                            <i class="bi-plus-lg"></i> Add Subject
                        </a>
                        <a href="{{ route('tests.edit', $test) }}"
                           class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 text-slate-500 rounded-xl hover:text-teal-600 hover:border-teal-200 transition-all"
                           title="Edit Test">
                            <i class="bi-pencil-square text-sm"></i>
                        </a>
                        @can('lock', $test)
                            <form action="{{ route('test.lock', $test) }}" method="post">
                                @csrf @method('patch')
                                <button type="submit"
                                    class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 text-amber-500 rounded-xl hover:bg-amber-50 hover:border-amber-200 transition-all"
                                    title="Lock Assessment">
                                    <i class="bi-unlock font-bold text-sm"></i>
                                </button>
                            </form>
                        @endcan
                        @can('delete', $test)
                            <form action="{{ route('tests.destroy', $test) }}" method="POST" onsubmit="confirmDel(event)">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 text-rose-500 rounded-xl hover:bg-rose-50 hover:border-rose-200 transition-all"
                                    title="Delete">
                                    <i class="bi-trash3 text-sm"></i>
                                </button>
                            </form>
                        @endcan
                    @else
                        @can('unlock', $test)
                            <form action="{{ route('test.unlock', $test) }}" method="post" class="w-full">
                                @csrf @method('patch')
                                <button type="submit"
                                    class="flex items-center justify-center w-full gap-2 px-5 py-2.5 bg-slate-800 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-slate-900 hover:shadow-lg hover:shadow-slate-100 transition-all">
                                    <i class="bi-unlock-fill"></i> Reopen Assessment
                                </button>
                            </form>
                        @endcan
                    @endif
                @endrole
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- ── Stat Strip ── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            {{-- Completion % --}}
            <div class="bg-teal-600 rounded-3xl p-5 text-white shadow-xl shadow-teal-100 relative overflow-hidden flex items-center gap-5">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full"></div>
                <div>
                    <p class="text-[9px] font-bold text-teal-200 uppercase tracking-widest mb-3">Submitted</p>
                    <p class="text-2xl font-bold leading-none">{{ $submitted }}<span class="text-teal-300/60 text-sm font-bold"> / {{ $total }}</span></p>
                    <!-- <p class="text-[9px] text-teal-100/70 font-bold mt-1">subjects submitted</p> -->
                </div>
            </div>

            {{-- Pending --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pending</p>
                    <div class="w-8 h-8 rounded-2xl bg-rose-50 flex items-center justify-center">
                        <i class="bi-hourglass-split text-rose-500 text-sm"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-bold text-slate-800 leading-none">{{ $pending }}</span>
                    <span class="text-[8px] font-bold text-slate-400 uppercase">subjects</span>
                </div>
            </div>

            {{-- Today --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Today</p>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <i class="bi-lightning-charge-fill text-indigo-500 text-sm"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-bold text-slate-800 leading-none">+{{ $todaySubmitted }}</span>
                    <span class="text-[8px] font-bold text-slate-400 uppercase">new</span>
                </div>
            </div>

            {{-- Max Marks --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Max Marks</p>
                    <div class="w-8 h-8 rounded-xl bg-teal-50 flex items-center justify-center">
                        <i class="bi-award-fill text-teal-500 text-sm"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-bold text-slate-800 leading-none">{{ $test->max_marks }}</span>
                    <span class="text-[8px] font-bold text-slate-400 uppercase">pts</span>
                </div>
            </div>
        </div>

        {{-- ── Main Content Panel ── --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">

            @if ($test->is_open)
                {{-- Filter & Search toolbar --}}
                <div class="px-6 md:px-8 py-5 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/40">
                    <div class="flex flex-wrap items-center gap-1 w-full sm:w-auto">
                        <button id="filter-all"
                            onclick="filterBy('all', this)"
                            class="filter-btn active-filter flex-1 sm:flex-none flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all bg-teal-600 text-white">
                            <i class="bi-grid-fill"></i> All
                        </button>
                        <button id="filter-submitted"
                            onclick="filterBy('submitted', this)"
                            class="filter-btn flex-1 sm:flex-none flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">
                            <i class="bi-check-circle-fill text-teal-500"></i> Done
                        </button>
                        <button id="filter-pending"
                            onclick="filterBy('pending', this)"
                            class="filter-btn flex-1 sm:flex-none flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">
                            <i class="bi-hourglass-split text-rose-500"></i> Pending
                        </button>
                    </div>
                    <div class="relative w-full sm:w-72 group">
                        <input type="text" id="searchby" placeholder="Search subject or teacher…"
                               oninput="search(event)"
                               class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-400 transition-all outline-none">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-teal-500 transition-colors text-xs"></i>
                    </div>
                </div>

                {{-- Subject rows --}}
                <div class="divide-y divide-slate-50">
                    @forelse ($test->testSubjects()->mine()->get()->sortBy(['section_id', 'lecture_no']) as $testSubject)
                        @php $isSubmitted = $testSubject->result_date; @endphp
                        <a href="{{ route('test.test-subjects.show', [$test, $testSubject]) }}"
                           class="tr group flex items-center gap-4 px-6 md:px-8 py-5 hover:bg-white hover:shadow-2xl hover:shadow-teal-100/50 hover:-translate-y-0.5 transition-all duration-300 relative {{ $isSubmitted ? 'submitted' : 'pending' }}">

                            <div class="flex-1 min-w-0 flex flex-col gap-1.5">
                                {{-- Row 1: Subject & Status --}}
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="font-bold text-slate-800 group-hover:text-teal-600 transition-colors leading-tight text-sm truncate sm:whitespace-normal">
                                            {{ $testSubject->subject->name }}
                                        </span>
                                        @if($testSubject->hasBeenSubmittedToday())
                                            <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 bg-teal-500 text-white text-[7px] font-black uppercase tracking-tighter rounded-md animate-pulse">NEW</span>
                                        @endif
                                    </div>

                                    <div class="shrink-0">
                                        @if ($isSubmitted)
                                            <i class="bi-check-circle-fill text-xs text-teal-600"></i>
                                        @else
                                            <i class="bi-clock-fill text-xs text-rose-600"></i>
                                        @endif
                                    </div>
                                </div>

                                {{-- Row 2: Metadata --}}
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-slate-400 uppercase tracking-tight">
                                        <i class="bi-collection text-slate-300"></i> {{ $testSubject->section->name }}
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-slate-400 uppercase tracking-tight">
                                        <i class="bi-person text-[10px]"></i> {{ $testSubject->user?->profile->short_name ?? '—' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Centered Chevron --}}
                            <i class="bi-chevron-right text-slate-300 group-hover:text-teal-500 group-hover:translate-x-1 transition-all duration-300"></i>
                        </a>
                    @empty
                        <div class="px-8 py-16 text-center">
                            <i class="bi-inbox text-4xl text-slate-200 mb-3 block"></i>
                            <p class="text-sm font-bold text-slate-400">No subjects assigned yet.</p>
                        </div>
                    @endforelse
                </div>

            @else
                {{-- ── Archived / Report View ── --}}
                <div class="px-6 md:px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-700">Generate Reports</p>
                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">Download result sheets and report cards per section</p>
                    </div>
                    <span class="px-3 py-1.5 bg-slate-100 text-slate-500 text-[9px] font-bold uppercase tracking-widest rounded-full border border-slate-200">
                        <i class="bi-lock-fill mr-1"></i>Archived
                    </span>
                </div>

                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach ($sections as $section)
                            <div class="group bg-slate-50/60 rounded-3xl p-6 border border-slate-100 hover:border-teal-200 hover:shadow-md hover:shadow-teal-50 transition-all duration-300">
                                <div class="flex items-center gap-3 mb-5">
                                    <div class="w-10 h-10 rounded-2xl bg-white text-teal-600 flex items-center justify-center shadow-sm border border-slate-100">
                                        <i class="bi-collection-play text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-800 leading-tight">{{ $section->name }}</h3>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Section Reports</p>
                                    </div>
                                </div>

                                <div class="grid gap-2">
                                    <a href="{{ route('section-result', [$test, $section]) }}" target="_blank"
                                       class="flex items-center justify-between p-3.5 bg-white rounded-2xl border border-slate-100 hover:border-rose-200 hover:shadow-sm group/link transition-all">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-xl bg-rose-50 flex items-center justify-center">
                                                <i class="bi-file-earmark-pdf text-rose-500 text-sm"></i>
                                            </div>
                                            <span class="text-[9px] font-bold text-slate-600 group-hover/link:text-rose-600 transition-colors">Result Sheet</span>
                                        </div>
                                        <i class="bi-box-arrow-up-right text-xs text-slate-300 group-hover/link:text-rose-500 transition-colors"></i>
                                    </a>
                                    <a href="{{ route('report-cards', [$test, $section]) }}" target="_blank"
                                       class="flex items-center justify-between p-3.5 bg-white rounded-2xl border border-slate-100 hover:border-rose-200 hover:shadow-sm group/link transition-all">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-xl bg-rose-50 flex items-center justify-center">
                                                <i class="bi-postcard text-rose-500 text-sm"></i>
                                            </div>
                                            <span class="text-[9px] font-bold text-slate-600 group-hover/link:text-rose-600 transition-colors">Report Cards</span>
                                        </div>
                                        <i class="bi-box-arrow-up-right text-xs text-slate-300 group-hover/link:text-rose-500 transition-colors"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script type="module">
        $(document).ready(function () {

            window.search = function (event) {
                const q = event.target.value.toLowerCase();
                $('.tr').each(function () {
                    $(this).toggle($(this).text().toLowerCase().includes(q));
                });
            };

            window.filterBy = function (criteria, btn) {
                // Pill states
                $('.filter-btn').removeClass('bg-teal-600 text-white shadow-sm').addClass('text-slate-500');
                $(btn).removeClass('text-slate-500').addClass('bg-teal-600 text-white shadow-sm');

                if (criteria === 'all') {
                    $('.tr').show();
                } else {
                    $('.tr').each(function () {
                        $(this).toggle($(this).hasClass(criteria));
                    });
                }
            };

            window.confirmDel = function (event) {
                event.preventDefault();
                const form = event.target;
                Swal.fire({
                    title: 'Delete Assessment?',
                    text: "All associated subject data will be permanently removed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            };
        });
    </script>
@endsection
