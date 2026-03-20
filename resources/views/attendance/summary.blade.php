@extends('layouts.app')
@section('page-content')
    <h2 class=""> <i class="ri-user-6-fill"></i> Attendance Management</h2>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <div>Attendance</div>

    </div>
    <div class="w-full md:w-4/5 mx-auto bg-white mt-8">

        {{-- Filter Section --}}
        <div class="">
            <div class="statbox cyan md:p-8">
                <div class="flex items-center flex-wrap gap-2 md:gap-8 mb-4">
                    <div class="flex space-x-3 items-center">
                        <i class="bi bi-calendar-range text-xl"></i>
                        <div>
                            <p class=" text-xs font-semibold uppercase tracking-widest">Select Date</p>
                            <p class="text-sm font-semibold">Filter attendance by date</p>
                        </div>
                    </div>
                    <form action="{{ route('attendance.filter') }}" method="post" id="form_filter">
                        @csrf
                        <input type="hidden" name="date" id="date">
                        <input type="date" id='filter_date'
                            class="w-full md:w-64 px-3 py-2 border rounded-lg border-slate-200 text-slate-800 font-medium text-sm focus:ring-2 focus:ring-cyan-400">
                    </form>
                </div>

            </div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Overall Stats Header --}}

        <div class="border-[0.5px] rounded-lg bg-white mt-6">
            <div
                class="grid grid-cols-1 md:grid-cols-2 p-5 md:p-8 gap-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50">
                <div class="flex items-center gap-3 ">
                    <div class="ico w-9 h-9 indigo rounded-xl ">
                        <i class="bi-calendar-check text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-800 text-sm leading-tight">
                            {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                        </h2>

                        <p class="text-xs text-gray-400"> <i class="bi-cloud-check"></i> {{ $sectionsMarked }} out of
                            {{ $sections->count() }}</p>
                    </div>

                </div>
                @if ($sections->count() > 1 && $overallAttendanceCount > 0)
                    <div class="flex flex-col items-end w-full">
                        <h2 class="text-lg md:2xl font-bold">
                            {{ round(($overallPresenceCount / $overallAttendanceCount) * 100, 1) }}%
                        </h2>
                        <p class="text-sm text-gray-700"><i class="ri-user-6-fill mr-1"></i>
                            {{ $overallPresenceCount }}/{{ $overallAttendanceCount }}</p>

                    </div>
                @endif

            </div>

            {{-- Section Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-5 md:p-8">
                @foreach ($sections as $section)
                    @php

                        $attendancePercentage =
                            $section->attendanceCount > 0
                                ? round(($section->presenceCount / $section->attendanceCount) * 100, 1)
                                : 0;

                        $themeColor =
                            $section->attendanceCount > 0
                                ? ($attendancePercentage >= 90
                                    ? 'green'
                                    : ($attendancePercentage < 90 && $attendancePercentage >= 75
                                        ? 'cyan'
                                        : 'red'))
                                : 'gray';

                    @endphp
                    <a href="@if ($section->attenanceCount > 0) {{ route('section.attendance.index', $section) }} @elseif(\Carbon\Carbon::parse($date)->isToday()){{ route('section.attendance.create', $section) }} @else {{ route('section.attendance.index', $section) }} @endif "
                        class="statbox {{ $themeColor }} transition-all duration-300 transform hover:scale-105">
                        {{-- <div class="flex items-center"> --}}
                        <div class="ico {{ $themeColor }}"><i class="ri-user-6-line"></i></div>

                        <div class="leading-none mt-1">
                            <h3 class="">{{ $section->name }}</h3>
                            <span class="text-slate-400 text-xs md:text-sm font-normal">Average attendance:
                                {{ $section->averageAttendance() ?? 0 }} %</span>
                        </div>

                        {{-- if attendance marked show progress bar --}}
                        @if ($section->attendanceCount > 0)
                            {{-- Progress Bar --}}
                            <div class="grid grid-cols-2 content-center items-end gap-2 mt-1">
                                <div class="text-{{ $themeColor }}-600 text-sm">
                                    {{ $section->presenceCount ?? 0 }} /
                                    {{ $section->attendanceCount ?? 0 }}
                                </div>
                                <div class="">
                                    <div>
                                        <span
                                            class="text-xs font-semibold text-{{ $themeColor }}-600">{{ $attendancePercentage }}%</span>

                                        @if ($attendancePercentage > $section->averageAttendance())
                                            <i class="bx bx-trending-up"></i>
                                        @else
                                            <i class="bx bx-trending-down"></i>
                                        @endif
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-gradient-to-r {{ $attendancePercentage >= 90 ? 'from-green-200 to-green-500' : ($attendancePercentage >= 75 ? 'from-cyan-200 to-cyan-500' : 'from-red-200 to-red-500') }} h-2 rounded-full transition-all"
                                            style="width: {{ min($attendancePercentage, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-2 content-center items-end gap-2 mt-1">
                                <div class="text-{{ $themeColor }}-600 text-sm">-/-</div>
                                <div class="">
                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-gradient-to-r {{ $attendancePercentage >= 90 ? 'from-green-200 to-green-500' : ($attendancePercentage >= 75 ? 'from-cyan-200 to-cyan-500' : 'from-red-200 to-red-500') }} h-2 rounded-full transition-all"
                                            style="width: {{ min($attendancePercentage, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endif



                    </a>
                @endforeach
            </div>
        </div>
    @endsection
    @section('script')
        <script type="module">
            $(document).ready(function() {
                $('#filter_date').on('change', function() {
                    let selected = $(this).val();
                    $('#date').val(selected);
                    $('#form_filter').submit();
                });
            });
        </script>
        <script type="text/javascript">
            function confirmClear(event) {
                event.preventDefault(); // prevent form submit
                var form = event.target; // storing the form

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        form.submit();
                    }
                })
            }
        </script>
    @endsection
