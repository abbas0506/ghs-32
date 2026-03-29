@extends('layouts.app')
@section('page-content')
    {{-- Page Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Edit Lesson Plan</h1>
            <div class="bread-crumb mt-1">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <a href="{{ route('lessons.index') }}">Lesson Plan</a>
                <div>/</div>
                <a href="{{ route('lessons.index', ['grade' => $lesson->grade_id, 'subject' => $lesson->subject_id]) }}"
                    class="text-teal-600 hover:underline">
                    {{ $lesson->grade?->name }} – {{ $lesson->subject?->name }}
                </a>
                <div>/</div>
                <span class="text-gray-500">Lesson {{ $lesson->lesson_no }}</span>
            </div>
        </div>

        {{-- Day navigation --}}
        <div class="flex items-center gap-2">
            @if ($prevPlan)
                <a href="{{ route('grade.subject.lessons.edit', [$lesson->grade, $lesson->subject, $prevPlan->id]) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition shadow-sm">
                    <i class="ri-arrow-left-s-line"></i> Lesson {{ $prevPlan->lesson_no }}
                </a>
            @endif
            @if ($nextPlan)
                <a href="{{ route('grade.subject.lessons.edit', [$lesson->grade, $lesson->subject, $nextPlan->id]) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition shadow-sm">
                    Lesson {{ $nextPlan->lesson_no }} <i class="ri-arrow-right-s-line"></i>
                </a>
            @endif
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
                {{ $lesson->lesson_no }}
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Lesson Number</p>
                <p class="text-gray-700 font-semibold mt-0.5">
                    <span class="mr-3">
                        <i class="ri-school-line text-teal-500 mr-1"></i>{{ $lesson->grade?->name }}
                    </span>
                    <span>
                        <i class="ri-book-2-line text-indigo-400 mr-1"></i>{{ $lesson->subject?->name }}
                    </span>
                </p>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('grade.subject.lessons.show', [$lesson->grade, $lesson->subject, $lesson]) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    <i class="ri-eye-line"></i> Preview
                </a>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('grade.subject.lessons.update', [$lesson->grade, $lesson->subject, $lesson]) }}"
                method="POST" class="divide-y divide-gray-50">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div class="px-6 py-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Title <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $lesson->title) }}"
                        placeholder="Enter lesson title…"
                        class="w-full border md:border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition @error('title') border-red-400 @enderror"
                        required>
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Objective --}}
                <div class="px-6 py-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Learning Objective
                    </label>
                    <textarea name="objective" rows="3" placeholder="What will students learn from this lesson?"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition resize-none">{{ old('objective', $lesson->objective) }}</textarea>
                </div>

                {{-- Lesson Cues --}}
                <div class="px-6 py-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Lesson Cues / Guidelines
                    </label>
                    @php
                        $remaining = 3; // default to 3 cues if none exist
                    @endphp
                    @if ($lesson->cues->isNotEmpty())
                        {{-- show 3 cues exactly, if existing < 3, show remaining as blank textareas --}}
                        @php
                            $cueCount = $lesson->cues->count();
                            $remaining = 3 - $cueCount;
                        @endphp
                        @foreach ($lesson->cues as $cue)
                            <textarea name="cues[]" rows="3" placeholder="Enter lesson cues…"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition resize-none mb-3">{{ $cue->content }}</textarea>
                        @endforeach
                    @endif
                    @for ($i = 0; $i < $remaining; $i++)
                        <textarea name="cues[]" rows="3" placeholder="Describe lesson cue…"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition resize-none mb-3"></textarea>
                    @endfor

                </div>

                {{-- Activity --}}
                <div class="px-6 py-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Classroom Activity
                    </label>
                    <textarea name="activity" rows="3" placeholder="Describe classroom activities, exercises or demonstrations…"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition resize-none">{{ old('activity', $lesson->activity) }}</textarea>
                </div>

                {{-- Homework --}}
                <div class="px-6 py-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Homework / Assignment
                    </label>
                    <textarea name="homework" rows="2" placeholder="Describe any homework or take-home assignments…"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition resize-none">{{ old('homework', $lesson->homework) }}</textarea>
                </div>

                {{-- Remarks --}}
                <div class="px-6 py-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
                        Remarks / Notes
                    </label>
                    <textarea name="remarks" rows="2" placeholder="Any additional notes or observations…"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition resize-none">{{ old('remarks', $lesson->remarks) }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="px-6 py-4 bg-gray-50/60 rounded-b-2xl flex flex-wrap-reverse items-center justify-between">
                    <a href="{{ route('lessons.index', ['grade_id' => $lesson->grade_id]) }}"
                        class="text-sm text-gray-400 hover:text-gray-600 transition inline-flex items-center gap-1">
                        <i class="ri-arrow-left-line"></i> Back to list
                    </a>
                    <div class="flex items-center gap-3">
                        @if ($nextPlan)
                            <button type="submit" name="_save_and_next" value="1"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-teal-700 bg-teal-50 border border-teal-200 rounded-xl hover:bg-teal-100 transition">
                                Save & Next <i class="ri-arrow-right-line"></i>
                            </button>
                        @endif
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
