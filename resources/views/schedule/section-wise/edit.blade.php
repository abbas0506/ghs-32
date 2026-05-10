@extends('layouts.app')

@section('page-content')
    <div class="space-y-8 pb-12">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 mb-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('section.lecture.schedule.index', [0, 0]) }}" class="hover:text-teal-600 transition-colors">Allocations</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Edit</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-pencil-square text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800 leading-none mb-1">Edit Allocation</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Update the subject or teacher assigned to this period</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('section.lecture.schedule.index', [0, 0]) }}" class="flex items-center gap-2 px-4 py-2 bg-white text-slate-600 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-slate-50 border border-slate-200 transition-all shadow-sm">
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

            <div class="bg-white border border-slate-100 rounded-[2rem] shadow-xl shadow-slate-200/40 overflow-hidden">
                <!-- Target Header -->
                <div class="bg-slate-50 border-b border-slate-100 p-5 md:p-8 flex flex-col lg:flex-row gap-6 lg:items-center justify-between relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
                    
                    <div class="flex flex-wrap md:flex-nowrap items-center gap-4 md:gap-6 relative z-10 w-full lg:w-auto">
                        <div class="flex items-center gap-4 min-w-[200px]">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center text-teal-600 shrink-0">
                                <i class="bi-mortarboard text-xl md:text-2xl"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Target Class</p>
                                <h2 class="text-xl md:text-xl font-bold text-slate-800 truncate">{{ $allocation->section->name }}</h2>
                            </div>
                        </div>
                        <div class="h-10 w-px bg-slate-200 hidden md:block shrink-0"></div>
                        <div class="flex items-center gap-3 bg-white px-4 py-2.5 md:bg-transparent md:px-0 md:py-0 border border-slate-100 md:border-none rounded-xl ml-auto md:ml-0 shadow-sm md:shadow-none shrink-0">
                            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 shrink-0">
                                <i class="bi-clock-history text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-0.5">Time Slot</p>
                                <h3 class="text-sm font-bold text-slate-700 whitespace-nowrap">Period {{ $allocation->lecture_no }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Action -->
                    <form action="{{ route('section.lecture.schedule.destroy', [$allocation->section, $allocation->lecture_no, $allocation]) }}"
                        method="POST" onsubmit="return confirmDel(event)" class="relative z-10 m-0 shrink-0 mt-4 lg:mt-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-rose-50 text-rose-600 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                            <i class="bi-trash3"></i> <span class="hidden md:inline">Delete Allocation</span>
                        </button>
                    </form>
                </div>

                <form action="{{ route('section.lecture.schedule.update', [$allocation->section, $allocation->lecture_no, $allocation]) }}" method='post' class="p-5 md:p-8 space-y-8">
                    @csrf
                    @method('PATCH')
                    
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
                                    <option value="" disabled>Choose a subject...</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected($allocation->subject_id == $subject->id)>{{ $subject->name }}</option>
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
                                    <option value="" disabled>Choose a teacher...</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected($allocation->user_id == $user->id)>{{ $user->profile?->name ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                                <i class="bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 border-t border-slate-100 -mx-5 md:-mx-8 -mb-5 md:-mb-8 p-5 md:p-8 flex flex-col md:flex-row items-center justify-between gap-4">
                        <p class="text-xs font-medium text-slate-400 italic">Verify your assignment selections before proceeding.</p>
                        <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-2 px-8 py-3.5 bg-teal-600 text-white rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-teal-700 hover:shadow-xl hover:shadow-teal-100 transition-all group">
                            Update Allocation <i class="bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function confirmDel(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this deletion!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e', // rose-500
                cancelButtonColor: '#94a3b8', // slate-400
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed || result.value) {
                    form.submit();
                }
            })
        }
    </script>
@endsection
