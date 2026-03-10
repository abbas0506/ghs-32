@extends('layouts.app')
@section('page-content')
    <h1>Assessment</h1>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <div>Assessment</div>
    </div>

    <div class="grid md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border gap-3">
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- search -->
        <div class="flex relative w-full md:w-1/3">
            <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full" oninput="search(event)">
            <i class="bx bx-search absolute top-2 right-2"></i>
        </div>

        {{-- new buttn --}}
        @can('create', App\Models\Test::class)
            <a href="{{ route('tests.create') }}"
                class="fixed bottom-4 right-4 flex justify-center items-center bg-teal-400 hover:bg-teal-600 hover:cursor-pointer rounded-full w-12 h-12"><i
                    class="bi-plus-lg"></i></a>
        @endcan

        <table class="table-auto borderless w-full mt-8">
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
                                <br><span class="text-slate-500 text-xs">{{ $test->created_at->format('d/m/Y H:i') }}</span>
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
