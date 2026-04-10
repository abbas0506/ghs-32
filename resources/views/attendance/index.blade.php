@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ route('attendance.summary') }}" class="hover:text-teal-600 transition-colors">Attendance Center</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Class Details</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-people-fill text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 leading-none mb-1">{{ $section->name }}</h1>
                        <p class="text-slate-400 text-xs font-medium italic">{{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</p>
                    </div>
                </div>
            </div>

            @if (\Carbon\Carbon::parse($date)->isToday())
                <a href="{{ route('section.attendance.edit', [$section, 1]) }}" 
                   class="flex items-center gap-2 px-6 py-3 bg-teal-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                   <i class="bi-pencil-square text-white"></i> Edit Today's Record
                </a>
            @endif
        </div>

        @php
            $presentCount = $attendances->where('status', 1)->count();
            $total = $attendances->count();
            $absentCount = $total - $presentCount;
            $percentage = $total > 0 ? round(($presentCount / $total) * 100, 1) : 0;
            $themeColor = $percentage >= 90 ? 'teal' : ($percentage >= 75 ? 'cyan' : 'rose');
        @endphp

        <!-- Quick Summary Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-teal-600 rounded-3xl p-6 text-white shadow-xl shadow-teal-100 flex items-center justify-between col-span-2 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full"></div>
                <div>
                    <p class="text-[10px] font-black text-teal-100 uppercase tracking-widest mb-1">Attendance Rate</p>
                    <h2 class="text-4xl font-black text-white">{{ $percentage }}%</h2>
                </div>
                <div class="text-right">
                    <div class="w-12 h-12 rounded-full border-4 border-white/10 flex items-center justify-center relative">
                        <svg class="w-10 h-10 transform -rotate-90">
                            <circle cx="20" cy="20" r="18" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white/10" />
                            <circle cx="20" cy="20" r="18" stroke="currentColor" stroke-width="3" fill="transparent" class="text-white" 
                                stroke-dasharray="113" stroke-dashoffset="{{ 113 - (113 * $percentage / 100) }}" />
                        </svg>
                        <i class="bi-check-all absolute text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl md:rounded-3xl p-4 md:p-6 border border-slate-100 shadow-sm flex flex-col justify-center">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Present</p>
                    <i class="bi-person-check-fill text-teal-600 opacity-60"></i>
                </div>
                <h2 class="text-xl md:text-2xl font-black text-slate-800">
                    {{ $presentCount }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl md:rounded-3xl p-4 md:p-6 border border-slate-100 shadow-sm flex flex-col justify-center">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Absent</p>
                    <i class="bi-person-x-fill text-rose-600 opacity-60"></i>
                </div>
                <h2 class="text-xl md:text-2xl font-black text-rose-600">
                    {{ $absentCount }}
                </h2>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Main Content Table -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <!-- Table Controls -->
            <div class="px-4 md:px-8 py-4 md:py-6 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center flex-wrap gap-1 bg-white p-1 rounded-xl shadow-sm border border-slate-100 w-full md:w-auto">
                    <button onclick="filterBy('all')" class="flex-1 md:flex-none px-3 md:px-5 py-2 rounded-lg text-[10px] md:text-xs font-black uppercase tracking-widest cursor-pointer hover:bg-slate-50 transition-all text-slate-600">All</button>
                    <button onclick="filterBy('present')" class="flex-1 md:flex-none px-3 md:px-5 py-2 rounded-lg text-[10px] md:text-xs font-black uppercase tracking-widest cursor-pointer bg-teal-50 text-teal-700 hover:bg-teal-100 transition-all">Present</button>
                    <button onclick="filterBy('absent')" class="flex-1 md:flex-none px-3 md:px-5 py-2 rounded-lg text-[10px] md:text-xs font-black uppercase tracking-widest cursor-pointer bg-rose-50 text-rose-700 hover:bg-rose-100 transition-all">Absent</button>
                </div>
                <div class="relative w-full md:w-80 group">
                    <input type="text" id='searchby' placeholder="Search by name or roll..." oninput="search(event)"
                        class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors"></i>
                </div>
            </div>

            <!-- Table Body -->
            <div class="overflow-x-auto">
                <table class=" table-fixed w-full border-collapse">
                    <thead>
                        <tr class="">
                            <th class="w-16 px-5 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Roll #</th>
                            <th class="w-48 px-5 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Student Profile</th>
                            <th class="w-32 px-5 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Status</th>
                            <th class="w-16 px-5 py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($attendances as $attendance)
                            @php $isStudentPresent = $attendance->status == 1; @endphp
                            <tr class="tr group hover:bg-slate-50/80 transition-all {{ $isStudentPresent ? 'present' : 'absent' }}">
                                <td class="py-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-xs font-black text-slate-600 group-hover:bg-white group-hover:shadow-sm transition-all border border-transparent group-hover:border-slate-100 uppercase">
                                        {{ $attendance->student->rollno }}
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col">
                                        <p class="text-xs font-semibold text-left text-slate-800 leading-tight group-hover:text-teal-600 transition-colors">{{ $attendance->student->name }}</p>
                                        <div class="flex items-center gap-3 mt-1.5 font-medium">
                                            <span class="text-[10px] text-slate-400 uppercase flex items-center gap-1">
                                                <i class="bi bi-person text-xs"></i> {{ $attendance->student->father_name }}
                                            </span>
                                            </div>
                                            <div>
                                            <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                            <span class="text-[10px] text-slate-400 flex items-center gap-1">
                                                <i class="bi bi-telephone text-[10px]"></i> {{ $attendance->student->phone }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <div class="flex justify-center">
                                        @if ($isStudentPresent)
                                            <span class="px-3 py-1 bg-teal-50 text-teal-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-teal-100 shadow-sm">Present</span>
                                        @else
                                            <span class="px-3 py-1 bg-rose-50 text-rose-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-rose-100 shadow-sm">Absent</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-2 text-center">
                                    <a href="{{ route('section.attendance.show', [$section, $attendance]) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-teal-600 hover:border-teal-200 hover:shadow-sm transition-all">
                                        <i class="bi-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="flex items-center justify-center pt-8">
            <a href="{{ route('attendance.summary') }}" class="flex items-center gap-2 px-8 py-3 rounded-2xl text-xs font-black text-slate-500 hover:bg-slate-100 transition-all uppercase tracking-widest">
                <i class="bi-arrow-left"></i> Back to Main Summary
            </a>
        </div>
    </div>

    <script type="module">
        window.search = function(event) {
            const searchtext = event.target.value.toLowerCase();
            document.querySelectorAll('.tr').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.classList.toggle('hidden', !text.includes(searchtext));
            });
        };

        window.filterBy = function(criteria) {
            document.querySelectorAll('.tr').forEach(row => {
                if (criteria === 'all') {
                    row.classList.remove('hidden');
                } else {
                    row.classList.toggle('hidden', !row.classList.contains(criteria));
                }
            });
        };
    </script>
@endsection
