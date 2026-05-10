@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3">
                    <a href="{{ route('attendance.summary') }}" class="hover:text-teal-600 transition-colors">Attendance</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('section.attendance.index', $section) }}" class="hover:text-teal-600 transition-colors">{{ $section->name }}</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">Update</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <i class="bi-calendar-check text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 leading-none mb-1">{{ $section->name }}</h2>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->format('l, d M Y') }}</span>
                    </div>
                    
                </div>
                <div class="flex items-center gap-2 mt-4 justify-center">
                    <span class="px-2 py-0.5 bg-orange-50 text-orange-600 text-[8px] font-bold uppercase tracking-widest rounded-full border border-orange-100 italic">Attendance Editing Mode</span>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Attendance Form & Table -->
        <div class="bg-white rounded-[1rem] md:rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <!-- Table Controls -->
            <div class="p-5 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div class="relative w-full md:w-80 group">
                        <input type="text" id='searchby' placeholder="Search student name or roll..." oninput="search(event)"
                            class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors"></i>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Select All Present</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="chkAll" onclick="checkAll()" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                    </label>
                </div>
            </div>

            <form action="{{ route('section.attendance.update', [$section, 1]) }}" method="post" id="attendanceFlow">
                @csrf @method('PATCH')
                <div class="overflow-x-auto">
                    <table class="table-fixed w-full border-collapse">
                        <thead>
                            <tr class="text-left">
                                <th class="w-8 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">#</th>
                                <th class="w-32 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">Student Profile</th>
                                <th class="w-8 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($attendances->sortBy('rollno') as $attendance)
                                <tr class="tr group hover:bg-slate-50/80 transition-all">
                                    <input type="hidden" name="attendance_ids[]" value="{{ $attendance->id }}">
                                    <td class="">
                                        <div class=" w-6 h-6 md:w-8 md:h-8 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-[10px] font-bold text-slate-500 group-hover:bg-white group-hover:shadow-sm transition-all border border-transparent group-hover:border-slate-100 uppercase">
                                            {{ $attendance->student->rollno }}
                                        </div>
                                    </td>
                                    <td class="">
                                        <div class="flex flex-col">
                                            <p class="text-xs text-left font-semibold text-slate-800 leading-tight group-hover:text-teal-600 transition-colors">{{ $attendance->student->name }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <!-- <i class="bi bi-person text-[10px] text-slate-400"></i> -->
                                                <span class="text-[9px] text-slate-400 font-semibold">{{ $attendance->student->father_name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-2">
                                        <div class="flex justify-center">
                                            <label class="group/check relative flex items-center justify-center w-12 h-12 cursor-pointer">
                                                <input type="checkbox" name="attendance_ids_checked[]" value="{{ $attendance->id }}" 
                                                       @checked($attendance->status) 
                                                       class="peer hidden">
                                                <div class="w-5 h-5 rounded-lg border-2 border-slate-200 bg-white flex items-center justify-center transition-all peer-checked:bg-teal-600 peer-checked:border-teal-600 peer-checked:shadow-lg peer-checked:shadow-teal-100 group-hover/check:border-teal-300">
                                                    <i class="bi bi-check-lg text-white opacity-0 peer-checked:opacity-100 scale-50 peer-checked:scale-100 transition-all"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-50 bg-slate-50/30 flex items-center justify-end gap-3">
                   <button type="submit" class="flex items-center gap-2 px-8 py-3 bg-teal-600 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all active:scale-95">
                        <i class="bi-check2-circle text-lg"></i> Update Attendance
                    </button>
                </div>
            </form>
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

        window.checkAll = function() {
            const isChecked = document.getElementById('chkAll').checked;
            document.querySelectorAll('.tr:not(.hidden) input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        };
    </script>
@endsection
