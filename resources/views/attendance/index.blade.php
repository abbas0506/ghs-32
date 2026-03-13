@extends('layouts.app')
@section('page-content')
    <div class="mb-6">
        <h1 class="text-slate-800">Class Attendance</h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Dashboard</a>
            <div>/</div>
            <a href="{{ route('attendance.summary') }}">Attendance</a>
            <div>/</div>
            <div>{{ $section->name }}</div>
        </div>
    </div>

    <!-- Section Summary Card -->
    <div class="statbox indigo">
        <div class="flex justify-between items-center">
            <h2 class="text-lg md:text-xl font-semibold">{{ $section->name }}</h2>
            @if (\Carbon\Carbon::parse($date)->isToday())
                <a href="{{ route('section.attendance.edit', [$section, 1]) }}" aria-label="Edit attendance"
                    class="inline-flex w-8 h-8 justify-center items-center rounded-full gap-2 p-1 bg-indigo-600 text-white font-semibold transition">
                    <i class="bx bx-pencil"></i>
                </a>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-2 md:gap-4 mt-2">
            <div c>
                <p class="text-xs font-semibold mb-1">Attendance</p>
                <p class="text-lg font-bold">{{ $attendances->where('status', 1)->count() }} / {{ $attendances->count() }}
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold mb-1">Percentage</p>
                <p class="text-lg font-bold">
                    {{ $attendances->count() > 0 ? round(($attendances->where('status', 1)->count() / $attendances->count()) * 100, 1) : 0 }}%
                    {{-- show similey according to attendance percentage --}}
                    <?php
                    $similey = 'neutral';
                    $percentage = $attendances->count() > 0 ? round(($attendances->where('status', 1)->count() / $attendances->count()) * 100, 1) : 0;
                    if ($percentage >= 90) {
                        $similey = 'smile';
                    } elseif ($percentage < 90 && $percentage >= 75) {
                        $similey = 'neutral';
                    } else {
                        $similey = 'angry';
                    }
                    ?>
                    <i class="bi bi-emoji-<?php echo $similey; ?> text-2xl"></i>
                </p>

            </div>
        </div>
    </div>
    <!-- page message -->
    @if ($errors->any())
        <x-message :errors='$errors'></x-message>
    @else
        <x-message></x-message>
    @endif

    <div class="flex
                        justify-between items-center flex-wrap gap-3 mt-8">
        <!-- Tabs -->
        <div class="flex flex-wrap items-center gap-x-2 text-sm mt-8">
            <span class="text-slate-600 hover:cursor-pointer" onclick="filterBy('all')"><i class="bi-filter"></i></span>
            <span class="bg-green-50 text-green-600 hover:cursor-pointer px-2 py-[2px] text-xs rounded-full "
                onclick="filterBy('present')"><i class="bi-check"></i> Present
            </span>
            <span class="bg-red-50 text-red-600 hover:cursor-pointer px-2 py-[2px] text-xs rounded-full"
                onclick="filterBy('absent')"> <i class="bi-x"></i> Absent
            </span>
        </div>
        <!-- search -->
        <div class="flex relative w-full md:w-1/3">
            <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                oninput="search(event)">
            <i class="bx bx-search absolute top-2 right-2"></i>
        </div>
    </div>
    <!-- Table -->
    <div class="rounded-lg xs overflow-hidden mt-3">
        <div class="overflow-x-auto">
            <table class="table-fixed w-full border-collapse">
                <thead>
                    <tr>
                        <th class="w-8">#</th>
                        <th class="text-left w-36">Student</th>
                        <th class="w-20">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @foreach ($attendances as $attendance)
                        <tr class="tr {{ $attendance->status == 1 ? 'present' : 'absent' }}">
                            <td class="text-sm font-semibold text-slate-700">
                                {{ $attendance->student->rollno }}</td>
                            <td class="text-left">
                                <a href="{{ route('section.attendance.show', [$section, $attendance]) }}"
                                    class="text-indigo-600">{{ $attendance->student->name }}</a>
                                <div class="text-xs text-slate-600 mt-1">
                                    <p>{{ $attendance->student->father_name }}</p>
                                    <p class="flex items-center text-slate-500">
                                        <i class="bi bi-telephone text-xs"></i>
                                        {{ $attendance->student->phone }}
                                    </p>
                                </div>
                            </td>
                            <td class="text-center">
                                @if ($attendance->status == 1)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-[1px] rounded-full bg-green-100 text-green-600 text-xs">
                                        <i class="bi bi-check"></i>Present
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-[1px] rounded-full bg-red-100 text-red-700 text-xs">
                                        <i class="bi bi-x"></i>
                                        Absent
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Close Button -->
    <div class="text-center mt-8">
        <a href="{{ route('attendance.summary') }}"
            class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-slate-600 text-white font-semibold shadow-md hover:bg-slate-700 hover:shadow-lg transition">
            <i class="bi bi-arrow-left"></i>
            Back to Summary
        </a>
    </div>
    </div>
    <script>
        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(0).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(1).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }

        function filterBy(criteria) {
            if (criteria == 'all') {
                $('.tr').each(function() {
                    $(this).removeClass('hidden');
                });
            } else {
                // show submitted or pending as selected
                $('.tr').each(function() {
                    if ((
                            $(this).hasClass(criteria)
                        )) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            }

        }

        function showTab(tab) {
            var presentBtn = document.getElementById('tab-present');
            var absentBtn = document.getElementById('tab-absent');
            var presentList = document.getElementById('present-list');
            var absentList = document.getElementById('absent-list');

            if (tab === 'present') {
                presentList.classList.remove('hidden');
                absentList.classList.add('hidden');

                presentBtn.classList.remove('bg-white', 'border', 'text-slate-700');
                presentBtn.classList.add('bg-green-600', 'text-white');

                absentBtn.classList.remove('bg-indigo-600', 'text-white');
                absentBtn.classList.add('bg-white', 'border', 'text-slate-700');
            } else {
                presentList.classList.add('hidden');
                absentList.classList.remove('hidden');

                absentBtn.classList.remove('bg-white', 'border', 'text-slate-700');
                absentBtn.classList.add('bg-indigo-600', 'text-white');

                presentBtn.classList.remove('bg-indigo-600', 'text-white');
                presentBtn.classList.add('bg-white', 'border', 'text-slate-700');
            }
        }

        // initialize default tab on load
        document.addEventListener('DOMContentLoaded', function() {
            var presentCount = parseInt(document.querySelector('#tab-present span:nth-child(3)').innerText || '0',
                10);
            if (presentCount > 0) showTab('present');
            else showTab('absent');
        });
    </script>

    </div>
@endsection
