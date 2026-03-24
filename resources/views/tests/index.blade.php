@extends('layouts.app')
@section('page-content')
    <h1>Assessment</h1>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <div>Assessment</div>
    </div>

    <div class="grid grid-cols-2 md:w-4/5 gap-2 md:gap-4 mx-auto mt-6">
        <div class="statbox teal">
            <div class="flex justify-between items-center">
                <p class="text-xs md:text-sm">Open Tests</p>
                <div class="ico">
                    <i class="bi bi-unlock text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="font-semibold text-sm md:text-lg mt-1">{{ $tests->where('is_open', true)->count() }} <span
                    class="px-3 py-[2px] bg-teal-100 text-teal-600 rounded-full text-xs ml-1 md:ml-3"> <i
                        class="bx bx-trending-up"></i>+{{ $testsThisWeek->count() > 0 ? $testsThisWeek->count() : 0 }}</span>
            </div>
        </div>
        <div class="statbox primary">
            <div class="flex justify-between items-center">
                <p class="text-xs md:text-sm">Data Status</p>
                <div class="ico">
                    <i class="bi bi-graph-up text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="font-semibold text-sm md:text-lg mt-1">{{ $dataProgress }}% <span
                    class="bg-indigo-100 text-indigo-600 px-2 py-[2px] rounded-full text-xs ml-1 md:ml-3"> Overall </span>
            </div>
        </div>
    </div>


    {{-- <div class="grid md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 border rounded-lg"> --}}

    <div class="w-full md:w-4/5 mx-auto mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-teal-100 text-teal-600">
                <i class="ri-filter-3-line text-lg"></i>
            </div>
            <div>
                <h2 class="font-semibold text-gray-800 text-sm leading-tight">ASSESSMENT</h2>
                <p class="text-xs text-gray-400">Click on any test to see detail</p>
            </div>

        </div>

        <div class="px-6 py-5">

            <!-- page message -->
            @if ($errors->any())
                <x-message :errors='$errors'></x-message>
            @else
                <x-message></x-message>
            @endif

            {{-- new buttn --}}
            @can('create', App\Models\Test::class)
                <a href="{{ route('tests.create') }}"
                    class="fixed bottom-4 right-4 flex justify-center items-center bg-teal-400 hover:bg-teal-600 hover:cursor-pointer rounded-full w-12 h-12"><i
                        class="bi-plus-lg"></i></a>
            @endcan

            {{-- grid of showing tests: 2 test per row with title and status and link to details page --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($tests->sortByDesc('created_at') as $test)
                    <a href="{{ route('tests.show', $test) }}"
                        class="bg-white p-4 md:p-6 rounded border border-transparent shadow hover:border-teal-400 transition cursor-pointer ease-in-out duration-300">
                        <div class="flex items-center space-x-2">
                            <div class="ico indigo">
                                <i class="bi-file-earmark-text"></i>
                            </div>
                            <h3>{{ $test->title }}</h3>
                        </div>

                        {{-- calculate percentage of submitted tests --}}
                        @php
                            $sumbittedCount = $test->testSubjects()->mine()->resultSubmitted()->count();
                            $totalCount = $test->testSubjects()->mine()->count();
                            $percent = $totalCount > 0 ? round(($sumbittedCount / $totalCount) * 100, 0) : 0;
                        @endphp
                        <p class="text-sm text-gray-500 mt-1">Status: {{ $percent }}%
                            <span
                                class="px-2 py-[2px] bg-{{ $test->is_open ? 'green' : 'gray' }}-100 text-{{ $test->is_open ? 'green' : 'gray' }}-600 rounded-full text-xs">
                                {{ $test->is_open ? 'Open' : 'Closed' }}
                            </span>
                        </p>

                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function delme(formid) {

            event.preventDefault();

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
                    //submit corresponding form
                    $('#del_form' + formid).submit();
                }
            });
        }

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
    </script>
@endsection
