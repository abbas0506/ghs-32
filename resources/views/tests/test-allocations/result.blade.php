@extends('layouts.app')
@section('page-content')
    <h2>Class {{ $testAllocation->section->name }}</h2>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <a href="{{ route('tests.index') }}">Tests</a>
        <div>/</div>
        <a href="{{ route('test.test-allocations.index', $testAllocation->test) }}">Subjects</a>
        <div>/</div>
        <a href="{{ route('test-allocation.results.index', $testAllocation) }}">Result</a>
        <div>/</div>
        <div>Edit</div>
    </div>

    <div class="w-full md:w-4/5 mx-auto bg-white mt-8">
        <div class="statbox blue mt-3">
            <i class="bi bi-info-circle ico sky"></i>
            <p class="flex text-sm text-slate-500 mt-2">
                If some student is missing, go back and import the student from
                his respective class
            </p>
        </div>

    </div>
    <div class="w-full md:w-4/5 mx-auto border rounded-lg p-5 md:p-8 bg-white mt-4">

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <form action="{{ route('test-allocation.results.update', [$testAllocation, 1]) }}" method="post"
            onsubmit="return validate(event)">
            @csrf
            @method('patch')
            <div class="flex flex-wrap items-center gap-3 mt-8">
                <div class="flex items-center flex-wrap gap-3">
                    <div class="pill green"> <i class="ri-user-line mr-2"></i>{{ $testAllocation->section->name }}</div>
                    <i class="bi-arrow-right mx-1"></i>
                    <div class="pill indigo"><i class="bx bx-book mr-2"></i>{{ $testAllocation->subject->name }}</div>
                </div>
                <div class="flex space-x-3 items-center">
                    <div class="ico rose"><i class="bi-hand-index rotate-90"></i></div>
                    <h3 class="text-red-600">Maximum Marks </h3>
                    <input type="number" id="max_marks" name="max_marks" value="{{ $testAllocation->max_marks }}"
                        class="custom-input-borderless w-16 h-8 text-center px-0" min='0' max='100'>
                </div>
            </div>
            <div class="overflow-x-auto w-full mt-6">
                <table class="table-auto borderless w-full">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th class="w-40 text-left">Name</th>
                            <th class="w-12">Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($testAllocation->results->sortBy('student.rollno') as $result)
                            <tr class="tr">
                                <td>
                                    <div class="ico emerald mx-auto">{{ $result->student->rollno }}</div>
                                </td>
                                <td class="text-left text-xs md:text-sm">{{ $result->student->name }}<br><span
                                        class="text-xs text-slate-300">{{ $result->student->father_name }}</span></td>
                                <td>
                                    <input type="text" name="result_ids_array[]" value="{{ $result->id }}" hidden>
                                    <input type="number" name="obtained_marks_array[]"
                                        value="{{ $result->obtained_marks }}"
                                        class="custom-input-borderless w-16 h-8 text-center px-0 obtained-marks text-xs md:text-sm"
                                        min='0' max='100' onclick="selectMe(event)">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="divider my-3"></div>
            <div class="text-center">
                <button type="submit" class="btn-blue py-2 px-4 rounded-lg">Update Now</button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        function selectMe(event) {
            event.target.select()
        }

        function validate(event) {
            const maxMarks = parseFloat(document.getElementById('max_marks').value);
            const inputs = document.querySelectorAll('.obtained-marks');

            for (let i = 0; i < inputs.length; i++) {
                const obtained = parseFloat(inputs[i].value);

                if (obtained > maxMarks) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Marks',
                        text: `Obtained marks (${obtained}) > maximum marks.`,
                        confirmButtonText: 'Fix it'
                    }).then(() => {
                        inputs[i].focus();
                        inputs[i].select();
                    });

                    return false; // ❌ stop submission
                }
            }

            return true; // ✅ submit form
        }
    </script>
@endsection
