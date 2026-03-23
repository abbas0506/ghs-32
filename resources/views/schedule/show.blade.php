@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h1>My Schedule</h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <div>Schedule</div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="md:w-4/5 mx-auto overflow-auto bg-white md:p-8 p-4 rounded border mt-12">
            <h2 class="text-green-600">Total Allocations: {{ $schedules->count() }}</h2>
            <table class="table-auto borderless w-full mt-3">
                <thead>
                    <tr>
                        <th class="w-48 text-left">Subject</th>
                        <th class="w-6">Class</th>
                        <th class="w-6">Period</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules->sortBy('lecture_no') as $schedule)
                        <tr class="tr">
                            <td class="text-left">{{ $schedule->subject?->name }}</td>
                            <td>{{ $schedule->section->name }}</td>
                            <td>{{ $schedule->lecture_no }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
