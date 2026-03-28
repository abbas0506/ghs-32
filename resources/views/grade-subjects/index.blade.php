@extends('layouts.app')
@section('page-content')
    {{-- Page Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Grade Subjects</h1>
            <div class="bread-crumb mt-1">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <span class="text-gray-500">Grade Subjects</span>
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
                    <h2 class="font-semibold text-gray-800 text-sm leading-tight">Filter Grade</h2>
                    <p class="text-xs text-gray-400">Select grade to view subjects</p>
                </div>
                @if ($grade)
                    <a href="{{ route('grade-subjects.index') }}"
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
                        <a href="{{ route('grade-subjects.index') }}"
                            class="ml-2 text-xs text-gray-400 underline hover:text-gray-600 transition">Change</a>
                    </div>
                @else
                    <form action="{{ route('grade-subjects.index') }}" method="GET" id="filterForm">
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
                                <i class="ri-search-line"></i> Load Subjects
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        {{-- if grade selected, show subjects --}}
        @if ($grade)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <div>
                        <h2 class="font-semibold text-gray-800">Subjects</h2>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $grade->subjects->count() }} subjects found
                        </p>
                    </div>
                </div>
                <table class="table-fixed borderless w-full text-sm xs md:sm">
                    <thead class="">
                        <tr>
                            <th class="w-16">#</th>
                            <th class="w-40 text-left">Subject</th>
                            <th class="w-16"></th>
                        </tr>
                    </thead>
                    {{-- already selected subjects --}}
                    <tbody>
                        @foreach ($gradeSubjects as $gradeSubject)
                            <tr class="tr">
                                <td>
                                    <div class="ico teal mx-auto">{{ $loop->index + 1 }}</div>
                                </td>
                                <td class="text-left">{{ $gradeSubject->subject?->name }}</td>
                                <td>
                                    {{-- delete this subject  from grade-subjects --}}
                                    <form action="{{ route('grade-subjects.destroy', $gradeSubject) }}" method="post"
                                        class="flex justify-center items-center" onsubmit="return confirmDel(event)">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="pill red"><i
                                                class="bi-trash text-red-500"></i></button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>

                <div class="overflow-auto">
                    <div
                        class="flex flex-col md:flex-row items-start md:items-center gap-2 justify-between px-6 py-4 border-b border-gray-100">
                        <div>
                            <h2 class="font-semibold text-gray-800">+ Add More</h2>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $remainingSubjects->count() }} subjects available
                            </p>
                        </div>
                    </div>


                </div>
                <div>
                    {{-- remaining subjects --}}
                    <form action="{{ route('grade-subjects.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="grade_id" value="{{ $grade->id }}">
                        <table class="table-fixed borderless w-full text-sm xs md:sm">
                            <thead class="">
                                <tr>
                                    <th class="w-16">#</th>
                                    <th class="w-40 text-left">Subject</th>
                                    <th class="w-16"><input type="checkbox" id='chkAll' class="rounded"
                                            onclick="checkAll()"><br><label for="">Check all</label></th>
                                    </th>

                                </tr>
                            </thead>


                            @if ($remainingSubjects->count())
                                <tbody>

                                    @foreach ($remainingSubjects as $subject)
                                        <tr class="tr">
                                            <td>
                                                <div class="ico teal mx-auto">{{ $loop->index + 1 }}</div>
                                            </td>
                                            <td class="text-left">{{ $subject->name }}</td>
                                            <td>
                                                <input type="checkbox" class="w-4 h-4 rounded" name="subject_ids_array[]"
                                                    value="{{ $subject->id }}">
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                        <div class="flex justify-center my-5">

                            <button type="submit" class="btn-green rounded px-3 py-1">+ Add More</button>
                        </div>
                    </form>
                </div>

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

        function checkAll() {

            $('.tr').each(function() {
                if (!$(this).hasClass('hidden'))
                    $(this).children().find('input[type=checkbox]').prop('checked', $('#chkAll').is(':checked'));
                // updateChkCount()
            });
        }

        function confirmDel(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    //submit corresponding form
                    form.submit();
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
