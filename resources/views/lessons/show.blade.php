@extends('layouts.app')
@section('page-content')

    {{-- Page Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lesson Plan Detail</h1>
            <div class="bread-crumb mt-1">
                <a href="{{ url('/') }}">Dashboard</a>
                <div>/</div>
                <a href="{{ route('lessons.index') }}">Lesson Plan</a>
                <div>/</div>
                <a href="{{ route('lessons.index', ['grade' => $lesson->grade_id, 'subject' => $lesson->subject_id]) }}"
                    class="text-teal-600 hover:underline">
                    {{ $lesson->grade?->name }} – {{ $lesson->subject?->name }}
                </a>
                <div>/</div>
                <span class="text-gray-500">Day {{ $lesson->lesson_no }}</span>
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

        {{-- Context Banner --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4 flex items-center gap-4 flex-wrap">
            <div
                class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-400 to-green-400 text-white text-2xl font-bold shadow">
                {{ $lesson->lesson_no }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Day {{ $lesson->lesson_no }}</p>
                <p class="text-gray-800 font-bold text-lg leading-tight mt-0.5 truncate">
                    {{ $lesson->title ?? 'No topic set' }}
                </p>
                <p class="text-xs text-gray-400 mt-0.5">
                    <i class="ri-school-line text-teal-500 mr-1"></i>{{ $lesson->grade?->name }}
                    &nbsp;·&nbsp;
                    <i class="ri-book-2-line text-indigo-400 mr-1"></i>{{ $lesson->subject?->name }}
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('lessons.edit', $lesson->id) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-teal-500 to-green-500 rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
                    <i class="ri-pencil-line"></i> Edit
                </a>
                <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" id="deleteForm" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-red-500 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Detail Cards --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-50">

            {{-- Objective --}}
            <div class="px-6 py-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-teal-100 text-teal-600">
                        <i class="ri-focus-3-line text-sm"></i>
                    </span>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Learning Objective</h3>
                </div>
                @if ($lesson->objective)
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $lesson->objective }}</p>
                @else
                    <p class="text-sm text-gray-300 italic">Not specified</p>
                @endif
            </div>
            {{-- lesson cues --}}
            <div class="px-6 py-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600">
                        <i class="ri-cursor-line text-sm"></i>
                    </span>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Lesson Cues / Guidelines</h3>
                </div>
                @if ($lesson->cues)
                    @foreach ($lesson->cues as $cue)
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $cue->content }}</p>
                    @endforeach
                @else
                    <p class="text-sm text-gray-300 italic">Not specified</p>
                @endif
            </div>


            {{-- Activity --}}
            <div class="px-6 py-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-purple-100 text-purple-600">
                        <i class="ri-group-line text-sm"></i>
                    </span>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Classroom Activity</h3>
                </div>
                @if ($lesson->activity)
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $lesson->activity }}</p>
                @else
                    <p class="text-sm text-gray-300 italic">No activity defined</p>
                @endif
            </div>

            {{-- Homework --}}
            <div class="px-6 py-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-100 text-amber-600">
                        <i class="ri-home-4-line text-sm"></i>
                    </span>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Homework / Assignment</h3>
                </div>
                @if ($lesson->homework)
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $lesson->homework }}</p>
                @else
                    <p class="text-sm text-gray-300 italic">No homework assigned</p>
                @endif
            </div>

            {{-- Remarks --}}
            <div class="px-6 py-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-100 text-blue-500">
                        <i class="ri-sticky-note-line text-sm"></i>
                    </span>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Remarks / Notes</h3>
                </div>
                @if ($lesson->remarks)
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $lesson->remarks }}</p>
                @else
                    <p class="text-sm text-gray-300 italic">No remarks</p>
                @endif
            </div>

        </div>

        {{-- Resources Section --}}
        @if ($lesson->resources->count())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <i class="ri-attachment-line text-gray-400"></i>
                    <h3 class="font-semibold text-gray-700 text-sm">Resources ({{ $lesson->resources->count() }})</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach ($lesson->resources as $resource)
                        <div class="px-6 py-3 flex items-center gap-3">
                            @php
                                $icons = [
                                    'video' => ['ri-video-line', 'text-red-500', 'bg-red-50'],
                                    'document' => ['ri-file-text-line', 'text-blue-500', 'bg-blue-50'],
                                    'link' => ['ri-link', 'text-teal-500', 'bg-teal-50'],
                                ];
                                [$icon, $color, $bg] = $icons[$resource->resource_type] ?? [
                                    'ri-file-line',
                                    'text-gray-400',
                                    'bg-gray-50',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $bg }} {{ $color }}">
                                <i class="{{ $icon }} text-sm"></i>
                            </span>
                            <div class="flex-1 min-w-0">
                                <a href="{{ $resource->resource_url }}" target="_blank" rel="noopener noreferrer"
                                    class="text-sm font-medium text-teal-600 hover:underline truncate block">
                                    {{ $resource->description ?: $resource->resource_url }}
                                </a>
                                <p class="text-xs text-gray-400 capitalize">{{ $resource->resource_type }}</p>
                            </div>
                            <a href="{{ $resource->resource_url }}" target="_blank" rel="noopener noreferrer"
                                class="text-gray-300 hover:text-teal-500 transition">
                                <i class="ri-external-link-line"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Footer nav --}}
        <div class="flex items-center justify-between pb-4">
            <a href="{{ route('lessons.index', ['grade' => $lesson->grade_id, 'subject' => $lesson->subject_id]) }}"
                class="text-sm text-gray-400 hover:text-gray-600 transition inline-flex items-center gap-1">
                <i class="ri-arrow-left-line"></i> Back to list
            </a>
            <div class="flex items-center gap-3">
                @if ($prevPlan)
                    <a href="{{ route('lessons.show', $prevPlan->id) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                        <i class="ri-arrow-left-s-line"></i> Prev
                    </a>
                @endif
                @if ($nextPlan)
                    <a href="{{ route('lessons.show', $nextPlan->id) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                        Next <i class="ri-arrow-right-s-line"></i>
                    </a>
                @endif
            </div>
        </div>

    </div>

@endsection

@section('script')
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Delete this plan?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
@endsection
