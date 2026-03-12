@extends('layouts.app')
@section('page-content')
    <!--welcome  -->
    <div class="flex items-center">
        <div class="bread-crumb">
            <p class="font-semibold">Welcome back {{ Auth::user()->profile->short_name }}!</p>
        </div>
    </div>
    <!-- pallets -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-4 mt-5 md:mt-8 text-xs md:text-sm">
        <a href="{{ route('sections.index') }}" class="statbox green">
            <div class="flex justify-between items-center flex-1">
                <div class="">Students</div>
                <div class="ico">
                    <i class="bi bi-people text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="flex items-center mt-[1px] text-lg font-semibold">{{ $students->count() }}
                <span class="font-normal text-xs ml-2"><i class="bx bx-trending-up"></i>
                    +{{ $newAdmissions->count() }}</span>
            </div>
        </a>

        <a href="{{ route('attendance.summary') }}" class="statbox indigo">
            <div class="flex justify-between items-center">
                <div class="">Attendance</div>
                <div class="ico">
                    <i class="bi bi-person-check text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="font-semibold text-lg mt-[1px]">{{ round(($attendances->count() / $students->count()) * 100, 1) }}%
                @if ($attendanceChange > 0)
                    <span class="text-green-500 font-normal text-xs ml-2"><i class="bx bx-trending-up"></i>
                        {{ $attendanceChange }}%</span>
                @else
                    <span class="text-red-500 font-normal text-xs ml-2"><i class="bx bx-trending-down"></i>
                        {{ $attendanceChange }}%</span>
                @endif

                {{-- round green badge  --}}
                <span class="bg-indigo-200 text-indigo-800 font-normal text-[10px] rounded-full px-2 py-[1px]">Last
                    week</span>
            </div>
        </a>

        <a href="{{ route('tests.index') }}" class="statbox teal">
            <div class="flex justify-between items-center">
                <div>Assessment</div>
                <div class="ico">
                    <i class="bi bi-clipboard-check text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="font-semibold text-lg mt-[1px]">{{ $tests->count() }}</div>
        </a>
        <a href="{{ route('tasks.index') }}" class="statbox orange">
            <div class="flex justify-between items-center flex-1">
                <div class="">My Tasks</div>
                <div class="ico">
                    <i class="bi bi-calendar-event text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="mt-[1px] text-lg font-semibold">{{ $pendingAssignments->count() }} <span
                    class="bg-orange-100 text-orange-600 font-normal text-[10px] rounded-full px-2 py-[1px]"> <i
                        class="bi-clock"></i> +{{ $tasksDue->count() }} this
                    week</span></div>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 mt-8 md:gap-x-6 gap-y-4">
        <!-- middle panel  -->
        <div class="col-span-2 bg-slate-50">
            <h2 class="bg-blue-50 py-1 px-2 rounded-t-lg"><i class="bi-list-task mr-2"></i>My Tasks</h2>
            <div class="py-2 px-5">
                <table class="table-auto borderless w-full">
                    <thead>
                        <tr>
                            <th class="text-left"></th>
                            <th class="w-6"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
@endsection
