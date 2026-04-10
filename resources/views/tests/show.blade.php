@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ route('tests.index') }}" class="hover:text-teal-600 transition-colors">Assessment</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Test Details</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-journal-check text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 leading-none mb-1">{{ $test->title }}</h2>
                        <div class="flex items-center gap-2 mt-1">
                            @if($test->is_open)
                                <span class="px-2 py-0.5 bg-teal-50 text-teal-600 text-[8px] font-black uppercase tracking-widest rounded-full border border-teal-100">Live Assessment</span>
                            @else
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[8px] font-black uppercase tracking-widest rounded-full border border-slate-200">Archive Only</span>
                            @endif
                            <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($test->created_at)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex items-center gap-2">
                @role('admin|head')
                    @if ($test->is_open)
                        <a href="{{ route('test.test-subjects.create', $test) }}" 
                           class="w-10 h-10 flex items-center justify-center bg-teal-600 text-white rounded-xl hover:bg-teal-700 hover:shadow-lg transition-all" title="Add Subject">
                           <i class="bi-plus-lg text-lg"></i>
                        </a>
                        <a href="{{ route('tests.edit', $test) }}" 
                           class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 text-slate-500 rounded-xl hover:text-teal-600 hover:border-teal-200 transition-all" title="Edit Test">
                           <i class="bi-pencil-square"></i>
                        </a>
                        @can('lock', $test)
                            <form action="{{ route('test.lock', $test) }}" method='post'>
                                @csrf @method('patch')
                                <button type="submit" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 text-orange-500 rounded-xl hover:bg-orange-50 hover:border-orange-200 transition-all" title="Lock results">
                                    <i class="bi-unlock font-black"></i>
                                </button>
                            </form>
                        @endcan
                        @can('delete', $test)
                            <form action="{{ route('tests.destroy', $test) }}" method="POST" onsubmit="confirmDel(event)">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 text-rose-500 rounded-xl hover:bg-rose-50 hover:border-rose-200 transition-all" title="Delete">
                                    <i class="bi-trash3"></i>
                                </button>
                            </form>
                        @endcan
                    @else
                        @can('unlock', $test)
                            <form action="{{ route('test.unlock', $test) }}" method='post'>
                                @csrf @method('patch')
                                <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-rose-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-700 transition-all">
                                    <i class="bi-lock-fill"></i> Unlock Assessment
                                </button>
                            </form>
                        @endcan
                    @endif
                @endrole
            </div>
        </div>

        @php
            $submitted = $test->testSubjects()->mine()->resultSubmitted()->count();
            $total = $test->testSubjects()->mine()->count();
            $percent = $total > 0 ? round(($submitted / $total) * 100, 0) : 0;
            $todaySubmitted = $test->testSubjects()->resultSubmitted()->today()->count();
        @endphp

        <!-- Overview Card -->
        <div class="bg-teal-600 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-teal-100">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex-1">
                    <p class="text-teal-100 text-[10px] font-black uppercase tracking-[0.2em] mb-3">Submission Summary</p>
                    <h4 class="text-2xl font-black mb-2">{{ $percent }}% <span class="text-teal-200/60 font-normal italic">Progress</span></h4>
                    <div class="flex items-center gap-4 mt-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-teal-100/60 uppercase">Subjects</span>
                            <span class="text-lg font-bold">{{ $submitted }} <span class="text-teal-200/50 text-xs">/ {{ $total }}</span></span>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-teal-100/60 uppercase">Today</span>
                            <span class="text-lg font-bold text-white">+{{ $todaySubmitted }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="relative flex items-center justify-center w-32 h-32">
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="64" cy="64" r="54" stroke="currentColor" stroke-width="8" fill="transparent" class="text-white/10" />
                        <circle cx="64" cy="64" r="54" stroke="currentColor" stroke-width="8" fill="transparent" class="text-white" 
                            stroke-dasharray="339.29" stroke-dashoffset="{{ 339.29 - (339.29 * $percent / 100) }}"
                            stroke-linecap="round" />
                    </svg>
                    <div class="absolute flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-white">{{ $percent }}%</span>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Content Area -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            @if ($test->is_open)
                <!-- Filters & Search -->
                <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center flex-wrap gap-1 bg-white p-1 rounded-xl shadow-sm border border-slate-100 w-full md:w-auto">
                        <button onclick="filterBy('all')" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2 rounded-lg text-xs font-black uppercase tracking-widest cursor-pointer hover:bg-white transition-all text-slate-600">
                            <i class="bi-grid-fill"></i> All
                        </button>
                        <button onclick="filterBy('submitted')" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2 rounded-lg text-xs font-black uppercase tracking-widest cursor-pointer bg-teal-50 text-teal-700 hover:bg-teal-100 transition-all">
                            <i class="bi-check-circle-fill"></i> Submitted
                        </button>
                        <button onclick="filterBy('pending')" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2 rounded-lg text-xs font-black uppercase tracking-widest cursor-pointer bg-rose-50 text-rose-700 hover:bg-rose-100 transition-all">
                            <i class="bi-hourglass-split"></i> Pending
                        </button>
                    </div>
                    <div class="relative w-full md:w-80 group">
                        <input type="text" id='searchby' placeholder="Search subjects or teachers..." oninput="search(event)"
                            class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors"></i>
                    </div>
                </div>

                <!-- Subjects Table -->
                <div class="overflow-x-auto">
                    <table class="table-fixed w-full border-collapse">
                        <thead>
                            <tr class="text-left">
                                <th class="w-16 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">#</th>
                                <th class="w-40 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Subject & Instructor</th>
                                <th class="w-24 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 text-center">Status</th>
                                <th class="w-16"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($test->testSubjects()->mine()->get()->sortBy(['section_id', 'lecture_no']) as $testSubject)
                                @php $isSubmitted = $testSubject->result_date; @endphp
                                <tr class="tr group hover:bg-slate-50/80 transition-all {{ $isSubmitted ? 'submitted' : 'pending' }}">
                                    <td class="p-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mx-auto text-[10px] font-black {{ $isSubmitted ? 'bg-teal-50 text-teal-600' : 'bg-slate-50 text-slate-400' }} transition-colors border {{ $isSubmitted ? 'border-teal-100' : 'border-slate-100' }}">
                                            {{ $loop->iteration }}
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex flex-col">
                                            <a href="{{ route('test.test-subjects.show', [$test, $testSubject]) }}" class="text-sm font-semibold text-slate-800 hover:text-teal-600 transition-colors leading-tight text-left">
                                                {{ $testSubject->subject->short_name }} — {{ $testSubject->section->name }}
                                                @if($testSubject->hasBeenSubmittedToday())
                                                    <span class="ml-1 text-teal-500 text-[8px] animate-pulse">● NEW</span>
                                                @endif
                                            </a>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <i class="bi bi-person text-[10px] text-teal-600/50"></i>
                                                <span class="text-[10px] text-teal-600 font-semibold uppercase tracking-tight">{{ $testSubject->user?->profile->short_name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex justify-center">
                                            @if ($isSubmitted)
                                                <span class="px-3 py-1 bg-teal-50 text-teal-700 text-[9px] font-black uppercase tracking-widest rounded-full border border-teal-100">Submitted</span>
                                            @else
                                                <span class="px-3 py-1 bg-rose-50 text-rose-700 text-[9px] font-black uppercase tracking-widest rounded-full border border-rose-100">Pending</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <a href="{{ route('test.test-subjects.show', [$test, $testSubject]) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-teal-600 hover:border-teal-200 transition-all">
                                            <i class="bi-chevron-right text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Archived View (Test Closed) -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($sections as $section)
                            <div class="group bg-slate-50 rounded-3xl p-6 border border-slate-100 hover:border-teal-200 hover:bg-teal-50/20 transition-all">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-white text-teal-600 flex items-center justify-center shadow-sm">
                                            <i class="bi-collection-play text-lg"></i>
                                        </div>
                                        <h3 class="font-black text-slate-800 leading-tight">{{ $section->name }}</h3>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Reports</span>
                                </div>
                                
                                <div class="grid gap-3">
                                    <a href="{{ route('section-result', [$test, $section]) }}" target="_blank" 
                                       class="flex items-center justify-between p-3 bg-white rounded-xl border border-slate-100 hover:border-rose-200 group/link transition-all">
                                        <div class="flex items-center gap-2">
                                            <i class="bi-file-earmark-pdf text-rose-600"></i>
                                            <span class="text-xs font-bold text-slate-600">Result Sheet</span>
                                        </div>
                                        <i class="bi-printer text-slate-300 group-hover/link:text-rose-600 transition-colors"></i>
                                    </a>
                                    <a href="{{ route('report-cards', [$test, $section]) }}" target="_blank" 
                                       class="flex items-center justify-between p-3 bg-white rounded-xl border border-slate-100 hover:border-rose-200 group/link transition-all">
                                        <div class="flex items-center gap-2">
                                            <i class="bi-postcard text-rose-600"></i>
                                            <span class="text-xs font-bold text-slate-600">Result Cards</span>
                                        </div>
                                        <i class="bi-printer text-slate-300 group-hover/link:text-rose-600 transition-colors"></i>
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
        $(document).ready(function() {
            window.search = function(event) {
                var searchtext = event.target.value.toLowerCase();
                $('.tr').each(function() {
                    var content = $(this).text().toLowerCase();
                    $(this).toggle(content.indexOf(searchtext) > -1);
                });
            }

            window.filterBy = function(criteria) {
                if (criteria == 'all') {
                    $('.tr').show();
                } else {
                    $('.tr').each(function() {
                        $(this).toggle($(this).hasClass(criteria));
                    });
                }
            }

            window.confirmDel = function(event) {
                event.preventDefault();
                var form = event.target;
                Swal.fire({
                    title: 'Delete Assessment?',
                    text: "All associated subject data will be removed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', // rose-600
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            }
        });
    </script>
@endsection
