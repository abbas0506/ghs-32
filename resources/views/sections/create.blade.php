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

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="md:w-4/5 mx-auto rounded border p-5 md:p-8 mt-8 bg-slate-100">
            <form action="{{ route('sections.store') }}" method='post' class="mt-4" onsubmit="return validate(event)">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="col-span-full">
                        <label>Section</label>
                        <input type="text" name='name' class="custom-input-borderless" placeholder="Type here"
                            value="">
                    </div>
                    <div class="">
                        <label for="">Level</label>
                        <select name="level" id="" class="custom-input-borderless">
                            @foreach (range(1, 50) as $level)
                                <option value="{{ $level }}">{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-8">
                    <button type="submit" class="btn-teal rounded p-2">Create Now</button>
                </div>
            </form>

        </div>
    </div>
@endsection
