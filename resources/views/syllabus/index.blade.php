@extends('layouts.app')
@section('page-content')

    {{-- Page Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Syllabus</h1>
            <div class="bread-crumb mt-1">
                <a href="{{ url('/') }}">Dashboard</a>
                <div>/</div>
                <span class="text-gray-500">Syllabus</span>
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
                    <h2 class="font-semibold text-gray-800 text-sm leading-tight">Filter syllabus</h2>
                    <p class="text-xs text-gray-400">Select grade to view syllabi</p>
                </div>
                @if ($grade)
                    <a href="{{ route('syllabi.index') }}"
                        class="ml-auto inline-flex items-center gap-1 text-xs text-gray-500 hover:text-red-500 transition">
                        <i class="ri-close-line"></i> Clear filter
                    </a>
                @endif
            </div>

            <div class="px-6 py-5">
                @if ($grade)
                    {{-- Active filter pills --}}
                    <div class="flex items-center gap-3 flex-wrap">
                        <span
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 border border-teal-200 text-teal-700 rounded-full text-sm font-medium">
                            <i class="ri-school-line text-teal-500"></i>
                            {{ $grades->find($grade)?->name ?? 'N/A' }}
                        </span>
                        <a href="{{ route('syllabi.index') }}"
                            class="ml-2 text-xs text-gray-400 underline hover:text-gray-600 transition">Change</a>
                    </div>
                @else
                    <form action="{{ route('syllabi.index') }}" method="GET" id="filterForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Grade
                                </label>
                                <select name="grade_id"
                                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 bg-gray-50 transition">
                                    <option value="">— Choose Grade —</option>
                                    @foreach ($grades as $g)
                                        <option value="{{ $g->id }}" {{ $grade == $g->id ? 'selected' : '' }}>
                                            {{ $g->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-green-500 text-white text-sm font-semibold rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
                                <i class="ri-search-line"></i> Load Syllabus
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        {{-- Plans Table --}}
        @if ($grade)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div
                    class="flex flex-col md:flex-row items-start md:items-center gap-2 justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h2 class="font-semibold text-gray-800">Syllabus</h2>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $syllabi->count() }} subjects found
                        </p>
                    </div>
                    @if ($syllabi->count())
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search plans…" oninput="search(event)"
                                class="pl-8 pr-3 py-2 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:ring-2 focus:ring-teal-400 focus:border-teal-400 w-48 transition">
                            <i class="ri-search-line absolute left-2.5 top-2.5 text-gray-400 text-sm"></i>
                        </div>
                    @endif
                </div>

                @if ($syllabi->count())
                    <div class="overflow-auto">
                        <table class="table-fixed borderless w-full text-sm xs md:sm">
                            <thead class="">
                                <tr>
                                    <th class="w-16">#</th>
                                    <th class="w-16 text-left">Subject</th>
                                    <th class="w-32 text-left">Term 1</th>
                                    <th class="w-32 text-left">Term 2</th>
                                    <th class="w-32 text-left">Term 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($syllabi as $syllabus)
                                    <tr class="tr">
                                        <td>
                                            <div class="ico teal mx-auto">{{ $loop->index + 1 }}</div>
                                        </td>
                                        <td class="text-left">
                                            <a href="{{ route('syllabi.edit', $syllabus) }}"
                                                class="link">{{ $syllabus->subject->short_name }}</a>
                                        </td>
                                        <td class="text-left">{{ $syllabus->term1 }}</td>
                                        <td class="text-left">{{ $syllabus->term2 }}</td>
                                        <td class="text-left">{{ $syllabus->term3 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Empty state --}}
                    <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                        <div class="w-20 h-20 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                            <i class="ri-file-list-3-line text-4xl text-gray-300"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-600 mb-1">No Syllabus found</h3>
                        <p class="text-sm text-gray-400 mb-6 max-w-xs">
                            No Syllabus has been created for this grade and subject yet.
                        </p>
                        @auth
                            <form action="{{ route('syllabi.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="grade_id" value="{{ $grade->id }}">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-green-500 text-white text-sm font-semibold rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
                                    <i class="ri-add-circle-line text-base"></i>
                                    Generate Syllabus
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
        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            var str = 0;
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(0).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(1).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }

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
