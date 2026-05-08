@extends('layouts.app')
@section('page-content')
    <div class="space-y-8 pb-12">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 mb-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('section.lecture.schedule.index', [0, 0]) }}" class="hover:text-teal-600 transition-colors">Allocations</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">New Allocation</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-journal-plus text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 leading-none mb-1">New Allocation</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Assign a subject and teacher for a specific period</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('section.lecture.schedule.index', [0, 0]) }}" class="flex items-center gap-2 px-4 py-2 bg-white text-slate-600 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-50 border border-slate-200 transition-all shadow-sm">
                <i class="bi-arrow-left"></i> Back to Schedule
            </a>
        </div>

        <div class="max-w-4xl mx-auto mt-6">
            <!-- page message -->
            @if ($errors->any())
                <x-message :errors='$errors'></x-message>
            @else
                <x-message></x-message>
            @endif

            <form action="{{ route('section.lecture.schedule.store', [$section, $lecture_no]) }}" method='post' class="bg-white border border-slate-100 rounded-[2rem] shadow-xl shadow-slate-200/40 overflow-hidden" onsubmit="return window.validateForm(event)">
                @csrf
                <input type="hidden" name="session_id" value="1">
                
                <!-- Target Header -->
                <div class="bg-slate-50 border-b border-slate-100 p-5 md:p-8 flex flex-col md:flex-row gap-6 md:items-center justify-between relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
                    <div class="flex flex-wrap lg:flex-nowrap items-center gap-4 md:gap-6 relative z-10 w-full">
                        <div class="flex items-center gap-4 min-w-[200px]">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center text-teal-600 shrink-0">
                                <i class="bi-mortarboard text-xl md:text-2xl"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Target Class</p>
                                <h2 class="text-xl md:text-2xl font-bold text-slate-800 truncate">{{ $section->name }}</h2>
                            </div>
                        </div>
                        <div class="h-10 w-px bg-slate-200 hidden md:block shrink-0"></div>
                        <div class="flex items-center gap-3 bg-white px-4 py-2.5 md:bg-transparent md:px-0 md:py-0 border border-slate-100 md:border-none rounded-xl ml-auto md:ml-0 shadow-sm md:shadow-none shrink-0">
                            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 shrink-0">
                                <i class="bi-clock-history text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-0.5">Time Slot</p>
                                <h3 class="text-sm font-bold text-slate-700 whitespace-nowrap">Period {{ $lecture_no }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5 md:p-8 space-y-8">
                    <!-- Selectors -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subject Selector -->
                        <div class="group relative bg-white border-2 border-slate-100 rounded-[1.5rem] p-6 hover:border-teal-100 transition-colors">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-teal-50 group-hover:text-teal-600 transition-colors">
                                    <i class="bi-book text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Assign Subject</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Select course material</p>
                                </div>
                            </div>
                            <div class="relative">
                                <select name="subject_id" required class="w-full appearance-none bg-slate-50 border border-slate-200 text-slate-700 text-sm font-bold rounded-xl px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all cursor-pointer">
                                    <option value="" disabled selected>Choose a subject...</option>
                                    @foreach ($subjects->sortBy('name') as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                <i class="bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Teacher Selector -->
                        <div class="group relative bg-white border-2 border-slate-100 rounded-[1.5rem] p-6 hover:border-teal-100 transition-colors">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-teal-50 group-hover:text-teal-600 transition-colors">
                                    <i class="bi-person-badge text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Assign Teacher</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Select faculty member</p>
                                </div>
                            </div>
                            <div class="relative">
                                <select name="user_id" required class="w-full appearance-none bg-slate-50 border border-slate-200 text-slate-700 text-sm font-bold rounded-xl px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all cursor-pointer">
                                    <option value="" disabled selected>Choose a teacher...</option>
                                    @foreach ($users->sortBy('seniority') as $user)
                                        <option value="{{ $user->id }}">{{ $user->profile->name ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                                <i class="bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Days Selector -->
                    <div class="bg-slate-50 border border-slate-100 rounded-[1.5rem] p-5 md:p-8 text-center relative overflow-hidden">
                        <label class="block text-sm font-bold text-slate-800 mb-1">Active Days</label>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-8">Select sequence of days for this specific scheduled class</p>
                        
                        <div class="flex flex-wrap justify-center gap-3 md:gap-4 relative z-10">
                            @php $days = ['M','T','W','T','F','S']; @endphp
                            @foreach ($days as $i => $day)
                                <div class="day-circle w-12 h-12 md:w-14 md:h-14 text-sm font-bold bg-white border border-slate-200 text-slate-400 flex items-center justify-center rounded-[1rem] cursor-pointer select-none transition-all hover:-translate-y-1 hover:shadow-md hover:border-teal-200"
                                    data-day="{{ $i + 1 }}" title="Day {{ $i + 1 }}">
                                    {{ $day }}
                                </div>
                            @endforeach
                        </div>

                        {{-- Hidden field to store formatted selection --}}
                        <input type="hidden" name="days" id="selectedDays" required>
                        <div class="mt-4 h-4">
                            <p class="text-xs text-rose-500 hidden font-bold tracking-wide" id="daysError"><i class="bi-exclamation-circle mr-1"></i> Please select at least one day.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 border-t border-slate-100 -mx-5 md:-mx-8 -mb-5 md:-mb-8 p-5 md:p-8 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-xs font-medium text-slate-400 italic">Verify your assignment selections before proceeding.</p>
                    <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-2 px-8 py-3.5 bg-teal-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-teal-700 hover:shadow-xl hover:shadow-teal-100 transition-all group">
                        Create Allocation <i class="bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script type="module">
        $(function() {
            let selectedDays = [];

            function formatDays(days) {
                if (!days.length) return "";
                const sorted = [...new Set(days)].sort((a, b) => a - b);
                const parts = [];
                let start = sorted[0], prev = sorted[0];

                for (let i = 1; i < sorted.length; i++) {
                    const cur = sorted[i];
                    if (cur === prev + 1) {
                        prev = cur;
                    } else {
                        parts.push(start === prev ? String(start) : `${start}-${prev}`);
                        start = prev = cur;
                    }
                }
                parts.push(start === prev ? String(start) : `${start}-${prev}`);
                return parts.join(',');
            }

            function updateHidden() {
                $('#selectedDays').val(formatDays(selectedDays));
                if (selectedDays.length > 0) {
                    $('#daysError').addClass('hidden');
                }
            }

            $('.day-circle').on('click', function() {
                const day = parseInt($(this).data('day'), 10);
                const idx = selectedDays.indexOf(day);

                if (idx > -1) {
                    selectedDays.splice(idx, 1);
                    $(this).removeClass('bg-teal-500 text-white border-teal-500 shadow-teal-100')
                           .addClass('bg-white text-slate-400 border-slate-200');
                } else {
                    selectedDays.push(day);
                    $(this).removeClass('bg-white text-slate-400 border-slate-200')
                           .addClass('bg-teal-500 text-white border-teal-500 shadow-lg shadow-teal-100');
                }
                updateHidden();
            });

            // Auto-select all days by default for convenience
            $('.day-circle').each(function() {
                if (!$(this).hasClass('bg-teal-500')) {
                    $(this).trigger('click');
                }
            });
            
            window.validateForm = function(event) {
                if(selectedDays.length === 0) {
                    event.preventDefault();
                    $('#daysError').removeClass('hidden');
                    return false;
                }
                return true;
            };
        });
    </script>
@endsection
