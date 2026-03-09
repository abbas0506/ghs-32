@extends('layouts.app')
@section('page-content')
    <h1>
        {{ $test->title }}</h1>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <a href="{{ route('tests.index') }}">Assessment</a>
        <div>/</div>
        <div>View</div>

    </div>

    <div class="grid md:grid-cols-2 md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border gap-3">
        <div class="flex items-center gap-3 flex-wrap">
            <h2>Result</h2>
            @if ($test->testAllocations()->mine()->count())
                <p>{{ $test->testAllocations()->mine()->resultSubmitted()->count() }}/{{ $test->testAllocations()->mine()->count() }}
                    ({{ round(($test->testAllocations()->mine()->resultSubmitted()->count() / $test->testAllocations()->mine()->count()) * 100, 0) }}%)
                </p>
                <p class="text-xs text-green-600"><i
                        class="bi-arrow-up"></i>{{ $test->testAllocations()->resultSubmitted()->today()->count() }}
                </p>
            @endif
        </div>

        <div class="flex items-center flex-wrap  justify-center md:justify-end">
            <div class="flex flex-wrap gap-2 items-center">

                @if ($test->is_open)
                    {{-- new allocation --}}
                    <a href="{{ route('test.test-allocations.create', $test) }}"
                        class="flex justify-center items-center w-8 h-8 btn-teal rounded-full text-xs"><i
                            class="bi-plus-lg text-blue-600 text-white"></i></a>
                    {{-- test edit button --}}
                    <a href="{{ route('tests.edit', $test) }}"
                        class="flex justify-center items-center w-8 h-8 btn-teal rounded-full text-xs">
                        <i class="bx bx-pencil text-slate-50"></i>
                    </a>
                    {{-- delete button --}}
                    @can('delete', $test)
                        <form action="{{ route('tests.destroy', $test) }}" method="POST" onsubmit="confirmDel(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="flex justify-center items-center w-8 h-8 btn-red rounded-full text-xs">
                                <i class="bi-trash3 text-white"></i>
                            </button>
                        </form>
                    @endcan
                    @can('lock', $test)
                        <form action="{{ route('test.lock', $test) }}" method='post'>
                            @csrf
                            @method('patch')
                            <button type="submit"
                                class="flex justify-center items-center w-8 h-8 btn-cyan rounded-full text-xs">
                                <i class="bi-unlock text-white font-bold"></i></button>
                        </form>
                    @endcan
                @else
                    @can('unlock', $test)
                        <form action="{{ route('test.unlock', $test) }}" method='post'>
                            @csrf
                            @method('patch')
                            <button type="submit"
                                class="flex justify-center items-center w-8 h-8 btn-red rounded-full text-xs"><i
                                    class="bi-lock text-white font-bold"></i></button>
                        </form>
                    @endcan
                @endif


            </div>
        </div>
    </div>
    <div class="md:w-4/5 mx-auto mt-6 bg-white">
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif
    </div>

    <div class="md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border overflow-auto">
        @if ($test->is_open)
            <!-- search -->
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex relative w-full md:w-1/3">
                    <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                        oninput="search(event)">
                    <i class="bx bx-search absolute top-2 right-2"></i>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-slate-600 hover:cursor-pointer" onclick="filterBy('all')"><i
                            class="bi-filter"></i></span>
                    <span class="text-green-600 hover:cursor-pointer" onclick="filterBy('submitted')"><i
                            class="bi-check"></i>
                    </span>
                    <span class="text-red-600 hover:cursor-pointer" onclick="filterBy('pending')"> <i
                            class="bi-question"></i></span>

                </div>
                <div>
                    {{-- calculate percentage of test allocations submited and  draw pie graph --}}
                    <?php
                    $submitted = $test->testAllocations()->mine()->resultSubmitted()->count();
                    $total = $test->testAllocations()->mine()->count();
                    $percent = $total > 0 ? round(($submitted / $total) * 100, 0) : 0;
                    $hue = $percent * 1.2; // convert 0-100 → 0-120
                    ?>
                    {{-- draw pie graph --}}
                    <div class="w-12 h-12 rounded-full bg-gray-200 relative">
                        <div class="absolute top-0 left-0 w-full h-full rounded-full clip-auto"
                            style="background: conic-gradient(#50b174 {{ $percent }}%, #e5e7eb {{ $percent }}%)">
                        </div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-xs">
                            {{ $percent }}%
                        </div>

                    </div>
                </div>
                <table class="table-fixed borderless w-full mt-8">
                    <thead>
                        <tr>
                            <th class="w-8">Sr</th>
                            <th class="text-left w-24">Subject</th>
                            <th class="w-12">Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($test->testAllocations()->mine()->get()->sortBy(['section_id', 'lecture_no']) as $testAllocation)
                            <tr class="tr">
                                <td>{{ $loop->index + 1 }} </td>
                                <td class="text-left">
                                    <a href="{{ route('test.test-allocations.show', [$test, $testAllocation]) }}"
                                        class="link">
                                        {{ $testAllocation->subject->short_name }} -
                                        {{ $testAllocation->section->name }}
                                    </a>
                                    <br>
                                    <span class="text-slate-500 text-xs">{{ $testAllocation->user?->profile->name }}</span>
                                </td>
                                <td>
                                    @if ($testAllocation->result_date)
                                        {{-- green rounded pill with submitted label --}}
                                        <span
                                            class="bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">Submitted</span>
                                    @else
                                        <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            @else
                {{-- test closed --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    @foreach ($sections as $section)
                        <div class="p-5 rounded bg-slate-100">
                            <h3>{{ $section->name }}</h3>
                            <div class="grid gap-[2px] mt-2 text-xs md:text-sm">
                                <a href="{{ route('section-result', [$test, $section]) }}" class="link"
                                    target="_blank">Section Result</a>
                                <a href="{{ route('section-positions', [$test, $section]) }}" class="link"
                                    target="_blank">Positions List</a>
                                <a href="{{ route('report-cards', [$test, $section]) }}" class="link"
                                    target="_blank">Report
                                    Cards</a>
                            </div>
                        </div>
                    @endforeach
                </div>
        @endif
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            var str = 0;
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(1).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
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
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            })
        }

        function filterBy(criteria) {
            if (criteria == 'all') {
                $('.tr').each(function() {
                    $(this).removeClass('hidden');
                });
            } else {
                // show submitted or pending as selected
                $('.tr').each(function() {
                    if ((
                            $(this).children().eq(3).hasClass(criteria)
                        )) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            }

        }
    </script>
@endsection
