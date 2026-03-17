@extends('layouts.app')
@section('page-content')
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">New Lesson Plan</h1>
        <div class="bread-crumb mt-1">
            <a href="{{ url('/') }}">Dashboard</a>
            <div>/</div>
            <a href="{{ route('lesson-plans.index') }}">Lesson Plans</a>
            <div>/</div>
            <span class="text-gray-500">Create</span>
        </div>
    </div>

    <div class="md:w-2/3 lg:w-1/2 mx-auto space-y-5">

        {{-- Flash Messages --}}
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Info card --}}
        <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4">
            <i class="ri-information-line text-blue-400 text-xl mt-0.5 shrink-0"></i>
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-0.5">How this works</p>
                <p>Selecting a grade and subject will automatically generate <strong>72 daily lesson plan</strong> entries.
                    You can then fill in the content for each day individually.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50 rounded-t-2xl">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="ri-book-open-line text-teal-500"></i>
                    Select Grade & Subject
                </h2>
            </div>

            <form action="{{ route('lesson-plans.store') }}" method="POST" class="px-6 py-6 space-y-5">
                @csrf

                {{-- Grade --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                        Grade <span class="text-red-400">*</span>
                    </label>
                    <select name="grade_id" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition @error('grade_id') border-red-400 @enderror">
                        <option value="">— Choose Grade —</option>
                        @foreach ($grades as $g)
                            <option value="{{ $g->id }}" {{ old('grade_id') == $g->id ? 'selected' : '' }}>
                                {{ $g->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('grade_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Subject --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                        Subject <span class="text-red-400">*</span>
                    </label>
                    <select name="subject_id" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition @error('subject_id') border-red-400 @enderror">
                        <option value="">— Choose Subject —</option>
                        @foreach ($subjects as $s)
                            <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <a href="{{ route('lesson-plans.index') }}"
                        class="text-sm text-gray-400 hover:text-gray-600 transition inline-flex items-center gap-1">
                        <i class="ri-arrow-left-line"></i> Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-green-500 text-white text-sm font-semibold rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
                        <i class="ri-add-circle-line"></i>
                        Generate 72-Day Plan
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
