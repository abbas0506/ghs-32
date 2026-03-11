@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h1>Absence History</h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Dashoboard</a>
            <div>/</div>
            <a href="{{ route('section.attendance.index', $section) }}">Attendance</a>
            <div>/</div>
            <div>History</div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Absence Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-8">
            <!-- Card 1: Current Month Absence -->
            <div class="statbox orange">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="text-orange-600 text-xs font-semibold uppercase tracking-widest mb-1">Current Month</p>
                        <h3 class="text-slate-800 font-bold text-xl">{{ now()->format('M Y') }}</h3>
                    </div>
                    <div class="bg-orange-100 rounded-full p-3">
                        <i class="bi bi-calendar-month text-orange-600 text-2xl"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-4xl font-black text-slate-900">{{ $currentMonthAbsences }}</span>
                            <span class="text-xs text-slate-600 font-semibold">{{ $currentMonthRate }}%</span>
                        </div>
                        <p class="text-sm text-slate-600">{{ $currentMonthAbsences }} out of {{ $currentMonthTotal }} days
                        </p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-400 to-orange-600 h-2 rounded-full transition-all"
                            style="width: {{ min($currentMonthRate, 100) }}%"></div>
                    </div>

                    <!-- Trend Badge -->
                    <div class="flex items-center gap-2 pt-2">
                        @if ($currentMonthTrend === 'up')
                            <div class="bg-red-100 rounded-full p-2">
                                <i class="bx bx-trending-up text-red-600 font-bold"></i>
                            </div>
                            <span class="text-xs text-red-700 font-semibold">Rising trend</span>
                        @else
                            <div class="bg-green-100 rounded-full p-2">
                                <i class="bx bx-trending-down text-green-600 font-bold"></i>
                            </div>
                            <span class="text-xs text-green-700 font-semibold">Improving trend</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card 2: Overall Session Absence -->
            <div
                class="bg-gradient-to-br from-white to-indigo-50 rounded-xl shadow-lg hover:shadow-xl p-6 border border-indigo-200 transition-all duration-300 transform hover:scale-105">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="text-indigo-600 text-xs font-semibold uppercase tracking-widest mb-1">Overall Progress</p>
                        <h3 class="text-slate-800 font-bold text-xl">{{ $sessionStart->format('M Y') }} onwards</h3>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-3">
                        <i class="bi bi-pie-chart text-indigo-600 text-2xl"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="flex items-baseline justify-between mb-2">
                            <span class="text-4xl font-black text-slate-900">{{ $totalAbsencesInPeriod }}</span>
                            <span class="text-xs text-slate-600 font-semibold">{{ $absenceRateOverall }}%</span>
                        </div>
                        <p class="text-sm text-slate-600">{{ $totalAbsencesInPeriod }} out of {{ $totalDaysInPeriod }}
                            total days</p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-400 to-indigo-600 h-2 rounded-full transition-all"
                            style="width: {{ min($absenceRateOverall, 100) }}%"></div>
                    </div>

                    <!-- Performance Badge -->
                    <div class="flex items-center gap-2 pt-2">
                        @if ($absenceRateOverall < 15)
                            <span class="text-3xl">😊</span>
                            <div>
                                <p class="text-xs font-semibold text-green-700">Excellent Attendance</p>
                                <p class="text-xs text-slate-600">Below 15% absence rate</p>
                            </div>
                        @else
                            <span class="text-3xl">😔</span>
                            <div>
                                <p class="text-xs font-semibold text-red-700">Needs Improvement</p>
                                <p class="text-xs text-slate-600">Above 15% absence rate</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white w-full mt-8">
            <div class="flex items-center gap-4 mb-6">
                @php
                    $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', explode(' ', $student->name))));
                    $colors = [
                        'bg-indigo-500',
                        'bg-blue-500',
                        'bg-green-500',
                        'bg-purple-500',
                        'bg-pink-500',
                        'bg-red-500',
                        'bg-yellow-500',
                        'bg-teal-500',
                    ];
                    $colorIndex = (strlen($student->name) + $student->id) % count($colors);
                    $bgColor = $colors[$colorIndex];
                @endphp
                <div
                    class="flex w-8 h-8 p-1 rounded-full {{ $bgColor }} flex items-center justify-center text-white font-bold text-sm shadow-md">
                    {{ $initials }}
                </div>
                <div>
                    <h2 class="font-semibold text-slate-800">{{ $student->name }}</h2>
                    <p class="text-slate-600 text-sm">{{ $student->father_name }}</p>
                </div>
            </div>

            @if ($attendances->count())
                <div class="mt-8">
                    <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="bi bi-x-circle text-red-500"></i>
                        Absence Record <span class="text-sm font-normal text-slate-500">({{ $attendances->count() }}
                            entries)</span>
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="table-fixed w-full">
                            <thead class="bg-slate-50 text-slate-700">
                                <tr>
                                    <th class="w-8">#</th>
                                    <th class="w-36">Date</th>
                                    <th class="w-16">Day</th>
                                    <th class="w-24">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($attendances as $attendance)
                                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                                        <td class=" text-slate-700 font-medium">{{ $loop->index + 1 }}
                                        </td>
                                        <td>
                                            {{ $attendance->date->format('d M Y') }}
                                        </td>
                                        <td>
                                            {{ $attendance->date->locale('ur')->isoFormat('dddd') }}</td>
                                        <td>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="bi bi-x-circle mr-1"></i>
                                                Absent
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="mt-8 text-center py-12 bg-slate-50 rounded-lg border-2 border-dashed border-slate-300">
                    <i class="bi bi-inbox text-slate-400 text-5xl mb-4 block"></i>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">No Absences</h3>
                    <p class="text-slate-600">Great! {{ $student->name }} has perfect attendance during this period.</p>
                </div>
            @endif

        </div>
        <div class="text-center mt-8">
            <a href="{{ route('section.attendance.index', $section) }}" class="btn-blue rounded py-2 px-5">Close</a>
        </div>
    </div>
@endsection
