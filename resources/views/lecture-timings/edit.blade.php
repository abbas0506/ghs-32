@extends('layouts.app')
@section('page-content')
    {{-- Page Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Edit Lecture Timing</h1>
            <div class="bread-crumb mt-1">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <a href="{{ route('config') }}">Config</a>
                <div>/</div>
                <a href="{{ route('lecture-timings.index') }}">Lecture Timings</a>
                <div>/</div>
                <span class="text-gray-500">Lecture {{ $lectureTiming->lecture_no }}</span>
            </div>
        </div>
    </div>

    <div class="md:w-11/12 lg:w-3/4 xl:w-2/3 mx-auto space-y-5">

        {{-- Flash Messages --}}
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Context banner --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4 flex items-center gap-4 flex-wrap">
            <div
                class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-400 to-green-400 text-white text-xl font-bold shadow">
                {{ $lectureTiming->lecture_no }}
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Lecture Number</p>
                <p class="text-gray-700 font-semibold mt-0.5">
                    <span class="mr-3">
                        <i class="ri-time-line text-teal-500 mr-1"></i>Starts at: {{ $lectureTiming->starts_at->format('h:i A') }}
                    </span>
                    <span>
                        <i class="ri-timer-line text-indigo-400 mr-1"></i>Duration: {{ $lectureTiming->duration }} mins
                    </span>
                </p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('lecture-timings.update', $lectureTiming) }}"
                method="POST" class="divide-y divide-gray-50">
                @csrf
                @method('PUT')

                {{-- Lecture Number --}}
                <div class="px-6 py-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Lecture Number <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="lecture_no" value="{{ old('lecture_no', $lectureTiming->lecture_no) }}"
                        placeholder="Enter lecture number..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition @error('lecture_no') border-red-400 @enderror"
                        required>
                    @error('lecture_no')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Starts At --}}
                <div class="px-6 py-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Starts At <span class="text-red-400">*</span>
                    </label>
                    <input type="time" name="starts_at" value="{{ old('starts_at', $lectureTiming->starts_at->format('H:i')) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition @error('starts_at') border-red-400 @enderror"
                        required>
                    @error('starts_at')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Duration --}}
                <div class="px-6 py-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Duration (minutes) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="duration" value="{{ old('duration', $lectureTiming->duration) }}"
                        placeholder="Enter duration in minutes..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition @error('duration') border-red-400 @enderror"
                        required>
                    @error('duration')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="px-6 py-4 bg-gray-50/60 rounded-b-2xl flex flex-wrap-reverse items-center justify-between">
                    <a href="{{ route('lecture-timings.index') }}"
                        class="text-sm text-gray-400 hover:text-gray-600 transition inline-flex items-center gap-1">
                        <i class="ri-arrow-left-line"></i> Back to list
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-teal-500 to-green-500 text-white text-sm font-semibold rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
                            <i class="ri-save-line"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
