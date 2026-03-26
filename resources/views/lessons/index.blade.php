@extends('layouts.app')
@section('page-content')

    {{-- Page Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Lesson Plan</h1>
            <div class="bread-crumb mt-1">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <span class="text-gray-500">Lesson Plan</span>
            </div>
        </div>
    </div>

    <div class="md:w-11/12 mx-auto mt-6 space-y-6">

        {{-- Flash Messages --}}
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Filter Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div
                class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-teal-100 text-teal-600">
                    <i class="ri-filter-3-line text-lg"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800 text-sm leading-tight">Filter Lessons</h2>
                    <p class="text-xs text-gray-400">Select grade and subject to view lessons</p>
                </div>
                @if ($grade && $subject)
                    <a href="{{ route('lessons.index') }}"
                        class="ml-auto inline-flex items-center gap-1 text-xs text-gray-500 hover:text-red-500 transition">
                        <i class="ri-close-line"></i> Clear filter
                    </a>
                @endif
            </div>

            <div class="px-6 py-5">
                @if ($grade && $subject)
                    {{-- Active filter pills --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 border border-teal-200 text-teal-700 rounded-full text-sm font-medium">
                            <i class="ri-school-line text-teal-500"></i>
                            {{ $grades->find($grade)?->name ?? 'N/A' }}
                        </span>
                        <i class="ri-arrow-right-line text-gray-300"></i>
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-full text-sm font-medium">
                            <i class="ri-book-2-line text-indigo-500"></i>
                            {{ $subjects->find($subject)?->name ?? 'N/A' }}
                        </span>
                        <a href="{{ route('lessons.index') }}"
                            class="ml-2 text-xs text-gray-400 underline hover:text-gray-600 transition">Change</a>
                    </div>
                @else
                    <form action="{{ route('lessons.index') }}" method="GET" id="filterForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Grade
                                </label>
                                <select name="grade"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition">
                                    <option value="">— Choose Grade —</option>
                                    @foreach ($grades as $g)
                                        <option value="{{ $g->id }}" {{ $grade == $g->id ? 'selected' : '' }}>
                                            {{ $g->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Subject
                                </label>
                                <select name="subject"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition">
                                    <option value="">— Choose Subject —</option>
                                    @foreach ($subjects as $s)
                                        <option value="{{ $s->id }}" {{ $subject == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-green-500 text-white text-sm font-semibold rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
                                <i class="ri-search-line"></i> Load Plan
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        {{-- Plans Table --}}
        @if ($grade && $subject)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div
                    class="flex flex-col md:flex-row items-start md:items-center gap-2 justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h2 class="font-semibold text-gray-800">Lesson Plan</h2>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $lessons->count() }} {{ Str::plural('plan', $lessons->count()) }} lessons found
                        </p>
                    </div>
                    @if ($lessons->count())
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search plans…" oninput="filterPlans(event)"
                                class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-teal-400 focus:border-teal-400 w-48 transition">
                            <i class="ri-search-line absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                        </div>
                    @endif
                </div>

                @if ($lessons->count())
                    <div class="overflow-auto">
                        <table class="table-fixed borderless w-full text-sm xs md:sm">
                            <thead class="">
                                <tr>
                                    <th class="w-16">#</th>
                                    <th class="w-48 text-left">Lesson</th>
                                    <th class="w-32 text-left">Assignment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lessons as $lesson)
                                    <tr class="tr">
                                        <td>
                                            <a href="{{ route('lessons.show', $lesson->id) }}" class="ico green mx-auto">
                                                {{ $lesson->lesson_no }}
                                            </a>
                                        </td>
                                        <td class="text-left">
                                            <h3 class="text-gray-600 font-semibold">{{ $lesson->title }}</h3>
                                            <ul>
                                                @foreach ($lesson->cues as $cue)
                                                    <li class="list-disc ml-4">
                                                        {{ $cue->content }}
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </td>
                                        <td class="text-left">
                                            {{ $lesson->homework }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Progress bar --}}
                    @php
                        $filled = $lessons
                            ->filter(fn($p) => $p->title && $p->title !== 'Topic ' . $p->lesson_no)
                            ->count();
                        $total = $lessons->count();
                        $pct = $total ? round(($filled / $total) * 100) : 0;
                    @endphp
                    <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1.5">
                            <span>Content filled</span>
                            <span class="font-semibold">{{ $filled }}/{{ $total }} plans
                                ({{ $pct }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-gradient-to-r from-teal-400 to-green-400 h-1.5 rounded-full transition-all duration-500"
                                style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @else
                    {{-- Empty state --}}
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <div class="w-20 h-20 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                            <i class="ri-file-list-3-line text-4xl text-gray-300"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-600 mb-1">No lesson plan found</h3>
                        <p class="text-sm text-gray-400 mb-6 max-w-xs">
                            No lesson plan has been created for this grade and subject yet.
                        </p>
                        @auth
                            <form action="{{ route('lessons.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="grade_id" value="{{ $grade }}">
                                <input type="hidden" name="subject_id" value="{{ $subject }}">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-green-500 text-white text-sm font-semibold rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
                                    <i class="ri-add-circle-line text-base"></i>
                                    Generate Lesson Plan
                                </button>
                            </form>
                        @endauth
                    </div>
                @endif
            </div>
        @endif

    </div>

@endsection

@section('script')
    <script>
        function delme(planId) {
            event.preventDefault();
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
                    document.getElementById('del_form' + planId).submit();
                }
            });
        }

        function filterPlans(event) {
            const query = event.target.value.toLowerCase();
            document.querySelectorAll('.plan-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.classList.toggle('hidden', !text.includes(query));
            });
        }
    </script>
@endsection
