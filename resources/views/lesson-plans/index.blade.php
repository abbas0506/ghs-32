@extends('layouts.app')
@section('page-content')
    <h1>Lesson Plans</h1>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Dashoboard</a>
        <div>/</div>
        <a href="{{ route('lesson-plans.index') }}">Lesson Plans</a>
        <div>/</div>
        <div>Index</div>
    </div>


    <div class="md:w-4/5 mx-auto mt-8">

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <!-- Filter Section -->
        <div class="bg-white shadow-md rounded-xl p-6 mb-8 border border-gray-100">

            @if (!($grade && $subject))
                <form action="{{ route('lesson-plans.index') }}" method="GET" id="filterForm">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Grade Dropdown -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">
                                Select Grade
                            </label>

                            <select name="grade"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">

                                <option value="">Choose Grade</option>

                                @foreach ($grades as $g)
                                    <option value="{{ $g->id }}" {{ $grade == $g->id ? 'selected' : '' }}>
                                        {{ $g->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>


                        <!-- Subject Dropdown -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">
                                Select Subject
                            </label>

                            <select name="subject"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">

                                <option value="">Choose Subject</option>

                                @foreach ($subjects as $s)
                                    <option value="{{ $s->id }}" {{ $subject == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                    <hr class="my-5">
                    <div class="text-center">

                        <button type="submit" class="btn-indigo rounded-lg px-4 py-2 mt-3">Load Data</button>
                    </div>

                </form>
            @else
                <div class="">
                    {{ $grades->where('id', $grade)->first()->name }} -
                    {{ $subjects->where('id', $subject)->first()->name }}
                </div>
            @endif



        </div>


        <!-- Lesson Plan List -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100">

            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-700">
                    Available Lesson Plans
                </h2>
            </div>


            @if ($lessonPlans->count())
                <div class="divide-y">

                    @foreach ($lessonPlans as $plan)
                        <div class="p-6 hover:bg-gray-50 transition">

                            <div class="flex justify-between items-center">

                                <div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $plan->topic ?? 'N/A' }}
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        {{ $plan->objective }}
                                    </p>
                                </div>


                                <div class="flex gap-3">

                                    <a href="{{ route('lesson-plans.edit', $plan->id) }}"
                                        class="px-2 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">

                                        <i class="ri-pencil-line"></i>
                                    </a>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                <div class="p-10 text-center text-gray-500">

                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-12 w-12 text-gray-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2a4 4 0 118 0v2m-4-6V7m0 0V3m0 4h4m-4 0H7" />

                    </svg>

                    <p class="text-lg">
                        No lesson plans found
                    </p>
                    {{-- Create lesson plan button --}}
                    <p class="text-sm text-gray-400 mt-1">
                    <form action="{{ route('lesson-plans.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="grade_id" value="{{ $grade }}">
                        <input type="hidden" name="subject_id" value="{{ $subject }}">
                        <button type="submit"
                            class="px-4 py-2 text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200">
                            Create Lesson Plan
                        </button>
                    </form>
                    </p>

                </div>
            @endif

        </div>

    </div>

@endsection
@section('script')
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
