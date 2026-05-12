@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 mb-6">
        <div>
            <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3">
                <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                <i class="bi-chevron-right text-[8px]"></i>
                <a href="{{ route('sections.index') }}" class="hover:text-teal-600 transition-colors">Classes</a>
                <i class="bi-chevron-right text-[8px]"></i>
                <a href="{{ route('sections.show', $section) }}" class="hover:text-teal-600 transition-colors">{{ $section->name }}</a>
                <i class="bi-chevron-right text-[8px]"></i>
                <span class="text-teal-600">ID Cards</span>
            </div>
            <h2 class="text-base font-bold text-gray-800 leading-none">Generate ID Cards</h2>
        </div>
    </div>

        <div class="w-full md:w-4/5 mx-auto bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden mt-8">
            <div class="p-6 border-b border-slate-50">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="ri-group-line text-teal-600"></i> Class {{ $section->name }}
                </h3>
                <p class="text-slate-400 text-[10px] uppercase font-bold tracking-widest mt-1">
                    {{ $section->students->count() }} Students Enrolled
                </p>
            </div>

            <form action="{{ route('section.cards.store', $section) }}" method="post">
                @csrf
                <div class="flex items-center justify-between gap-4">
                    <div class="relative w-full md:w-64 group">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-500 transition-colors"></i>
                        <input type="text" 
                            id="searchby" 
                            placeholder="Search students..." 
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-xs font-bold focus:ring-4 focus:ring-teal-500/10 focus:bg-white transition-all outline-none"
                            oninput="search(event)"
                        >
                    </div>

                    <button type="submit" class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center hover:bg-teal-600 hover:text-white transition-all shadow-sm">
                        <i class="bi bi-printer text-lg"></i>
                    </button>
                </div>
                <!-- page message -->
                @if ($errors->any())
                    <x-message :errors='$errors'></x-message>
                @else
                    <x-message></x-message>
                @endif

                <div class="overflow-x-auto w-full mt-8">

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="w-10 text-[10px] font-bold uppercase tracking-widest text-slate-400 w-16">Roll</th>
                                <th class="text-[10px] font-bold uppercase tracking-widest text-slate-400 text-left">Student Info</th>
                                <th class="text-[10px] font-bold uppercase tracking-widest text-slate-400 w-20 text-center">Photo</th>
                                <th class="text-right w-16">
                                    <input type="checkbox" id="chkAll" class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500" onclick="checkAll()">
                                </th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            @foreach ($section->students as $student)
                                <tr class="tr group hover:bg-teal-50/30 transition-colors border-b border-slate-50">
                                    <td class="px-3 py-2">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 text-teal-700 text-[10px] font-bold">
                                            {{ $student->rollno }}
                                        </span>
                                    </td>
                                    <td class="text-left">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-bold text-slate-700">{{ $student->name }}</span>
                                            <span class="text-[9px] text-slate-400 uppercase tracking-wide mt-0.5">{{ $student->father_name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}" alt="photo" class="w-8 h-8 rounded-lg object-cover mx-auto shadow-sm ring-2 ring-white">
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center mx-auto">
                                                <i class="ri-user-line text-slate-300 text-xs"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500 transition-all" name="student_ids_array[]" value="{{ $student->id }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function search(event) {
            // var searchtext = event.target.value.toLowerCase();
            var searchtext = $('#searchby').val().toLowerCase();
            var str = 0;
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(2).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }

        function checkAll() {

            $('.tr').each(function() {
                if (!$(this).hasClass('hidden'))
                    $(this).children().find('input[type=checkbox]').prop('checked', $('#chkAll').is(':checked'));
                // updateChkCount()
            });
        }
    </script>
@endsection
