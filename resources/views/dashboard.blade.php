@extends('layouts.app')
@section('page-content')
    <!--welcome  -->
    <div class="flex items-center">
        <div class="bread-crumb">
            <div>#</div>
            <div>/</div>
            <div><i class="bi-house"></i></div>
        </div>
    </div>
    <!-- pallets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
        <a href="{{ route('sections.index') }}" class="statbox green">
            <div class="flex-1">
                <div class="uppercase text-xs font-semibold">Classes</div>
                <div class="flex items-center">
                    <div class="h2">{{ $sections->count() }}</div>
                    <i class="bi-person text-sm ml-4"></i>
                    <p class="text-sm ml-1">{{ $students->count() }}</p>
                </div>
            </div>
            <div class="ico">
                <i class="bi bi-layers text-xl"></i>
            </div>
        </a>

        <a href="{{ route('attendance.summary') }}" class="statbox indigo">
            <div class="flex-1">
                <div class="uppercase text-xs font-semibold">Attendance
                    @if ($attendances->count())
                        <sup><i class="bi-circle-fill text-green-500 text-xxs"></i></sup>
                    @endif
                </div>
                <div class="h2">{{ $attendances->count() }} / {{ $students->count() }}</div>
            </div>
            <div class="ico">
                <i class="bi bi-person-check text-xl"></i>
            </div>
        </a>

        <a href="" class="statbox teal">
            <div class="flex-1">
                <div class="uppercase text-xs font-semibold">Assessment
                    @if ($tests->where('is_open', 1)->count())
                        <sup><i class="bi-circle-fill text-green-500 text-xxs"></i></sup>
                    @endif
                </div>
                <div class="h2">{{ $tests->count() }}</div>
            </div>
            <div class="ico">
                <i class="bi bi-clipboard-check text-xl"></i>
            </div>
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
