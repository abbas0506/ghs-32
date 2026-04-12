@extends('layouts.app')
@section('page-content')
    <div class="space-y-8 pb-12">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 mb-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('sections.index') }}" class="hover:text-teal-600 transition-colors">Classes</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Allocations</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-calendar-week text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 leading-none mb-1">Section Allocations</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Manage class timetables and teacher assignments</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ url('user-schedule') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-teal-50 hover:text-teal-600 transition-colors" title="Teacher-wise Timetable">
                    <i class="bi-person-badge text-lg"></i>
                </a>
                <a href="{{ route('schedule-slips') }}" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-cyan-50 hover:text-cyan-600 transition-colors" title="Clean Slips View">
                    <i class="bi-grid-3x3-gap text-lg"></i>
                </a>
                <div class="h-6 w-px bg-slate-200"></div>
                <form action="{{ url('class-schedule/clear') }}" method="post" onsubmit="return confirmClear(event)" class="m-0">
                    @csrf
                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 transition-colors" title="Clear Data">
                        <i class="bi-recycle text-lg"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Toolbar -->
        <div class="flex flex-col xl:flex-row justify-between items-center gap-4 bg-white p-4 rounded-[1.5rem] border border-slate-100 shadow-sm">
            <div class="relative flex-1 w-full xl:max-w-sm group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-500 transition-colors"></i>
                <input type="text" id='searchby' placeholder="Search section..." class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none" oninput="search(event)">
            </div>

            <div class="flex flex-wrap md:flex-nowrap items-center gap-4 w-full xl:w-auto">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" id="chkAll" class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300 transition-colors cursor-pointer" onclick="checkAll()" checked>
                    <span class="text-[10px] font-black text-slate-600 group-hover:text-teal-600 uppercase tracking-widest transition-colors">Print All Sections</span>
                </label>
                <div class="h-5 w-px bg-slate-200 hidden md:block"></div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <button type="button" onclick="submitForm('landscape')" class="flex-1 md:flex-none justify-center flex items-center gap-2 px-5 py-2.5 bg-slate-800 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-700 hover:shadow-lg hover:shadow-slate-800/20 transition-all">
                        <i class="bi-view-stacked"></i> Unified <span class="hidden md:inline">(Landscape)</span>
                    </button>
                    <button type="button" onclick="submitForm('portrait')" class="flex-1 md:flex-none justify-center flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-teal-700 hover:shadow-lg hover:shadow-teal-100 transition-all">
                        <i class="bi-layout-split"></i> Section-wise <span class="hidden md:inline">(Portrait)</span>
                    </button>
                </div>
            </div>
        </div>

        <form action="{{ route('class-schedule.post') }}" method="post" id='form_sections'>
            @csrf
            <input type="hidden" name="layout" id="layout" value="landscape">
            <div class="bg-white border border-slate-100 rounded-[2rem] shadow-xl shadow-slate-200/40 overflow-hidden">
                <div class="overflow-x-auto text-sm">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="chk w-[5%] hidden px-4 py-4"></th>
                                <th class="px-6 py-4 text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase w-32 sticky left-0 z-10 bg-slate-50 border-r border-slate-100">Class</th>
                                @foreach ($lectures as $lecture)
                                    <th class="px-4 py-3 border-l border-slate-100 text-center min-w-[110px]">
                                        <div class="text-[11px] font-black text-slate-700 mb-1 uppercase tracking-widest">Period {{ $lecture->lecture_no }}</div>
                                        <div class="inline-flex items-center justify-center px-1.5 py-0.5 rounded text-[9px] font-bold tracking-widest bg-white border border-slate-200 text-slate-500 shadow-sm">
                                            <i class="bi-clock mr-1 opacity-70"></i> {{ $lecture->starts_at->format('H:i') }}
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($sections as $section)
                                <tr class="tr hover:bg-slate-50/50 transition-colors group/row">
                                    <td class="chk hidden px-4 py-3 align-middle text-center border-b border-slate-100">
                                        <input type="checkbox" class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300" name="section_ids_array[]" value="{{ $section->id }}" checked>
                                    </td>
                                    <td class="px-6 py-4 font-black text-slate-700 text-sm border-r border-slate-100 sticky left-0 z-10 bg-white group-hover/row:bg-slate-50/50 transition-colors tracking-tight">
                                        {{ $section->name }}
                                    </td>
                                    @foreach ($lectures as $lecture)
                                        <td class="p-2 border-l border-slate-100 align-top bg-white group-hover/row:bg-slate-50/50">
                                            <div class="flex flex-col gap-1.5 h-full">
                                                @foreach ($section->schedules()->havingLectureNo($lecture->lecture_no)->get() as $allocation)
                                                    <a href="{{ route('section.lecture.schedule.edit', [$section, $lecture->lecture_no, $allocation]) }}"
                                                        class="block bg-gradient-to-br from-teal-50 to-emerald-50 hover:from-teal-100 hover:to-emerald-100 border border-teal-100/60 rounded-xl p-2 text-center transition-all hover:-translate-y-0.5 hover:shadow-md hover:shadow-teal-100/50 relative group">
                                                        <h4 class="text-[10px] font-black text-teal-800 uppercase tracking-widest mb-1 truncate">{{ $allocation->subject->short_name }}</h4>
                                                        <p class="text-[9px] font-bold text-teal-600/90 bg-white/70 rounded px-1.5 py-0.5 inline-block truncate max-w-full">{{ $allocation->user->profile?->short_name ?? 'N/A' }}</p>
                                                    </a>
                                                @endforeach
                                                <a href="{{ route('section.lecture.schedule.create', [$section, $lecture->lecture_no]) }}" 
                                                   class="flex-1 w-full min-h-[36px] flex items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50/50 text-slate-400 hover:text-teal-600 hover:border-teal-400 hover:bg-teal-50 transition-all text-sm group/add">
                                                    <i class="bi-plus-lg group-hover/add:scale-125 transition-transform"></i>
                                                </a>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        function confirmClear(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form

            Swal.fire({
                title: 'Are you sure?',
                text: "You are going to clear all timetable data for this class!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d9488', // teal-600
                cancelButtonColor: '#f43f5e', // rose-500
                confirmButtonText: 'Yes, clear it!',
                borderA: '30px'
            }).then((result) => {
                if (result.isConfirmed || result.value) {
                    form.submit();
                }
            })
        }

        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            $('.tr').each(function() {
                if (!$(this).text().toLowerCase().includes(searchtext)) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }

        function checkAll() {
            if ($('#chkAll').is(':checked')) {
                $('.chk').addClass('hidden')
            } else {
                $('.chk').removeClass('hidden')
            }
            $('.tr').each(function() {
                $(this).children().find('input[type=checkbox]').prop('checked', $('#chkAll').is(':checked'));
            })
        }

        function submitForm(layout = 'landscape') {
            document.getElementById("layout").value = layout;
            let form = document.getElementById("form_sections"); // storing the form
            form.submit();
        }
    </script>
@endsection

