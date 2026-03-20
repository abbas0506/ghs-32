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

    <?php
    $submitted = $test->testAllocations()->mine()->resultSubmitted()->count();
    $total = $test->testAllocations()->mine()->count();
    $percent = $total > 0 ? round(($submitted / $total) * 100, 0) : 0;
    $hue = $percent * 1.2; // convert 0-100 → 0-120
    ?>

    <div class="grid md:grid-cols-1 md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border gap-3">
        <div class="flex items-center justify-between">
            <div class="leading-none">
                <div class="flex items-center space-x-2">
                    <div class="ico green"><i class="ri-upload-2-line"></i></div>
                    <h2 class="uppercase text-sm md:text-lg font-bold text-gray-800">
                        Results
                        <span class="ml-2 text-teal-600 text-xs md:text-sm font-normal"><i class="bi-arrow-up"></i>
                            {{ $test->testAllocations()->resultSubmitted()->today()->count() }}

                        </span>
                    </h2>
                </div>

                {{-- set line spacing to 1 --}}
                <span class="text-gray-500 text-xs md:text-sm mt-1">{{ $submitted }} out of
                    {{ $total }} subjects
                    submitted</span>
            </div>

            <div class="flex items-center space-x-3">
                {{-- calculate percentage of test allocations submited and  draw pie graph --}}

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

        </div>
        <hr class="w-1/3 mx-auto">
        <div class="flex items-center flex-wrap justify-center">
            <div class="flex flex-wrap gap-2 items-center">

                {{-- new allocation --}}
                @role('admin|head')
                    @if ($test->is_open)
                        <a href="{{ route('test.test-allocations.create', $test) }}"
                            class="flex justify-center items-center w-8 h-8 btn-teal rounded-full text-xs"><i
                                class="bi-plus-lg text-blue-600"></i></a>
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
                @endrole

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

    <div class="md:w-4/5 mx-auto mt-6 bg-white overflow-auto">
        @if ($test->is_open)
            <div class="flex flex-1 flex-col md:flex-row items-center gap-3 md:justify-between">
                {{-- tabs --}}
                <div class="flex items-center space-x-3">
                    <span class="text-slate-600 hover:cursor-pointer" onclick="filterBy('all')"><i
                            class="bi-filter"></i></span>
                    <span class="bg-green-50 text-green-600 hover:cursor-pointer px-2 py-[1px] text-xs rounded-full "
                        onclick="filterBy('submitted')"><i class="bi-check"></i> Submitted
                    </span>
                    <span class="bg-red-50 text-red-600 hover:cursor-pointer px-2 py-[1px] text-xs rounded-full"
                        onclick="filterBy('pending')"> <i class="bi-question"></i> Pending
                    </span>

                </div>
                <!-- search -->
                <div class="flex relative w-full md:w-1/3">
                    <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                        oninput="search(event)">
                    <i class="bx bx-search absolute top-2 right-2"></i>
                </div>

            </div>



            <table class="table-auto borderless w-full mt-8">
                <thead>
                    <tr>
                        <th class="w-8">Sr</th>
                        <th class="text-left w-32">Subject</th>
                        <th class="w-20">Status</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($test->testAllocations()->mine()->get()->sortBy(['section_id', 'lecture_no']) as $testAllocation)
                        <tr class="tr {{ $testAllocation->result_date ? 'submitted' : 'pending' }}">
                            <td>
                                <div class="ico gray mx-auto">{{ $loop->index + 1 }} </div>
                            </td>
                            <td class="text-left">
                                <a href="{{ route('test.test-allocations.show', [$test, $testAllocation]) }}"
                                    class="link">
                                    {{ $testAllocation->subject->short_name }} -
                                    {{ $testAllocation->section->name }}
                                </a>
                                <br>
                                <span
                                    class="text-slate-500 text-xs">{{ $testAllocation->user?->profile->short_name }}</span>
                            </td>
                            <td>
                                @if ($testAllocation->result_date)
                                    {{-- green rounded pill with submitted label --}}
                                    <span
                                        class="bg-green-100 text-green-600 text-xs px-2 py-[1px] rounded-full">Submitted</span>
                                @else
                                    <span class="bg-red-100 text-red-600 text-xs px-2 py-[1px] rounded-full">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @else
            {{-- test closed --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ($sections as $section)
                    <div class="p-5 rounded statbox green">
                        <div class="flex items-center justify-between">
                            <h3>
                                <i
                                    class="ri-user-community-line bg-green-100 p-2 rounded-lg text-lg text-green-500 mr-2"></i>{{ $section->name }}
                            </h3>
                            <i class="bi-printer"></i>
                        </div>

                        <hr class="my-4">
                        <div class="grid gap-2 text-xs md:text-sm">
                            <div class="flex items-center justify-between">
                                <p class="text-slate-600">Overall Class Result</p>
                                <a href="{{ route('section-result', [$test, $section]) }}" target="_blank">
                                    <i class="bi bi-file-earmark-pdf text-red-600 mr-2"></i> </a>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-slate-600">Overall Class Position List</p>
                                <a href="{{ route('section-positions', [$test, $section]) }}" target="_blank">
                                    <i class="bi bi-file-earmark-pdf text-red-600 mr-2"></i> </a>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-slate-600">Result Cards</p>
                                <a href="{{ route('report-cards', [$test, $section]) }}" target="_blank">
                                    <i class="bi bi-file-earmark-pdf text-red-600 mr-2"></i> </a>
                            </div>
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
                            $(this).hasClass(criteria)
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
