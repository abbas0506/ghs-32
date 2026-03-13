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


        <div class="overflow-x-auto bg-white w-full mt-6">
            <div class="flex items-center gap-4">
                @php
                    $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', explode(' ', $student->name))));
                @endphp
                <div
                    class="flex w-8 h-8 p-1 rounded-full bg-teal-500 items-center justify-center text-white font-semibold text-sm shadow-md">
                    {{ $initials }}
                </div>
                <div>
                    <h2 class="text-sm md:text-lg font-semibold text-slate-800">{{ $student->name }}</h2>
                    <p class="text-slate-600 text-sm">{{ $student->father_name }}</p>
                </div>
            </div>

            <div class="statbox indigo mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-end  gap-2 mb-2">
                        <i class="bi-person-check"></i>
                        <p class="text-indigo-600 text-xs font-semibold uppercase tracking-widest mb-1">Attendance</p>
                    </div>
                    <div class="text-xl">
                        @if ($absenceRateOverall < 15)
                            <i class="bi-emoji-happy"></i>
                        @else
                            <i class="bi-emoji-frown"></i>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-around gap-2">
                    <div class="text-center">
                        <label class="text-slate-800">Current Month</label>
                        <h2 class="font-semibold">{{ $currentMonthRate }}%</h2>
                    </div>
                    <div class="text-center">
                        <label class="text-slate-800">Since {{ $sessionStart->format('M Y') }}</label>
                        <h2 class="font-semibold">{{ $absenceRateOverall }}%</h2>
                    </div>
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
                        <table class="table-fixed w-full xs">
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
                                        <td class=" text-slate-700">{{ $loop->index + 1 }}
                                        </td>
                                        <td>
                                            {{ $attendance->date->format('d M Y') }}
                                        </td>
                                        <td>
                                            {{ $attendance->date->locale('ur')->isoFormat('dddd') }}</td>
                                        <td>
                                            <span
                                                class="inline-flex items-center px-2 py-[1px] rounded-full text-xs  bg-red-100 text-red-600">
                                                <i class="bi bi-x mr-1"></i>
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
