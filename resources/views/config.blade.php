@extends('layouts.app')

@section('page-content')
    <div class="space-y-8 pb-12">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 mb-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-3">
                    <a href="{{ url('/') }}" class="hover:text-teal-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Settings</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-sliders text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 leading-none mb-1">Configuration</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Manage system settings, users, and core academic parameters</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
            <!-- Users -->
            <a href="{{ route('users.index') }}" class="group bg-white border border-slate-100 rounded-[1.5rem] p-6 hover:border-teal-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-100/50 transition-all flex flex-col relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bi bi-people text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1.5">Users</h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Manage staff accounts, edit profiles, and configure system permissions.</p>
                <div class="mt-auto pt-6 flex items-center text-[10px] font-bold text-teal-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Access <i class="bi-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- Subjects -->
            <a href="{{ route('subjects.index') }}" class="group bg-white border border-slate-100 rounded-[1.5rem] p-6 hover:border-teal-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-100/50 transition-all flex flex-col relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bi bi-book text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1.5">Subjects</h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">System-wide subject directory. Manage all subjects taught across the school.</p>
                <div class="mt-auto pt-6 flex items-center text-[10px] font-bold text-teal-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Configure <i class="bi-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- Classes -->
            <a href="{{ route('sections.index') }}" class="group bg-white border border-slate-100 rounded-[1.5rem] p-6 hover:border-teal-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-100/50 transition-all flex flex-col relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bi bi-layers text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1.5">Classes</h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Manage academic sections, class rosters, and bulk enrollments natively.</p>
                <div class="mt-auto pt-6 flex items-center text-[10px] font-bold text-teal-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Configure <i class="bi-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- Schedule -->
            <a href="{{ route('class-schedule') }}" class="group bg-white border border-slate-100 rounded-[1.5rem] p-6 hover:border-teal-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-100/50 transition-all flex flex-col relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bi bi-calendar-range text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1.5">Allocations</h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Master timetable controls. Assign teachers, classes, and subjects to periods.</p>
                <div class="mt-auto pt-6 flex items-center text-[10px] font-bold text-teal-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Open <i class="bi-arrow-right ml-1"></i>
                </div>
            </a>
            
            <!-- Timings -->
            <a href="{{ route('lecture-timings.index') }}" class="group bg-white border border-slate-100 rounded-[1.5rem] p-6 hover:border-teal-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-100/50 transition-all flex flex-col relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bi bi-clock-history text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1.5">Lecture Timings</h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Establish standard period lengths and synchronize school bells globally.</p>
                <div class="mt-auto pt-6 flex items-center text-[10px] font-bold text-teal-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Configure <i class="bi-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- Grade Setup -->
            <a href="{{ route('grade-subjects.index') }}" class="group bg-white border border-slate-100 rounded-[1.5rem] p-6 hover:border-teal-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-100/50 transition-all flex flex-col relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bi bi-diagram-3 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1.5">Grade Setup</h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Architect curriculum rules. Decide which subjects map exactly to which grade.</p>
                <div class="mt-auto pt-6 flex items-center text-[10px] font-bold text-teal-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Configure <i class="bi-arrow-right ml-1"></i>
                </div>
            </a>

            <!-- Tasks -->
            <a href="{{ route('tasks.index') }}" class="group bg-white border border-slate-100 rounded-[1.5rem] p-6 hover:border-teal-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-teal-100/50 transition-all flex flex-col relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <i class="bi bi-ui-checks text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1.5">Task Manager</h3>
                <p class="text-xs text-slate-500 font-medium leading-relaxed">Assign administrative tasks, track progress, and manage workflow approvals.</p>
                <div class="mt-auto pt-6 flex items-center text-[10px] font-bold text-teal-600 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Manage <i class="bi-arrow-right ml-1"></i>
                </div>
            </a>

        </div>
    </div>
@endsection
