@extends('layouts.app')

@section('page-content')
    <div class="space-y-6 pb-12">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('sections.index') }}" class="hover:text-teal-600 transition-colors">Classes</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Profile</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <span class="text-2xl font-black">{{ substr($section->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 leading-none mb-1">Class {{ $section->name }}</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Monitor academic class records and enrollment</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @can('update', $section)
                    <a href="{{ route('sections.edit', $section) }}" 
                       class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:text-teal-600 hover:border-teal-200 transition-all">
                       <i class="bi-pencil-square"></i> Edit
                    </a>
                @endcan
                @can('delete', $section)
                    <form action="{{ route('sections.destroy', $section) }}" method="POST" onsubmit="return confirmDel(event)" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" 
                           class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all">
                           <i class="bi-trash3"></i> Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        <!-- Quick Summary Metrics (Tests Card Style) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Metric 1: Total Enrolled -->
            <div class="bg-teal-600 rounded-3xl p-6 text-white shadow-xl shadow-teal-100 flex flex-col justify-center relative overflow-hidden hover:-translate-y-1 transition-transform">
                <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute top-4 right-4 w-6 h-6 bg-white/10 rounded-full"></div>
                <div class="flex items-center justify-between mb-1 relative z-10">
                    <p class="text-[9px] md:text-[10px] font-black text-teal-100 uppercase tracking-widest">Enrolled Students</p>
                    <i class="bi bi-people text-white opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-2 relative z-10">
                    <h2 class="text-xl md:text-2xl font-black text-white">{{ $section->students->count() }}</h2>
                    @php $newAdmissions = $section->newAdmissions()->count(); @endphp
                    @if($newAdmissions > 0)
                        <span class="text-[9px] font-black text-white uppercase opacity-80">+{{ $newAdmissions }} new</span>
                    @endif
                </div>
            </div>

            <!-- Metric 2: Market Today -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-center hover:-translate-y-1 transition-transform">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Marked Today</p>
                    <i class="bi bi-calendar-check text-teal-600 opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-1">
                    <h2 class="text-xl md:text-2xl font-black text-slate-800">{{ $section->attendanceMarked() }}</h2>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Students</span>
                </div>
            </div>

            <!-- Metric 3: Avg Attendance -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-center hover:-translate-y-1 transition-transform">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">Avg Attendance</p>
                    <i class="bi bi-graph-up text-emerald-500 opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-1">
                    <h2 class="text-xl md:text-2xl font-black text-slate-800">{{ $section->averageAttendance() }}%</h2>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Rate</span>
                </div>
            </div>
            
            <!-- Metric 4: Class ID -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-center hover:-translate-y-1 transition-transform">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">System Record</p>
                    <i class="bi bi-database text-slate-400 opacity-60"></i>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">ID</span>
                    <h2 class="text-xl md:text-2xl font-black text-slate-800">#{{ $section->id }}</h2>
                </div>
            </div>
        </div>

        <!-- Quick Action Menu -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('section.students.create', $section) }}" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-teal-100 transition-all text-center">
                <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-teal-600 group-hover:text-white transition-all">
                    <i class="bi bi-person-plus text-lg"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-slate-500 group-hover:text-teal-700 transition-colors">Add Student</span>
            </a>

            <a href="{{ route('sections.export', $section) }}" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-teal-100 transition-all text-center">
                <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-teal-600 group-hover:text-white transition-all">
                    <i class="bi bi-cloud-arrow-down text-lg"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-slate-500 group-hover:text-teal-700 transition-colors">Export Data</span>
            </a>

            <a href="{{ route('section.cards.index', $section) }}" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-teal-100 transition-all text-center">
                <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-teal-600 group-hover:text-white transition-all">
                    <i class="bi bi-person-badge text-lg"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-slate-500 group-hover:text-teal-700 transition-colors">ID Cards</span>
            </a>

            <a href="{{ route('sections.list.print', $section) }}" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-teal-100 transition-all text-center">
                <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-teal-600 group-hover:text-white transition-all">
                    <i class="bi bi-printer text-lg"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-slate-500 group-hover:text-teal-700 transition-colors">Attendance</span>
            </a>

            @can('clean', $section)
                <a href="{{ route('sections.clean', $section) }}" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-amber-100 transition-all text-center">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-amber-600 group-hover:text-white transition-all">
                        <i class="bi bi-stars text-lg"></i>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest text-slate-500 group-hover:text-amber-700 transition-colors">Clean Data</span>
                </a>
            @endcan

            <a href="{{ route('sections.reset', $section) }}" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-rose-100 transition-all text-center">
                <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-rose-600 group-hover:text-white transition-all">
                    <i class="bi bi-arrow-counterclockwise text-lg"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-slate-500 group-hover:text-rose-700 transition-colors">Reset Class</span>
            </a>
        </div>

        <!-- Student List Table -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Student Directory</h2>
                    <p class="text-slate-400 text-sm">Managing records for {{ $section->students->count() }} enrolled students</p>
                </div>

                <div class="relative w-full md:w-80 group">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-teal-500 transition-colors"></i>
                    <input type="text" 
                        id="studentSearch" 
                        placeholder="Search name or roll no..." 
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm font-medium focus:ring-4 focus:ring-teal-500/10 focus:bg-white transition-all outline-none"
                        oninput="searchStudents(event)"
                    >
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Roll No</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Student Information</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                            <th class="px-8 py-4 text-right overflow-hidden"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50" id="studentsTableBody">
                        @forelse ($section->students->sortBy('rollno') as $student)
                            <tr class="student-row group hover:bg-teal-50/30 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold ring-4 ring-teal-50/50">
                                        {{ $student->rollno }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-teal-900 transition-colors">{{ $student->name }}</span>
                                        <span class="text-xs text-slate-400 mt-0.5">S/O-D/O: {{ $student->father_name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    @if ($student->hasBeenCreatedThisWeek())
                                        <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-md">New Admission</span>
                                    @else
                                        <span class="text-slate-300 text-[10px] font-bold uppercase tracking-widest">Enrolled</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('section.students.show', [$section, $student]) }}" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-teal-600 transition-colors">
                                        View Profile <i class="bi bi-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="bi bi-people text-3xl"></i>
                                    </div>
                                    <p class="text-slate-400 font-medium">No students enrolled in this section yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function searchStudents(event) {
            const query = event.target.value.toLowerCase();
            const rows = document.querySelectorAll('.student-row');
            
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(query)) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        function confirmDel(event) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: 'Are you sure?',
                text: "Deleting this section will affect all associated records. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d9488', // teal-600
                cancelButtonColor: '#f43f5e', // rose-500
                confirmButtonText: 'Yes, delete class',
                cancelButtonText: 'Cancel',
                background: '#ffffff',
                customClass: {
                    title: 'text-xl font-bold text-slate-800',
                    popup: 'rounded-[1.5rem] shadow-2xl border-none'
                }
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            })
        }
    </script>
@endsection
