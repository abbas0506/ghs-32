@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h1>Attendance Record</h1>
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


        <div class="overflow-x-auto bg-white w-full md:w-4/5 mx-auto mt-6">
            <div class="flex items-center gap-4">
                @php
                    $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', explode(' ', $student->name))));
                @endphp
                <div
                    class="flex w-8 h-8 p-1 rounded-full bg-teal-500 items-center justify-center text-white font-semibold text-sm shadow-md">
                    {{ $initials }}
                </div>
                <div>
                    <h2 class="text-xs md:text-base font-semibold text-slate-800 leading-tight">{{ $student->name }}</h2>
                    <p class="text-slate-600 text-xs md:text-sm">{{ $student->father_name }}</p>
                </div>
            </div>

            <div class="statbox indigo mt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col items-start gap-2 mt-2">
                        <div class="flex items-end gap-1">
                            <i class="ri-user-follow-line"></i>
                            <p class="text-indigo-600 text-xs font-semibold uppercase tracking-widest mb-1">Attendance Rate
                            </p>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-3 text-xs">
                                <div class="pill green text-xs">{{ $student->section->name }}</div>
                                <i class="bi-arrow-right"></i>
                                <div class="pill indigo text-xs">Roll # {{ $student->rollno }}</div>
                            </div>
                        </div>
                    </div>

                    @php
                        $trending = $monthAttendancePercentage >= $sessionAttendancePercentage ? 'up' : 'down';
                    @endphp
                    <div class="flex items-center justify-around gap-2">
                        <div class="text-center">
                            <label class="text-slate-800">Current Month</label>
                            <h2 class="font-semibold">{{ $monthAttendancePercentage }}% <i
                                    class="bx bx-trending-{{ $trending }}"></i> </h2>
                        </div>
                        <div class="text-center">
                            <label class="text-slate-800">Since {{ $sessionStart->format('M Y') }}</label>
                            <h2 class="font-semibold">{{ $sessionAttendancePercentage }}% @if ($sessionAttendancePercentage >= 80)
                                    <i class="bi-emoji-happy"></i>
                                @else
                                    <i class="bi-emoji-frown"></i>
                                @endif
                            </h2>
                        </div>
                    </div>

                </div>


            </div>


            @if ($sessionAbsences->count())
                <div class="mt-8 ">
                    <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="bi bi-x-circle text-red-500"></i>
                        Absence Record <span class="text-sm font-normal text-slate-500">({{ $sessionAbsences->count() }}
                            entries)</span>
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-slate-200  ">
                        <table class="table-fixed borderless w-full">
                            <thead class="bg-slate-50 text-slate-700">
                                <tr>
                                    <th class="w-8">#</th>
                                    <th class="w-36 text-left">Date</th>
                                    <th class="w-12">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($sessionAbsences->sortBy('date') as $attendance)
                                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                                        <td class=" text-slate-700">
                                            <div class="ico rose mx-auto">
                                                {{ $loop->index + 1 }}
                                            </div>

                                        </td>
                                        <td class="text-left">
                                            {{ $attendance->date->format('d M Y') }} —
                                            {{ $attendance->date->format('D') }}
                                        </td>
                                        <td>
                                            <div class="flex justify-center">
                                                <span class="pill red text-xs">
                                                    Absent
                                                </span>
                                            </div>

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
