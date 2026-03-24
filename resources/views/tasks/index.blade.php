@extends('layouts.app')
@section('page-content')
    <h1>Tasks</h1>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <div>Tasks</div>
    </div>


    <div class="content-section">
        <div class="flex items-center flex-wrap justify-between">
            <!-- search -->
            <div class="flex relative w-full md:w-1/3">
                <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                    oninput="search(event)">
                <i class="bx bx-search absolute top-2 right-2"></i>
            </div>
            <a href="{{ route('tasks.create') }}"
                class="fixed bottom-4 right-4 flex justify-center items-center bg-teal-400 hover:bg-teal-600 hover:cursor-pointer rounded-full w-12 h-12"><i
                    class="bi-plus-lg"></i></a>
        </div>

        <div class="md:w-4/5 mx-auto overflow-auto bg-white md:p-8 p-4 rounded border mt-12">
            <!-- page message -->
            @if ($errors->any())
                <x-message :errors='$errors'></x-message>
            @else
                <x-message></x-message>
            @endif

            <table class="table-auto borderless w-full mt-8">
                <thead>
                    <tr class="">
                        <th class="w-6">Sr</th>
                        <th class="text-left w-48">Task Desc</th>
                        <th class="w-24">Status</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($tasks as $task)
                        @php
                            $percent = round(
                                ($task->taskLines()->where('status', 1)->count() / $task->taskLines()->count()) * 100,
                                0,
                            );
                        @endphp
                        <tr class="tr">
                            <td>
                                <div class="ico cyan mx-auto">
                                    {{ $loop->index + 1 }}
                                </div>
                            </td>
                            <td class="text-left">
                                @if ($task->isOpen())
                                    <a href="{{ route('tasks.show', $task) }}" class="link">{{ $task->description }}</a>
                                    <br>
                                    <span class="text-xs text-slate-400">Due date:
                                        {{ $task->due_date->format('d-m-Y') }}</span>
                                @else
                                    <a href="{{ route('tasks.show', $task) }}">{{ $task->description }}</a>
                                    <br>
                                    <span class="text-xs text-slate-400">Due date:
                                        {{ $task->due_date->format('d-m-Y') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="bg-green-600 h-4 rounded-full text-xs text-white text-center"
                                        style="width: {{ $percent }}%">
                                        {{ $percent }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
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
    </script>
@endsection
