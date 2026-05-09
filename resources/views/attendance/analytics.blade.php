@extends('layouts.app')
@section('page-content')
    <div class="flex flex-col space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-3">
                    <a href="{{ route('attendance.summary') }}" class="hover:text-teal-600 transition-colors">Attendance</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">Analytics</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-100">
                        <i class="bi-bar-chart-fill text-xl"></i>
                    </div>
                    <div class="w-full overflow-hidden">
                        <h2 class="text-xl font-bold text-slate-800 leading-none mb-1 truncate">Attendance Analytics</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Graphical timeline of class presence rates</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('attendance.analytics') }}" method="GET" id="analyticsForm">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 py-2 overflow-x-auto custom-scrollbar no-scrollbar">
                @foreach($sections as $section)
                    <label class="cursor-pointer shrink-0">
                        <input type="radio" name="class_id" value="{{ $section->id }}" class="sr-only peer" {{ $selectedSectionId == $section->id ? 'checked' : '' }} onchange="this.form.submit()">
                        <div class="p-3 rounded-xl border border-slate-100 bg-white text-[9px] md:text-xs font-bold uppercase text-slate-500 transition-all hover:border-teal-200 hover:bg-slate-50 peer-checked:bg-teal-600 peer-checked:text-white peer-checked:border-teal-600 peer-checked:shadow-xl peer-checked:shadow-teal-100 flex items-center justify-center min-w-[120px]">
                            {{ $section->name }}
                        </div>
                    </label>
                @endforeach
            </div>
        </form>

        <div class="w-full pb-12">
            @if($selectedSection && count($weeklyData) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Weekly Chart -->
                    <div class="bg-white rounded-[1rem] md:rounded-[1.5rem] p-6 md:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col h-[400px]">
                        <div class="absolute right-0 bottom-0 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                        <div class="flex items-center justify-between mb-6 relative z-10 w-full shrink-0">
                            <div>
                                <h3 class="font-bold text-slate-800 text-md flex items-center gap-2">
                                    <i class="bi-activity text-teal-500"></i> 15-Day Pulse
                                </h3>
                                <p class="text-[9px] font-bold text-slate-400 tracking-widest uppercase mt-1">Short-term Trend</p>
                            </div>
                            <div class="bg-teal-50 px-3 py-1.5 rounded-xl border border-teal-100">
                                <span class="text-[9px] font-bold text-teal-700 uppercase tracking-tight">Avg: {{ $averageRate }}%</span>
                            </div>
                        </div>
                        <div class="relative w-full z-10 flex-1">
                            <canvas id="weeklyAnalyticsChart"></canvas>
                        </div>
                    </div>

                    <!-- Monthly Chart -->
                    <div class="bg-white rounded-[1rem] md:rounded-[1.5rem] p-6 md:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col h-[400px]">
                        <div class="absolute right-0 bottom-0 w-80 h-80 bg-rose-500/5 rounded-full blur-3xl translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                        <div class="flex items-center justify-between mb-6 relative z-10 w-full shrink-0">
                            <div>
                                <h3 class="font-bold text-slate-800 text-md flex items-center gap-2">
                                    <i class="bi-graph-up-arrow text-rose-500"></i> Monthly Trend
                                </h3>
                                <p class="text-[9px] font-bold text-slate-400 tracking-widest uppercase mt-1">Session Averages</p>
                            </div>
                            <div class="bg-rose-50 px-3 py-1.5 rounded-xl border border-rose-100">
                                <span class="text-[9px] font-bold text-rose-700 uppercase tracking-tight">Avg: {{ $averageRate }}%</span>
                            </div>
                        </div>
                        <div class="relative w-full z-10 flex-1">
                            <canvas id="monthlyAnalyticsChart"></canvas>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-[2.5rem] p-12 border border-dashed border-slate-200 text-center flex flex-col items-center justify-center min-h-[500px]">
                    <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-3xl flex items-center justify-center mb-8 rotate-12">
                        <i class="bi-graph-up text-5xl"></i>
                    </div>
                    <h4 class="text-2xl font-black text-slate-800 mb-4">No Class Selected</h4>
                    <p class="text-slate-400 text-sm font-medium max-w-md leading-relaxed">
                        Select a class from the options above to visualize its performance trends and monthly retrospectives.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Shared Options
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
                    callbacks: {
                        label: function(context) { return context.raw + '% Average Presence'; }
                    }
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

        // Weekly Chart
        const weeklyCtxNode = document.getElementById('weeklyAnalyticsChart');
        if(weeklyCtxNode) {
            const weeklyData = @json($weeklyData);
            const avgRate = {{ $averageRate }};
            if(weeklyData.length > 0) {
                new Chart(weeklyCtxNode.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: weeklyData.map(d => d.date),
                        datasets: [
                            {
                                label: 'Attendance Rate',
                                data: weeklyData.map(d => d.percentage),
                                borderColor: '#0f766e', 
                                backgroundColor: 'rgba(20, 184, 166, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#0f766e',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 4
                            },
                            {
                                label: 'Class Average',
                                data: new Array(weeklyData.length).fill(avgRate),
                                borderColor: 'rgba(15, 118, 110, 0.3)',
                                borderDash: [6, 4],
                                borderWidth: 2,
                                fill: false,
                                pointRadius: 0,
                                tension: 0,
                                z: 0
                            }
                        ]
                    },
                    options: sharedOptions
                });
            }
        }

        // Monthly Chart
        const monthlyCtxNode = document.getElementById('monthlyAnalyticsChart');
        if(monthlyCtxNode) {
            const monthlyData = @json($monthlyData);
            const avgRate = {{ $averageRate }};
            if(monthlyData.length > 0) {
                new Chart(monthlyCtxNode.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: monthlyData.map(d => d.date),
                        datasets: [
                            {
                                label: 'Monthly Average',
                                data: monthlyData.map(d => d.percentage),
                                backgroundColor: monthlyData.map(d => d.percentage >= 80 ? '#14b8a6' : (d.percentage >= 50 ? '#f59e0b' : '#fda4af')),
                                borderRadius: 6,
                                borderSkipped: false,
                                barPercentage: 0.6
                            },
                            {
                                label: 'Session Average',
                                data: new Array(monthlyData.length).fill(avgRate),
                                type: 'line',
                                borderColor: 'rgba(225, 29, 72, 0.3)',
                                borderDash: [6, 4],
                                borderWidth: 2,
                                fill: false,
                                pointRadius: 0,
                                tension: 0,
                                z: 10
                            }
                        ]
                    },
                    options: sharedOptions
                });
            }
        }
    });
</script>
@endsection
