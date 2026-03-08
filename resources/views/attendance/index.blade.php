@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <div class="md:w-4/5 mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Class Attendance</h1>
                <div class="bread-crumb">
                    <a href="{{ url('/') }}">Dashboard</a>
                    <div>/</div>
                    <a href="{{ route('attendance.summary') }}">Attendance</a>
                    <div>/</div>
                    <div>{{ $section->name }}</div>
                </div>
            </div>

            <!-- Section Summary Card -->
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl shadow-lg p-6 mb-8 text-white">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-indigo-200 text-xs font-semibold uppercase tracking-widest mb-2">Section</p>
                        <h2 class="text-2xl md:text-3xl font-bold mb-4">{{ $section->name }}</h2>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-indigo-200 text-xs font-semibold uppercase mb-1">Present</p>
                                <p class="text-2xl font-bold">{{ $attendances->where('status', 1)->count() }}</p>
                            </div>
                            <div>
                                <p class="text-indigo-200 text-xs font-semibold uppercase mb-1">Absent</p>
                                <p class="text-2xl font-bold">{{ $attendances->where('status', 0)->count() }}</p>
                            </div>
                            <div>
                                <p class="text-indigo-200 text-xs font-semibold uppercase mb-1">Total</p>
                                <p class="text-2xl font-bold">{{ $attendances->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-indigo-500 rounded-full p-4">
                        <i class="bi bi-people text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- page message -->
            @if ($errors->any())
                <x-message :errors='$errors'></x-message>
            @else
                <x-message></x-message>
            @endif

            <!-- Search and Controls -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex-1 min-w-0">
                        <div class="relative">
                            <input type="text" id='searchby' placeholder="Search by name, roll no, or phone..."
                                class="w-full px-4 py-3 rounded-lg border border-slate-300 text-slate-700 placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                oninput="search(event)">
                            <i class="bx bx-search absolute right-3 top-3 text-slate-400 text-xl"></i>
                        </div>
                    </div>
                    @if (\Carbon\Carbon::parse($date)->isToday())
                        <a href="{{ route('section.attendance.edit', [$section, 1]) }}" aria-label="Edit attendance"
                            class="inline-flex items-center gap-2 px-4 py-3 rounded-lg bg-indigo-600 text-white font-semibold shadow-md hover:bg-indigo-700 hover:shadow-lg transition">
                            <i class="bi bi-pencil-fill"></i>
                            <span class="hidden md:inline">Edit</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex items-center gap-3 mb-6 flex-wrap">
                <button id="tab-present" onclick="showTab('present')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white font-semibold shadow-md hover:shadow-lg hover:bg-indigo-700 transition">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Present</span>
                    <span
                        class="bg-indigo-500 px-2 py-0.5 rounded-full text-xs font-bold">{{ $attendances->where('status', 1)->count() }}</span>
                </button>

                <button id="tab-absent" onclick="showTab('absent')"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border-2 border-slate-300 text-slate-700 font-semibold shadow-sm hover:shadow-md hover:border-slate-400 transition">
                    <i class="bi bi-x-circle-fill"></i>
                    <span>Absent</span>
                    <span
                        class="bg-slate-200 px-2 py-0.5 rounded-full text-xs font-bold">{{ $attendances->where('status', 0)->count() }}</span>
                </button>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wide w-12">
                                    #</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wide">
                                    Student Information</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wide w-32">
                                    Status</th>
                            </tr>
                        </thead>

                        <tbody id="present-list" class="divide-y divide-slate-200">
                            @foreach ($attendances->where('status', 1) as $attendance)
                                <tr class="tr tr-present hover:bg-green-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                        {{ $attendance->student->rollno }}</td>
                                    <td class="px-6 py-4 text-left">
                                        <a href="{{ route('section.attendance.show', [$section, $attendance]) }}"
                                            class="text-indigo-600 hover:text-indigo-700 font-semibold hover:underline">{{ $attendance->student->name }}</a>
                                        <div class="text-xs text-slate-600 mt-1">
                                            <p>{{ $attendance->student->father_name }}</p>
                                            <p class="flex items-center gap-1 text-slate-500">
                                                <i class="bi bi-telephone text-xs"></i>
                                                {{ $attendance->student->phone }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            <i class="bi bi-check-circle"></i>
                                            Present
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tbody id="absent-list" class="divide-y divide-slate-200 hidden">
                            @foreach ($attendances->where('status', 0) as $attendance)
                                <tr class="tr tr-absent hover:bg-red-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                        {{ $attendance->student->rollno }}</td>
                                    <td class="px-6 py-4 text-left">
                                        <a href="{{ route('section.attendance.show', [$section, $attendance]) }}"
                                            class="text-indigo-600 hover:text-indigo-700 font-semibold hover:underline">{{ $attendance->student->name }}</a>
                                        <div class="text-xs text-slate-600 mt-1">
                                            <p>{{ $attendance->student->father_name }}</p>
                                            <p class="flex items-center gap-1 text-slate-500">
                                                <i class="bi bi-telephone text-xs"></i>
                                                {{ $attendance->student->phone }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                            <i class="bi bi-x-circle"></i>
                                            Absent
                                        </span>
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

            function showTab(tab) {
                var presentBtn = document.getElementById('tab-present');
                var absentBtn = document.getElementById('tab-absent');
                var presentList = document.getElementById('present-list');
                var absentList = document.getElementById('absent-list');

                if (tab === 'present') {
                    presentList.classList.remove('hidden');
                    absentList.classList.add('hidden');

                    presentBtn.classList.remove('bg-white', 'border', 'text-slate-700');
                    presentBtn.classList.add('bg-indigo-600', 'text-white');

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
