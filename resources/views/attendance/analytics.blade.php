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
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 leading-none mb-1">Attendance Analytics</h2>
                        <p class="text-slate-400 text-xs font-medium italic">Graphical timeline of class presence rates</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('attendance.summary') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm flex items-center justify-center gap-2">
                <i class="bi-arrow-left"></i> Back to Summary
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12 items-start">
            <!-- Left Side: Filters -->
            <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm h-fit sticky top-6">
                <form action="{{ route('attendance.analytics') }}" method="GET" class="space-y-6" id="analyticsForm">
                    <div class="space-y-4">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">1. Select Class</label>
                        <div class="grid grid-cols-1 gap-3 max-h-[400px] overflow-y-auto pr-2 relative">
                            @foreach($sections as $section)
                                <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 cursor-pointer hover:bg-slate-50 transition-all group {{ $selectedSectionId == $section->id ? 'bg-teal-50/50 border-teal-200' : '' }}">
                                    <input type="radio" name="class_id" value="{{ $section->id }}" class="sr-only peer" {{ $selectedSectionId == $section->id ? 'checked' : '' }} onchange="document.getElementById('analyticsForm').submit()">
                                    <div class="w-5 h-5 rounded-full border-2 {{ $selectedSectionId == $section->id ? 'border-teal-600 bg-white' : 'border-slate-200 bg-white' }} mr-4 flex items-center justify-center transition-all overflow-hidden shrink-0">
                                        <div class="w-2.5 h-2.5 rounded-full bg-teal-600 {{ $selectedSectionId == $section->id ? 'opacity-100 scale-100' : 'opacity-0 scale-50' }} transition-all"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold {{ $selectedSectionId == $section->id ? 'text-teal-700' : 'text-slate-700' }} group-hover:text-teal-600 transition-colors leading-tight">
                                            Class {{ $section->name }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Side: Chart -->
            <div class="lg:col-span-2 space-y-6 h-full flex flex-col">
                @if($selectedSection && count($weeklyData) > 0)
                    <!-- Weekly Chart -->
                    <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col h-[380px]">
                        <div class="absolute right-0 bottom-0 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                        <div class="flex items-center justify-between mb-6 relative z-10 w-full shrink-0">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                    <i class="bi-activity text-teal-500"></i> 15-Day Pulse
                                </h3>
                                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1">Class {{ $selectedSection->name }} - Last 15 Days</p>
                            </div>
                        </div>
                        <div class="relative w-full z-10 flex-1 overflow-x-auto custom-scrollbar">
                            <div class="min-w-[700px] w-full min-h-[200px] relative">
                                <canvas id="weeklyAnalyticsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Chart -->
                    <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col h-[400px]">
                        <div class="absolute right-0 bottom-0 w-80 h-80 bg-rose-500/5 rounded-full blur-3xl translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                        <div class="flex items-center justify-between mb-6 relative z-10 w-full shrink-0">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                    <i class="bi-graph-up-arrow text-rose-500"></i> Monthly Retrospective
                                </h3>
                                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-1">Class {{ $selectedSection->name }} - Session Averages</p>
                            </div>
                        </div>
                        <div class="relative w-full z-10 flex-1 overflow-x-auto custom-scrollbar">
                            <div class="min-w-[700px] w-full min-h-[220px] relative">
                                <canvas id="monthlyAnalyticsChart"></canvas>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm h-full flex flex-col items-center justify-center text-center min-h-[400px]">
                        <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-6">
                            <i class="bi-bar-chart text-4xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-slate-700 mb-2">No Data Available</h4>
                        <p class="text-slate-400 text-xs font-bold max-w-sm">Please select a valid class and analytical period on the left to review the attendance rate.</p>
                    </div>
                @endif
            </div>
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
            if(weeklyData.length > 0) {
                new Chart(weeklyCtxNode.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: weeklyData.map(d => d.date),
                        datasets: [{
                            label: 'Attendance Rate',
                            data: weeklyData.map(d => d.percentage),
                            borderColor: '#0f766e', 
                            backgroundColor: 'rgba(20, 184, 166, 0.15)',
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#0f766e',
                            pointBorderWidth: 3,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: sharedOptions
                });
            }
        }

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
                    options: sharedOptions
                });
            }
        }
    });
</script>
@endsection
