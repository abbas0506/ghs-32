@extends('layouts.app')

@section('page-content')
    <div class="space-y-6 pb-12">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 mb-2">
            <div>
                <div class="flex flex-wrap items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('attendance.summary') }}" class="hover:text-teal-600 transition-colors">Attendance</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Marking</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-calendar-check text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 leading-none mb-1">Class {{ $section->name }}</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Mark attendance for {{ now()->format('l, j M Y') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('attendance.summary', $section) }}" 
                   class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs uppercase hover:text-teal-600 hover:border-teal-200 hover:bg-teal-50 transition-all">
                   <i class="bi-arrow-left"></i> Back to Attendance
                </a>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Main Form Box -->
        <div class="max-w-4xl bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden mt-8">
            <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Student Roster</h2>
                    <p class="text-slate-400 text-xs font-medium italic mt-1">Found {{ $section->students->count() }} enrolled students</p>
                </div>
                
                <div class="relative w-full md:w-72 group">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-500 transition-colors"></i>
                    <input type="text" id='searchby' placeholder="Search student..." oninput="search(event)" class="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-medium focus:ring-4 focus:ring-teal-500/10 focus:bg-white transition-all outline-none">
                </div>
            </div>

            <form action="{{ route('section.attendance.store', $section) }}" method="post">
                @csrf
                <div class="overflow-x-auto pb-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-2 md:px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 w-12 md:w-20 text-center">Roll #</th>
                                <th class="px-2 md:px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 text-left whitespace-nowrap">Student Profile</th>
                                <th class="px-2 md:px-6 py-4 text-center w-24 border-l border-slate-100">
                                    <div class="flex flex-col items-center justify-center gap-2 text-[9px] font-bold uppercase tracking-widest text-teal-600">
                                        Mark All
                                        <label class="group/check relative flex items-center justify-center cursor-pointer">
                                            <input type="checkbox" id='chkAll' class="peer hidden" onclick="checkAll()">
                                            <div class="w-6 h-6 rounded-lg border-2 border-slate-200 bg-white flex items-center justify-center transition-all peer-checked:bg-teal-600 peer-checked:border-teal-600 group-hover/check:border-teal-300">
                                                <i class="bi bi-check-lg text-white opacity-0 peer-checked:opacity-100 scale-50 peer-checked:scale-100 transition-all text-xs"></i>
                                            </div>
                                        </label>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($section->students->sortBy('rollno') as $student)
                                <tr class="tr group hover:bg-slate-50/80 transition-colors">
                                    <td class="px-2 md:px-6 py-3 w-12 md:w-20 text-center align-middle">
                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold group-hover:bg-white group-hover:shadow-sm border border-transparent group-hover:border-slate-100 transition-all uppercase">
                                            {{ $student->rollno }}
                                        </div>
                                    </td>
                                    <td class="px-2 md:px-6 py-3 text-left align-middle overflow-hidden">
                                        <div class="flex flex-col">
                                            <span class="text-xs md:text-sm font-bold text-slate-700 group-hover:text-teal-900 transition-colors leading-tight truncate">{{ $student->name }}</span>
                                            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mt-1 truncate">{{ $student->father_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-2 md:px-6 py-3 text-center align-middle border-l border-slate-100 transition-colors">
                                        <div class="flex justify-center">
                                            <label class="group/check relative flex items-center justify-center w-10 h-10 cursor-pointer">
                                                <input type="checkbox" name="student_ids_array[]" value="{{ $student->id }}" class="peer hidden">
                                                <div class="w-6 h-6 rounded-lg border-2 border-slate-200 bg-white flex items-center justify-center transition-all peer-checked:bg-teal-600 peer-checked:border-teal-600 peer-checked:shadow-lg peer-checked:shadow-teal-100 group-hover/check:border-teal-300">
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
                   <button type="submit" class="flex items-center gap-2 px-8 py-3 bg-teal-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all active:scale-95">
                        <i class="bi-check2-circle text-lg"></i> Submit Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(0).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(1).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }

        function checkAll() {
            $('.tr').each(function() {
                if (!$(this).hasClass('hidden')) {
                    $(this).find('input[type=checkbox]').prop('checked', $('#chkAll').is(':checked'));
                }
            });
        }
    </script>
@endsection
