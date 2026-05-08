@extends('layouts.app')

@section('style')
<style>
    @media print {
        @page {
            size: landscape;
            margin: 0.5cm;
        }
        
        /* Hide everything by default */
        body * {
            visibility: hidden;
        }
        
        /* Show only the report content */
        #printableReport, #printableReport * {
            visibility: visible;
        }
        
        #printableReport {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        /* Optimize table for print */
        table {
            width: 100% !important;
            table-layout: auto !important;
            font-size: 9px !important; /* Smaller text to fit all columns */
        }

        th, td {
            padding: 6px 4px !important;
            border: 1px solid #f1f5f9 !important;
        }

        /* Remove sticky columns during print */
        .sticky {
            position: static !important;
            background: white !important;
        }

        .rounded-\[2rem\], .rounded-\[2\.5rem\] {
            border-radius: 0 !important;
        }

        .shadow-sm, .shadow-lg {
            box-shadow: none !important;
        }

        .bg-teal-50\/20, .bg-teal-50\/30 {
            background-color: transparent !important;
            border: 1px solid #f1f5f9 !important;
        }
    }
</style>
@endsection

@section('page-content')
    <div class="flex flex-col space-y-6" id="printableReport">
        <!-- Print Only Header -->
        <div class="hidden print:block mb-8 border-b-2 border-slate-800 pb-4">
            <h1 class="text-2xl font-bold uppercase text-slate-800 tracking-tighter">Comparative Performance Report</h1>
            <div class="flex flex-wrap gap-x-12 gap-y-2 mt-4">
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Subject</span>
                    <span class="text-xs font-bold text-slate-700 uppercase">{{ $allocation->subject->name }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Class / Section</span>
                    <span class="text-xs font-bold text-slate-700 uppercase">{{ $allocation->section->name }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Instructor</span>
                    <span class="text-xs font-bold text-slate-700 uppercase">{{ $allocation->user->profile->name ?? 'Unknown Teacher' }}</span>
                </div>
                <div class="flex flex-col ml-auto text-right">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Generated On</span>
                    <span class="text-xs font-bold text-slate-700 uppercase">{{ now()->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2 no-print">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-3">
                    <a href="{{ route('class-tests.analysis') }}" class="hover:text-teal-600 transition-colors">Analysis Engine</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-teal-600 uppercase">{{ $allocation->subject->name }} ({{ $allocation->section->name }})</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-800 text-white flex items-center justify-center shadow-lg">
                        <i class="bi-bar-chart-fill text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 leading-none mb-1">Comparative Insights</h2>
                        <p class="text-slate-400 text-xs font-medium italic uppercase tracking-tighter">Instructor: {{ $allocation->user->profile->name ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                 <button onclick="window.print()" 
                   class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-500 rounded-xl text-xs font-bold uppercase tracking-widest hover:text-teal-600 hover:border-teal-200 transition-all">
                   <i class="bi-printer"></i> Print Report
                </a>
            </div>
        </div>

        @if($testSubjects->count() > 0)
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden overflow-x-auto print:border-none">
                <table class="w-full min-w-[1000px] border-collapse chart-table">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="p-3 text-left border-b border-slate-100 sticky left-0 bg-slate-50 z-10 w-64 md:w-72">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Student Details</span>
                            </th>
                            @foreach($testSubjects as $ts)
                                <th class="p-3 text-center border-b border-slate-100 min-w-[100px]">
                                    <div class="flex flex-col items-center">
                                        <span class="text-[9px] font-bold text-teal-600 uppercase tracking-widest mb-0.5">{{ $ts->test->title }}</span>
                                        <span class="text-[8px] font-bold text-slate-400 p-0.5 bg-white rounded-md border border-slate-100 mb-1">{{ optional($ts->test_date)->format('d M') ?? 'N/A' }}</span>
                                        <span class="text-[9px] font-bold text-slate-700">Total: {{ $ts->max_marks }}</span>
                                    </div>
                                </th>
                            @endforeach
                            <th class="p-3 text-center border-b border-slate-100 bg-teal-50/50">
                                <span class="text-[10px] font-bold text-teal-700 uppercase tracking-widest">Avg %</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @php
                                $totalPercentage = 0;
                                $countedTests = 0;
                                $performanceData = [];
                            @endphp
                            
                            @foreach($testSubjects as $ts)
                                @php
                                    $result = $ts->results->where('student_id', $student->id)->first();
                                    $marks = $result ? $result->obtained_marks : null;
                                    $perc = ($marks !== null && $ts->max_marks > 0) ? ($marks / $ts->max_marks) * 100 : null;
                                    if($perc !== null) {
                                        $totalPercentage += $perc;
                                        $countedTests++;
                                    }
                                    $performanceData[] = [
                                        'test' => $ts->test->title,
                                        'date' => optional($ts->test_date)->format('d M'),
                                        'perc' => $perc !== null ? round($perc, 1) : 0,
                                        'marks' => $marks ?? 0,
                                        'max' => $ts->max_marks
                                    ];
                                @endphp
                            @endforeach

                            <tr class="hover:bg-teal-50/30 transition-all cursor-pointer group border-b border-slate-50" 
                                onclick="showStudentTrends('{{ addslashes($student->name) }}', {{ json_encode($performanceData) }})">
                                <td class="p-2 sticky left-0 bg-white group-hover:bg-slate-50/50 z-10 transition-colors">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 shrink-0 rounded-lg bg-slate-100 text-slate-400 group-hover:bg-teal-600 group-hover:text-white flex items-center justify-center text-[10px] font-bold transition-all">{{ $student->rollno }}</span>
                                        <div class="flex flex-col min-w-0">
                                            <span class="text-[11px] font-bold text-slate-700 uppercase group-hover:text-teal-600 transition-colors truncate">{{ $student->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                @foreach($performanceData as $data)
                                    @php $isFail = $data['perc'] > 0 && $data['perc'] < 40; @endphp
                                    <td class="p-2 text-center">
                                        @if($data['max'] > 0)
                                            <div class="flex flex-col items-center gap-0.5">
                                                <span class="text-[11px] font-bold {{ $isFail ? 'text-red-600' : 'text-slate-700' }}">{{ $data['marks'] }}</span>
                                                <div class="w-10 h-1 bg-slate-100 rounded-full overflow-hidden no-print">
                                                    <div class="h-full {{ $isFail ? 'bg-red-400' : 'bg-teal-500' }}" style="width: {{ $data['perc'] }}%"></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-300 font-bold">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="p-2 text-center bg-teal-50/20">
                                    @if($countedTests > 0)
                                        @php $finalAvg = round($totalPercentage / $countedTests, 1); @endphp
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $finalAvg < 40 ? 'bg-red-100 text-red-600' : 'bg-teal-100 text-teal-600' }}">
                                            {{ $finalAvg }}%
                                        </span>
                                    @else
                                        <span class="text-slate-300 font-bold italic text-[10px]">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50/50 border-t-2 border-slate-100">
                            <td class="p-6 sticky left-0 bg-slate-50/80 backdrop-blur-sm z-10 border-b border-slate-100">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Class Average</span>
                            </td>
                            @php $classAverages = []; @endphp
                            @foreach($testSubjects as $ts)
                                @php
                                    $allMarks = $ts->results->pluck('obtained_marks')->filter(fn($m) => $m !== null);
                                    $classAvgPerc = ($allMarks->count() > 0 && $ts->max_marks > 0) 
                                        ? round(($allMarks->average() / $ts->max_marks) * 100, 1) 
                                        : 0;
                                    $classAverages[] = $classAvgPerc;
                                @endphp
                                <td class="p-6 text-center border-b border-slate-100">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-bold text-teal-600">{{ $classAvgPerc }}%</span>
                                        <div class="w-16 h-1.5 bg-slate-200 rounded-full mt-2 overflow-hidden">
                                            <div class="h-full bg-teal-500" style="width: {{ $classAvgPerc }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                            <td class="p-6 text-center border-b border-slate-100 bg-teal-50/30">
                                @php
                                    $overallAvg = $students->map(function($s) use ($testSubjects) {
                                        $results = $testSubjects->map(fn($ts) => $ts->results->where('student_id', $s->id)->first())
                                            ->filter(fn($r) => $r !== null);
                                        if($results->isEmpty()) return null;
                                        return $results->average(fn($r) => ($r->obtained_marks / $r->testSubject->max_marks) * 100);
                                    })->filter()->average() ?? 0;
                                @endphp
                                <span class="text-xs font-bold text-teal-700">{{ round($overallAvg, 1) }}%</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="py-20 flex flex-col items-center text-center bg-white rounded-[2.5rem] border border-slate-100">
                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mb-6 text-slate-300">
                    <i class="bi-bar-chart-line text-4xl"></i>
                </div>
                <h3 class="font-bold text-slate-500 uppercase tracking-widest text-lg">No assessment data available</h3>
                <p class="text-slate-400 text-sm mt-2 italic max-w-sm mx-auto">This specific subject and class combination does not have any recorded individual tests for comparative analysis yet.</p>
                <a href="{{ route('class-tests.create') }}" class="mt-8 px-8 py-3 bg-teal-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-teal-700 transition-all">
                    Create First Test
                </a>
            </div>
        @endif

        <!-- Student Analysis Modal -->
        <div id="chartModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
            <div class="min-h-full flex items-center justify-center p-4 sm:p-6">
                <div class="relative bg-white w-full max-w-4xl rounded-[2.5rem] shadow-2xl pointer-events-auto overflow-hidden animate-in zoom-in duration-300 my-auto">
                    <div class="p-6 md:p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shadow-sm">
                                <i class="bi-graph-up-arrow text-xl md:text-2xl"></i>
                            </div>
                            <div>
                                <h3 id="modalStudentName" class="text-base md:text-xl font-bold text-slate-800 uppercase leading-none mb-1">Student Performance</h3>
                                <p class="text-slate-400 text-[10px] md:text-xs font-medium italic underline decoration-teal-500/30 decoration-2">Trends across selected assessments</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between md:justify-end gap-4">
                             <div class="hidden sm:flex items-center gap-4 mr-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-teal-500"></div>
                                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Student</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
                                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Class Avg</span>
                                </div>
                            </div>
                            <button onclick="closeModal()" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-all flex items-center justify-center">
                                <i class="bi-x-lg text-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="h-[200px] sm:h-[250px] md:h-[300px]">
                            <canvas id="studentTrendChart"></canvas>
                        </div>
                    </div>
                    <div class="px-6 md:px-8 pb-6 flex items-center justify-center gap-6 md:gap-12 border-t border-slate-50 pt-6 mt-2">
                         <div class="flex flex-col items-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Average</span>
                            <span id="statAvg" class="text-xl font-bold text-teal-600">-</span>
                        </div>
                         <div class="flex flex-col items-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Highest</span>
                            <span id="statHigh" class="text-xl font-bold text-slate-800">-</span>
                        </div>
                         <div class="flex flex-col items-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Tests</span>
                            <span id="statTests" class="text-xl font-bold text-slate-800">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    let trendChart = null;
    const classAverages = {{ json_encode($classAverages ?? []) }};

    window.showStudentTrends = function(name, data) {
        const modal = document.getElementById('chartModal');
        const ctx = document.getElementById('studentTrendChart').getContext('2d');
        
        document.getElementById('modalStudentName').innerText = name;
        modal.classList.remove('hidden');

        // Stats
        const validScores = data.filter(d => d.perc > 0).map(d => d.perc);
        const avg = validScores.length > 0 ? (validScores.reduce((a, b) => a + b) / validScores.length).toFixed(1) : 0;
        const max = validScores.length > 0 ? Math.max(...validScores).toFixed(1) : 0;

        document.getElementById('statAvg').innerText = avg + '%';
        document.getElementById('statHigh').innerText = max + '%';
        document.getElementById('statTests').innerText = validScores.length;

        if (trendChart) trendChart.destroy();

        trendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => `${d.test} (${d.date})`),
                datasets: [
                    {
                        label: 'Student Score',
                        data: data.map(d => d.perc),
                        borderColor: '#0d9488',
                        backgroundColor: 'rgba(13, 148, 136, 0.05)',
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0d9488',
                        pointBorderWidth: 4,
                        pointRadius: 6,
                        tension: 0.4,
                        fill: true,
                        zIndex: 2
                    },
                    {
                        label: 'Class Average',
                        data: classAverages,
                        borderColor: '#cbd5e1',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        tension: 0.4,
                        fill: false,
                        zIndex: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 10 },
                        callbacks: {
                            label: function(context) {
                                const idx = context.dataIndex;
                                const item = data[idx];
                                return ` Score: ${item.marks} / ${item.max} (${item.perc}%)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        ticks: { font: { size: 10, weight: '600' }, color: '#94a3b8', callback: value => value + '%' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' }, color: '#94a3b8' }
                    }
                }
            }
        });
    };

    window.closeModal = function() {
        document.getElementById('chartModal').classList.add('hidden');
    };
</script>
@endsection
