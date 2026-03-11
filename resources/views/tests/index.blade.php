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
                    class="px-3 py-[2px] bg-teal-100 text-teal-600 rounded-full text-xs ml-3"> <i
                        class="bx bx-trending-up"></i>+{{ $testsThisWeek->count() > 0 ? $testsThisWeek->count() : 0 }}</span>
            </div>
        </div>
        <div class="statbox primary">
            <div class="flex justify-between items-center">
                <p class="text-xs md:text-sm">Data Progress</p>
                <div class="ico">
                    <i class="bi bi-graph-up text-sm md:text-lg"></i>
                </div>
            </div>
            <div class="font-semibold text-sm md:text-lg mt-1">{{ $dataProgress }}% <span
                    class="bg-indigo-100 text-indigo-600 px-2 py-[2px] rounded-full text-xs ml-3"> Overall </span></div>
        </div>
    </div>


    <div class="grid md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border gap-3">
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

        <!-- search -->
        <div class="flex relative w-full md:w-1/3">
            <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                oninput="search(event)">
            <i class="bx bx-search absolute top-2 right-2"></i>
        </div>
        {{-- table --}}
        <div class="overflow-x-auto w-full mt-8">
            <table class="table-fixed borderless w-full border-collapse">
                <thead>
                    <tr class="">
                        <th class="w-12">Sr</th>
                        <th class="text-left w-48">Test</th>
                        <th class="w-24">Status</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($tests->sortByDesc('created_at') as $test)
                        @php
                            $sumbittedCount = $test->testAllocations()->mine()->resultSubmitted()->count();
                            $totalCount = $test->testAllocations()->mine()->count();
                            $percent = $totalCount > 0 ? round(($sumbittedCount / $totalCount) * 100, 0) : 0;
                        @endphp
                        <tr class="tr">
                            <td>{{ $loop->index + 1 }}</td>
                            <td class="text-left">
                                @if ($test->is_open)
                                    <a href="{{ route('tests.show', $test) }}" class="link">{{ $test->title }}</a>
                                    <br><span class="text-slate-500 text-xs">
                                        {{ $test->created_at->format('d/m/Y H:i') }}</span>
                                @else
                                    <a href="{{ route('tests.show', $test) }}">{{ $test->title }}</a>
                                    <br><span
                                        class="text-slate-500 text-xs">{{ $test->created_at->format('d/m/Y H:i') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($percent == 100)
                                    {{-- green rounded pill with 100% label --}}
                                    <div class="flex justify-center">

                                        <div class="bg-green-600 h-4 rounded-full text-xs text-white text-center w-16">
                                            {{ $percent }}%
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <div class="bg-green-600 h-4 rounded-full text-xs text-white text-center"
                                            style="width: {{ $percent }}%">
                                            {{ $percent }}%
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
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
