@extends('layouts.app')
@section('page-content')
    <h2>{{ $testSubject->test->title }} Result</h2>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <a href="{{ route('tests.index') }}">Tests</a>
        <div>/</div>
        <a href="{{ route('test.test-subjects.index', [$testSubject->test, $testSubject]) }}">Subjects</a>
        <div>/</div>
        <div>{{ $testSubject->subject->short_name }}</div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border gap-3">
        <div class="">
            <div class="flex items-center space-x-2">
                <div class="ico green">
                    <i class="bx bx-book"></i>
                </div>
                <h2> {{ $testSubject->subject->name }} - {{ $testSubject->section->name }}
                </h2>
            </div>
            @if ($testSubject->result_date)
                <span class="mt-1 text-slate-400 text-xs md:text-sm">Result submission:
                    {{ $testSubject->result_date }}</span>
            @endif
        </div>
        <div class="flex w-full space-x-3 items-center justify-center md:justify-end">
            {{-- print button --}}
            <a href="{{ route('subject-result', $testSubject) }}" target="_blank"
                class="flex justify-center items-center w-8 h-8 btn-teal rounded-full text-xs text-white">
                <i class="bi-printer"></i>
            </a>
            @if ($testSubject->hasBeenSubmitted())
                @can('unlock', $testSubject)
                    <form action="{{ route('test-subject.unlock', $testSubject) }}" method="post">
                        @csrf
                        @method('patch')
                        <button type="submit"
                            class="flex justify-center items-center w-8 h-8 btn-red rounded-full text-sm text-white"><i
                                class="bi-lock"></i></button>
                    </form>
                @else
                    <button type="button" disabled
                        class="flex justify-center items-center w-8 h-8 btn-red rounded-full text-sm text-white"><i
                            class="bi-lock"></i></button>
                @endcan
            @else
                {{-- import button --}}
                <a href="{{ route('test-subject.import.index', $testSubject) }}"
                    class="flex justify-center items-center btn-green w-8 h-8 btn-teal rounded-full text-xs text-white"><i
                        class="bi-person-add"></i></a>

                @if ($testSubject->appearingStudents->count())
                    <a href="{{ route('test-subject.results.edit', [$testSubject, 0]) }}"
                        class="flex justify-center items-center w-8 h-8 btn-sky rounded-full text-sm text-white"><i
                            class="bx bx-pencil"></i></a>
                @endif

                @can('delete', $testSubject)
                    <form action="{{ route('test.test-subjects.destroy', [$test, $testSubject]) }}" method="POST"
                        onsubmit="confirmDel(event)" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex justify-center items-center w-8 h-8 btn-red rounded-full text-xs">
                            <i class="bi-trash3 text-white"></i>
                        </button>
                    </form>
                @endcan
            @endif
        </div>
    </div>

    <div class="md:w-4/5 mx-auto mt-6 bg-white md:p-8 p-4 rounded border gap-3">
        {{-- search --}}
        <div class="flex flex-wrap items-center justify-between gap-5">
            <div class="text-slate-500 text-sm font-semibold">
                <span><i class="ri-user-6-line bg-indigo-100 p-2 rounded-lg text-indigo-500"></i></span>
                <span class="font-normal text-xs">
                    {{ $testSubject->appearingStudents->count() }} Students
                </span>
                <span class="bg-green-100 p-2 rounded-lg text-green-500 ml-3">
                    {{ $testSubject->max_marks }}</span><span class="font-normal text-xs">Marks</span>
            </div>
            <div class="flex relative w-4/5 md:w-1/2">
                <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                    oninput="search(event)">
                <i class="bx bx-search absolute top-2 right-2"></i>
            </div>

        </div>


        <!-- error message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif


        <div class="overflow-x-auto w-full mt-8">
            <table class="table-auto borderless w-full">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th class="w-40 text-left">Name</th>
                        <th class="w-12">Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($testSubject->results->sortBy('student.rollno') as $result)
                        <tr class="tr">
                            <td>
                                <div class="ico emerald mx-auto">
                                    {{ $result->student->rollno }}</div>
                            </td>
                            <td class="text-left">{{ $result->student->name }}<br><span
                                    class="text-xs text-slate-400">{{ $result->student->father_name }}</span></td>
                            <td>{{ $result->obtained_marks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- dont show final submission if no student -->
        @if (!$testSubject->hasBeenSubmitted() && $testSubject->appearingStudents->count())
            <div class="md:w-2/3 mx-auto mt-8 text-center">
                <h3 class="text-red-600">Please note!</h3>
                <p>Once you have finished result, please make final submission as a last & necessary step. Remember, after
                    final, the result will be locked </p>
                <form action="{{ route('test-subject.lock', $testSubject) }}" method="post" class="mt-6 text-center">
                    @csrf
                    @method('patch')
                    <button type="submit" class="btn-red rounded p-2 px-5 text-sm">Make Final Submission</button>
                </form>
            </div>
        @endif
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
        </script>
    @endsection
