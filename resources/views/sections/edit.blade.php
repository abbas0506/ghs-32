@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h2>Classes / Edit</h2>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <a href="{{ route('sections.index') }}">Sections</a>
            <div>/</div>
            <div>Edit</div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="w-full md:w-3/4 mx-auto mt-8">
            <h1 class="text-slate-600 mt-8">Edit Class</h1>
            <form action="{{ route('sections.update', $section) }}" method='post' class="mt-4"
                onsubmit="return validate(event)">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-2 gap-2">
                    <div class="col-span-full">
                        <label>Section Name *</label>
                        <input type="text" name="name" class="custom-input" value="{{ $section->name }}">
                    </div>
                    <div class="">
                        <label for="">Grade *</label>
                        <select name="grade_id" id="" class="custom-input">
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}"
                                    {{ $section->grade_id == $grade->id ? 'selected' : '' }}> {{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex mt-4">
                    <button type="submit" class="btn-teal rounded p-2">Update Now</button>
                </div>
            </form>

        </div>
    </div>
@endsection
