@extends('layouts.app')

@section('page-content')
    <div class="space-y-8">
        <!-- Hero Section - Premium Teal -->
        <div class="relative overflow-hidden bg-gradient-to-br from-teal-700 via-teal-800 to-slate-900 rounded-3xl p-5 md:p-8 text-white shadow-lg">
            <!-- Elegant Background Accents -->
            <div class="absolute inset-0 pointer-events-none opacity-30">
                <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-teal-400 rounded-full blur-[100px] opacity-20 -translate-y-1/2 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-[25rem] h-[25rem] bg-emerald-500 rounded-full blur-[100px] opacity-20 translate-y-1/3 -translate-x-1/4"></div>
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmZiIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz48L3N2Zz4=')] opacity-30 bg-repeat pointer-events-none"></div>
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <h1 class="text-lg md:text-xl font-semibold text-black mb-2 tracking-tight leading-tight">
                        Welcome back, <span class="text-teal-300">{{ Auth::user()->profile->short_name }}!</span>
                    </h1>
                    <p class="text-teal-50/80 text-sm leading-relaxed mb-5 font-light">
                        You have <span class="text-white">{{ $pendingTasks->count() }} pending tasks</span> and <span class="text-white">{{ $tests->open()->count() }} active assessments</span>.
                    </p>
                    
                    <div class="flex flex-wrap gap-2.5 ">
                        <a href="{{ route('attendance.summary') }}" class="group bg-white text-teal-900 px-5 py-2.5 text-xs md:text-sm rounded-lg font-semibold transition-all flex items-center gap-2 hover:shadow-[0_8px_30px_rgb(20,184,166,0.2)] hover:-translate-y-0.5">
                            <i class="bi bi-person-check text-sm"></i> Mark Attendance 
                        </a>
                        <a href="{{ route('tests.create') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-5 py-2.5 rounded-lg text-xs md:text-sm font-semibold transition-all border border-white/10 flex items-center gap-2 hover:-translate-y-0.5">
                            <i class="bi bi-plus-lg text-sm"></i> Create Assessment
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Students -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <span class="bg-teal-50 text-teal-700 text-[9px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full">
                        +{{ $newAdmissions->count() }} new
                    </span>
                </div>
                <h3 class="text-slate-500 text-[10px] font-semibold uppercase tracking-wider mb-1">Total Students</h3>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($students->count()) }}</p>
            </div>

            <!-- Attendance -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <span class="bg-teal-50 text-teal-700 text-[9px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full">
                        {{ $highestAttenancePercentage }}% max
                    </span>
                </div>
                <h3 class="text-slate-500 text-[10px] font-semibold uppercase tracking-wider mb-1">Today's Attendance</h3>
                <p class="text-2xl font-bold text-slate-800">
                    @php
                        $todayPerc = $students->count() > 0 ? round(($attendances->count() / $students->count()) * 100, 0) : 0;
                    @endphp
                    {{ $todayPerc }}%
                </p>
            </div>

            <!-- Assessments -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="bi bi-clipboard2-data-fill"></i>
                    </div>
                    <span class="bg-teal-50 text-teal-700 text-[9px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full">
                        {{ $tests->open()->count() }} Active
                    </span>
                </div>
                <h3 class="text-slate-500 text-[10px] font-semibold uppercase tracking-wider mb-1">Assessments</h3>
                <p class="text-2xl font-bold text-slate-800">{{ $tests->count() }}</p>
            </div>

            <!-- My Schedule -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <span class="bg-teal-50 text-teal-700 text-[9px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-full">
                        Weekly
                    </span>
                </div>
                <h3 class="text-slate-500 text-[10px] font-semibold uppercase tracking-wider mb-1">My Subjects</h3>
                <p class="text-2xl font-bold text-slate-800">{{ $myAllocationsCount }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Attendance Trend Chart -->
            <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Attendance Trend</h2>
                        <p class="text-slate-500 text-sm">Student attendance over the last 15 days</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-teal-500 rounded-full"></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Present</span>
                    </div>
                </div>
                <div class="h-[300px] w-full">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Side Panels -->
            <div class="space-y-8">
                <!-- My Tasks -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-slate-800">My Tasks</h2>
                        <span class="bg-teal-50 text-teal-700 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg">{{ $pendingTasks->count() }} Pending</span>
                    </div>
                    
                    <div class="space-y-4 flex-1 overflow-y-auto max-h-[350px] pr-2 custom-scrollbar">
                        @forelse($pendingTasks->take(5) as $taskLine)
                            <div class="group flex items-start gap-4 p-3 rounded-xl bg-slate-50/50 hover:bg-teal-50/50 transition-all border border-transparent hover:border-teal-100/60 cursor-pointer">
                                <div class="mt-1">
                                    <div class="w-5 h-5 rounded border-2 border-slate-300 flex items-center justify-center group-hover:bg-teal-500 group-hover:border-teal-500 transition-all">
                                        <i class="bi bi-check text-white text-xs opacity-0 group-hover:opacity-100"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-700 group-hover:text-teal-900 truncate">{{ $taskLine->task->title }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Due: {{ $taskLine->task->due_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 flex flex-col items-center justify-center h-full">
                                <div class="w-14 h-14 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                    <i class="bi bi-check2-all text-2xl"></i>
                                </div>
                                <p class="text-slate-400 font-medium text-sm">All caught up!</p>
                            </div>
                        @endforelse
                    </div>

                    @if($pendingTasks->count() > 5)
                        <a href="{{ route('tasks.index') }}" class="mt-4 pt-4 border-t border-slate-100 text-center text-sm font-semibold text-teal-600 hover:text-teal-700 transition-colors block">
                            View All Tasks &rarr;
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions Bottom: Clean Light Theme -->
        <div class="bg-white border border-slate-100 rounded-[2rem] p-8 md:p-10 relative overflow-hidden shadow-sm">
            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10">
                <div class="max-w-md text-center lg:text-left">
                    <h2 class="text-lg font-semibold text-slate-800 mb-2">Need a Report?</h2>
                    <p class="text-slate-500">Access comprehensive analytics and generate formal academic reports effortlessly.</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full lg:flex-1 lg:max-w-3xl">
                    <a href="{{ route('reports.combined.selector') }}" class="group flex flex-col items-center justify-center bg-slate-50 hover:bg-teal-50/50 p-6 rounded-2xl transition-all border border-transparent hover:border-teal-100 w-full">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform group-hover:shadow text-teal-600">
                            <i class="bi bi-file-earmark-bar-graph text-xl"></i>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 group-hover:text-teal-700">Results</span>
                    </a>
                    
                    <a href="{{ route('sections.index') }}" class="group flex flex-col items-center justify-center bg-slate-50 hover:bg-teal-50/50 p-6 rounded-2xl transition-all border border-transparent hover:border-teal-100 w-full">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform group-hover:shadow text-teal-600">
                            <i class="bi bi-mortarboard text-xl"></i>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 group-hover:text-teal-700">Classes</span>
                    </a>

                    <a href="{{ route('user-schedule.show') }}" class="group flex flex-col items-center justify-center bg-slate-50 hover:bg-teal-50/50 p-6 rounded-2xl transition-all border border-transparent hover:border-teal-100 w-full">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform group-hover:shadow text-teal-600">
                            <i class="bi bi-clock-history text-xl"></i>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 group-hover:text-teal-700">Schedule</span>
                    </a>

                    <a href="{{ route('config') }}" class="group flex flex-col items-center justify-center bg-slate-50 hover:bg-teal-50/50 p-6 rounded-2xl transition-all border border-transparent hover:border-teal-100 w-full">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform group-hover:shadow text-teal-600">
                            <i class="bi bi-sliders text-xl"></i>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 group-hover:text-teal-700">Settings</span>
                    </a>
                </div>
            </div>
            <!-- Subtle Decorative Flare -->
            <div class="absolute right-0 top-0 w-64 h-64 bg-teal-50 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2 pointer-events-none opacity-50"></div>
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(13, 148, 136, 0.4)');
        gradient.addColorStop(1, 'rgba(13, 148, 136, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($attendanceTrends, 'date')) !!},
                datasets: [{
                    label: 'Present Students',
                    data: {!! json_encode(array_column($attendanceTrends, 'count')) !!},
                    borderColor: '#0d9488',
                    borderWidth: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0d9488',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0,0,0,0.03)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 12, weight: '500' },
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 12, weight: '500' },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
@endsection

