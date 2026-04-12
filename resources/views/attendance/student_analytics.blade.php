@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex flex-wrap items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-black mb-3">
                    <a href="{{ route('attendance.summary') }}" class="hover:text-teal-600 transition-colors">Attendance</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('section.attendance.show', [$section->id, $attendance->id]) }}" class="hover:text-teal-600 transition-colors">{{ $student->name }}</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">Analytics Workspace</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <i class="bi-person-bounding-box text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 leading-none mb-1">{{ $student->name }}</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Individual graphical attendance analysis</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('section.attendance.show', [$section->id, $attendance->id]) }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm flex items-center justify-center gap-2">
                <i class="bi-arrow-left"></i> Back to Profile
            </a>
        </div>

        <div class="flex flex-col space-y-6 pb-12">
            @if(count($weeklyData) > 0)
                <!-- Weekly Chart -->
                <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col">
                    <div class="absolute right-0 bottom-0 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                    <div class="flex items-center justify-between mb-6 relative z-10 w-full shrink-0">
                        <div>
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="bi-activity text-teal-500"></i> Weekly Pulse
                            </h3>
                            <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1">{{ $student->name }} - Last 7 Days Log</p>
                        </div>
                    </div>
                    <div class="relative w-full z-10 flex-1 overflow-x-auto custom-scrollbar pb-4">
                        <div class="min-w-[600px] w-full flex flex-col justify-center min-h-[160px] relative px-2">
                            <!-- connecting background line -->
                            <div class="absolute left-10 right-10 h-1.5 rounded-full bg-slate-100 top-1/2 -mt-4 z-0"></div>
                            
                            <div class="flex items-start justify-between relative z-10 w-full">
                            @foreach($weeklyData as $day)
                                @php
                                    $parts = explode(', ', $day['date']);
                                    $dayName = substr($parts[0] ?? 'Day', 0, 3);
                                    $dateStr = $parts[1] ?? '';
                                @endphp
                                <div class="flex flex-col items-center group relative cursor-pointer w-14">
                                    @if($day['status'] == 1)
                                        <div class="w-12 h-12 rounded-full border-4 border-white bg-teal-500 shadow-xl shadow-teal-500/30 flex items-center justify-center transition-transform group-hover:scale-110 mb-3 group-hover:-translate-y-1 relative z-10">
                                            <i class="bi-check text-white text-2xl font-black"></i>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-teal-600 opacity-100 transition-all text-center">Present</span>
                                    @elseif($day['status'] == 0)
                                        <div class="w-12 h-12 rounded-full border-4 border-white bg-rose-500 shadow-xl shadow-rose-500/30 flex items-center justify-center transition-transform group-hover:scale-110 mb-3 group-hover:-translate-y-1 relative z-10">
                                            <div class="w-3.5 h-1 rounded-sm bg-white"></div>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-rose-500 opacity-100 transition-all text-center">Absent</span>
                                    @else
                                        <div class="w-12 h-12 rounded-full border-4 border-white bg-slate-100 shadow-sm flex items-center justify-center transition-transform group-hover:scale-110 mb-3 group-hover:-translate-y-1 relative z-10">
                                            <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 opacity-100 transition-all text-center">No Data</span>
                                    @endif
                                    <span class="text-[10px] font-black text-slate-700 mt-1.5 align-middle leading-none">{{ $dayName }}</span>
                                    <span class="text-[9px] font-bold text-slate-400">{{ $dateStr }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Monthly Chart -->
                <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col">
                    <div class="absolute right-0 bottom-0 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                    <div class="flex items-center justify-between mb-6 relative z-10 w-full shrink-0">
                        <div>
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="bi-graph-up-arrow text-teal-500"></i> Monthly Retrospective
                            </h3>
                            <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1">{{ $student->name }} - Session Monthly Averages</p>
                        </div>
                    </div>
                    <div class="relative w-full z-10 flex-1 overflow-x-auto overflow-y-hidden custom-scrollbar">
                        <div class="min-w-[600px] w-full min-h-[220px] relative">
                            <canvas id="monthlyAnalyticsChart"></canvas>
                        </div>
                    </div>
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
    });
</script>
@endsection
