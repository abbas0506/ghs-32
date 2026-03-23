@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h2>New Class</h2>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <a href="{{ route('sections.index') }}">Sections</a>
            <div>/</div>
            <div>New</div>
        </div>

        <div class="md:w-4/5 mx-auto rounded border p-5 md:p-8 mt-8 bg-white">

            <!-- page message -->
            @if ($errors->any())
                <x-message :errors='$errors'></x-message>
            @else
                <x-message></x-message>
            @endif

            <form action="{{ route('sections.store') }}" method='post' class="mt-4" onsubmit="return validate(event)">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="">
                        <label for="">Grade *</label>
                        <select name="grade_id" id="" class="custom-input">
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-full">
                        <label>Section Name *</label>
                        <input type="text" name='name' class="custom-input" placeholder="Enter section name"
                            value="">
                    </div>
                </div>
                <div class="flex justify-end mt-8">
                    <button type="submit" class="btn-teal rounded p-2">Create Now</button>
                </div>
            </form>

        </div>
    </div>
@endsection
