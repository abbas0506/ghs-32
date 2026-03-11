@extends('layouts.app')
@section('page-content')
    <h2>Edit Test</h2>
    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('tests.index') }}">Tests</a>
        <div>/</div>
        <div>Edit</div>
    </div>

    <div class="md:w-3/4 mx-auto mt-12 bg-white md:p-8 rounded">
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <label for="">{{ $test->title }}</label>
        <div class="border rounded-md mt-3 p-3 text-sm bg-gradient-to-r from-blue-100 to-blue-50 border-blue-200">
            <h3 class="font-bold">Class {{ $testAllocation->section->name }} / Lecture #
                {{ $testAllocation->lecture_no }}</h3>
            <p>{{ $testAllocation->subject->name }} <span class="text-slate-400">by
                    {{ $testAllocation->user->profile->name }}</span></p>
        </div>

        <form action="{{ route('test.test-allocations.update', [$test, $testAllocation]) }}" method='post'
            class="w-full grid gap-6 mt-6">
            @csrf
            @method('patch')

            <div>
                <select name="user_id" id="" class="custom-input-borderless">

                </select>

            </div>
            <div>
                <label>Max Marks</label>
                <input type="number" name='max_marks' class="custom-input" placeholder="Total marks"
                    value="{{ $testAllocation->max_marks }}" required>
            </div>
            <button type="submit" class="btn-teal rounded p-2 w-32 mt-3">Update Now</button>
        </form>
    </div>
@endsection
