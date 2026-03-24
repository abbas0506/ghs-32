@extends('layouts.app')

@section('page-content')
    <h2>Creat Test Allocation</h2>
    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('test.test-subjects.index', $test) }}">Test Allocations</a>
        <div>/</div>
        <div>Create New</div>
    </div>

    <div class="md:w-3/4 mx-auto mt-6 bg-white md:p-8 rounded">
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif


        <h2 class="text-lg text-teal-500">{{ $test->title }}</h2>
        <div class="divider my-2"></div>

        <form action="{{ route('test.test-subjects.store', $test) }}" method="POST">
            @csrf
            <input type="text" id='allocation_id' name='allocation_id' value="" hidden>
        </form>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach ($unallocated->sortBy('section.id') as $allocation)
                <div
                    class="allocation flex justify-between items-center border hover:border-teal-100 rounded-md p-5 shadow-md hover:bg-teal-50 transition-all duration-500 ease-in-out hover:cursor-pointer text-sm">
                    <div data-bound='{{ $allocation->id }}'>
                        <h3 class="font-bold">{{ $allocation->section->name }} Lecture # {{ $allocation->lecture_no }}
                        </h3>
                        <p>{{ $allocation->subject->name }} <span class="text-slate-400">by
                                {{ $allocation->user->profile->name }}</span></p>
                    </div>
                    <i class="bi-arrow-right text-slate-400"></i>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
            $('.allocation').click(function() {
                var allocationId = $(this).children().first().attr('data-bound');
                $('#allocation_id').val(allocationId);
                $('form').submit();
            })
        });
    </script>
@endsection
