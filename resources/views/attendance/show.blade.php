@extends('layouts.app')
@section('page-content')
    <div class="space-y-8 pb-12">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 mb-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-3">
                    <a href="{{ route('section.attendance.index', $section) }}" class="hover:text-teal-600 transition-colors">Class Detail</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600">Student History</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                        <i class="bi-clock-history text-xl"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h1 class="text-lg font-bold text-slate-800 leading-none mb-1 truncate">Attendance Record</h1>
                        <p class="text-slate-400 text-xs font-medium italic">Detailed historical view</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="max-w-4xl mx-auto mt-6 space-y-8">
            <!-- Student Header Profile -->
            <div class="bg-white border border-slate-100 rounded-[1rem] md:rounded-[2rem] shadow-xl shadow-slate-200/40 p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
                
                <div class="flex flex-col md:flex-row items-start md:items-center gap-5 relative z-10 w-full md:w-auto">
                    @php
                        $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', explode(' ', $student->name))));
                    @endphp
                    <div class="hidden md:flex w-16 h-16 rounded-[1.5rem] bg-teal-50 border border-teal-100 text-teal-600 items-center justify-center text-xl font-bold shadow-sm shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="overflow-hidden w-full md:w-auto">
                        <div class="">
                            <h2 class="text-sm md:text-xl font-bold text-slate-800">{{ $student->name }}</h2>
                            <p class="text-[9px] uppercase tracking-widest text-slate-400 font-bold mt-1">
                            {{ $student->father_name }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center px-2.5 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-slate-200 whitespace-nowrap">Roll # {{ $student->rollno }}</div>
                </div>
                
                <div class="h-10 w-px bg-slate-200 hidden lg:block shrink-0 relative z-10"></div>
                
                <div class="flex items-center gap-3 bg-white px-5 py-3 border border-slate-100 rounded-2xl shadow-sm w-full md:w-auto relative z-10 shrink-0">
                    <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600 shrink-0">
                        <i class="bi-mortarboard"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-0.5">Class</p>
                        <h3 class="text-sm font-bold text-slate-700 whitespace-nowrap">{{ $student->section->name }}</h3>
                    </div>
                </div>
            </div>

            <!-- Metrics Grid -->
            @php
                $trending = $monthAttendancePercentage >= $sessionAttendancePercentage ? 'up' : 'down';
                $trendColor = $trending === 'up' ? 'text-teal-400' : 'text-rose-400';
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Session Metric -->
                <div class="bg-teal-600 rounded-[1rem] md:rounded-[2rem] p-5 text-white shadow-xl shadow-teal-100 flex items-center justify-between relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-[2rem] rotate-12"></div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-teal-100 uppercase tracking-widest mb-1.5">Session Attendance</p>
                        <div class="flex items-end gap-3">
                            <h2 class="text-xl font-bold text-white leading-none">{{ $sessionAttendancePercentage }}%</h2>
                            <span class="text-white/60 text-[9px] font-bold uppercase tracking-widest mb-0.5 pb-px">Since {{ $sessionStart->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Monthly Metric -->
                <div class="bg-white rounded-[1rem] md:rounded-[2rem] p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Current Month Rate</p>
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-slate-800 leading-none">{{ $monthAttendancePercentage }}%</h2>
                            <span class="{{ $trendColor }} text-[10px] font-bold bg-slate-50 px-2 py-0.5 rounded border border-slate-100 flex items-center gap-1">
                                <i class="bi-graph-{{ $trending }}"></i> {{ ucfirst($trending) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>


                <!-- Monthly Chart -->
                <div class="bg-white rounded-[1rem] md:rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col">
                    <div class="absolute right-0 bottom-0 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                    <div class="flex items-center justify-between mb-6 relative z-10 w-full shrink-0">
                        <div>
                            <h3 class="font-bold text-slate-800 text-md flex items-center gap-2">
                                <i class="bi-graph-up-arrow text-teal-500"></i> Monthly Averages
                            </h3>
                            <!-- <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1">Session Monthly Averages</p> -->
                        </div>
                    </div>
                    <div class="relative w-full z-10 flex-1 overflow-x-auto overflow-y-hidden custom-scrollbar">
                        <div class="min-w-[600px] w-full min-h-[220px] relative">
                            <canvas id="monthlyAnalyticsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Attendance Calendar -->
                <div class="bg-white rounded-[1rem] md:rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col mt-4">
                    <div class="absolute right-0 top-0 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                    
                    <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4 relative z-10">
                        <div>
                            <h3 class="font-bold text-slate-800 text-md flex items-center gap-2">
                                <i class="bi-calendar3 text-teal-500"></i> Attendance Calendar
                            </h3>
                            <!-- <p class="text-[10px] font-medium text-slate-400 mt-1">Daily presence overview</p> -->
                        </div>
                        
                        <div class="flex items-center bg-slate-50 p-1.5 rounded-2xl border border-slate-100 shadow-sm">
                            <button id="prevMonth" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white hover:text-teal-600 transition-all text-slate-400">
                                <i class="bi-chevron-left"></i>
                            </button>
                            <div class="px-6 py-1 min-w-[140px] text-center">
                                <span id="currentMonthYear" class="text-[10px] font-bold text-slate-700 uppercase tracking-widest"></span>
                            </div>
                            <button id="nextMonth" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white hover:text-teal-600 transition-all text-slate-400">
                                <i class="bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 gap-2 md:gap-4 mb-4 relative z-10">
                        @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                            <div class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest py-2">{{ $day }}</div>
                        @endforeach
                    </div>

                    <div id="calendarGrid" class="grid grid-cols-7 gap-2 md:gap-4 relative z-10">
                        <!-- Calendar days will be injected here -->
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-slate-50 pt-3 relative z-10">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-teal-500 shadow-sm shadow-teal-100"></div>
                            <span class="text-[10px] font-bold text-slate-500">Present</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-rose-500 shadow-sm shadow-rose-100"></div>
                            <span class="text-[10px] font-bold text-slate-500">Absent</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-slate-200 shadow-sm shadow-slate-100"></div>
                            <span class="text-[10px] font-bold text-slate-500">NA</span>
                        </div>
                    </div>
                </div>


            <!-- Absence Data -->
            @if ($sessionAbsences->count())
                <div class="bg-white rounded-[1rem] md:rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden mt-4">
                    <div class="flex items-center gap-3 px-6 md:px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                        <i class="bi-calendar-x text-rose-500 text-xl"></i>
                        <div class="flex flex-col items-start justify-between">
                            <h3 class="font-bold text-slate-800 text-md  tracking-wide">Absence Log</h3>
                            <span class="text-slate-400 text-xs italic">{{ $sessionAbsences->count() }} entries found</span>
                        </div>
                    </div>    
                    <div class="grid">
                        @foreach ($sessionAbsences->sortByDesc('date') as $attendance)
                        <div class="flex items-center gap-4 w-full p-4">
                            <div class="flex w-10 md:w-24">
                                <div class="w-6 h-6 md:w-8 md:h-8 rounded-lg bg-slate-50 text-slate-400 font-bold text-[9px] md:text-xs flex items-center justify-center mx-auto border border-slate-100 group-hover:bg-white transition-colors">
                                    {{ $loop->iteration }}
                                </div>
                            </div>
                            <div class="flex flex-1 flex-col">
                                <span class="text-xs md:text-sm font-bold text-slate-700">{{ $attendance->date->format('d M, Y') }}</span>
                                <span class="text-[8px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $attendance->date->format('l') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mt-8 text-center py-16 bg-white rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-24 h-24 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi-award text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Perfect Attendance!</h3>
                    <p class="text-slate-500 font-medium max-w-sm mx-auto">Outstanding! {{ $student->name }} has zero recorded absences during this academic session.</p>
                </div>
            @endif

        </div>
    </div>
@endsection
@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sharedOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13, weight: 'bold' },
                    displayColors: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: '#f8fafc', drawBorder: false },
                    ticks: {
                        stepSize: 20,
                        font: { weight: 'bold', size: 10 },
                        color: '#94a3b8',
                        callback: function(value) { return value + '%'; }
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { weight: 'bold', size: 10 }, color: '#cbd5e1' }
                }
            }
        };



        // Monthly Chart
        const monthlyCtxNode = document.getElementById('monthlyAnalyticsChart');
        if(monthlyCtxNode) {
            const monthlyData = @json($monthlyData);
            if(monthlyData.length > 0) {
                new Chart(monthlyCtxNode.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: monthlyData.map(d => d.date),
                        datasets: [{
                            label: 'Attendance Rate',
                            data: monthlyData.map(d => d.percentage),
                            backgroundColor: monthlyData.map(d => d.percentage >= 80 ? '#14b8a6' : (d.percentage >= 50 ? '#f59e0b' : '#fda4af')), // Teal > Orange > Rose
                            borderRadius: 4,
                            borderSkipped: false,
                            barPercentage: 0.7
                        }]
                    },
                    options: {
                        ...sharedOptions,
                        plugins: {
                            ...sharedOptions.plugins,
                            tooltip: {
                                ...sharedOptions.plugins.tooltip,
                                callbacks: {
                                    label: function(context) { return context.raw + '% Average Presence'; }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Calendar Logic
        let currentDisplayDate = new Date();
        const attendanceData = @json($sessionAttendanceMap);
        
        function renderCalendar(date) {
            const grid = document.getElementById('calendarGrid');
            const monthYearLabel = document.getElementById('currentMonthYear');
            
            if(!grid || !monthYearLabel) return;

            grid.innerHTML = '';
            
            const year = date.getFullYear();
            const month = date.getMonth();
            
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            monthYearLabel.innerText = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(date);
            
            // Empty slots for previous month
            for (let i = 0; i < firstDay; i++) {
                const empty = document.createElement('div');
                empty.className = 'aspect-square';
                grid.appendChild(empty);
            }
            
            // Days of the month
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const status = attendanceData[dateStr];
                
                const dayEl = document.createElement('div');
                dayEl.className = 'aspect-square flex flex-col items-center justify-center rounded-2xl md:rounded-[1.5rem] relative group cursor-default transition-all duration-300';
                
                let bgColor = 'bg-slate-50 border-slate-100 text-slate-400';
                let dotColor = 'bg-slate-200';
                
                if (status === 1) {
                    bgColor = 'bg-teal-50 border-teal-100 text-teal-700';
                    dotColor = 'bg-teal-500';
                } else if (status === 0) {
                    bgColor = 'bg-rose-50 border-rose-100 text-rose-700';
                    dotColor = 'bg-rose-500';
                }
                
                dayEl.innerHTML = `
                    <div class="absolute inset-0 rounded-2xl md:rounded-[1.5rem] border ${bgColor} shadow-sm group-hover:scale-105 transition-transform duration-300"></div>
                    <span class="relative z-10 text-[10px] md:text-xs font-bold">${d}</span>
                    <div class="relative z-10 w-1 h-1 md:w-1.5 md:h-1.5 rounded-full ${dotColor} mt-1"></div>
                `;
                
                grid.appendChild(dayEl);
            }
        }
        
        const prevBtn = document.getElementById('prevMonth');
        const nextBtn = document.getElementById('nextMonth');

        if(prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => {
                currentDisplayDate.setMonth(currentDisplayDate.getMonth() - 1);
                renderCalendar(currentDisplayDate);
            });
            
            nextBtn.addEventListener('click', () => {
                currentDisplayDate.setMonth(currentDisplayDate.getMonth() + 1);
                renderCalendar(currentDisplayDate);
            });
            
            renderCalendar(currentDisplayDate);
        }
    });
</script>
@endsection


