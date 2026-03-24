@extends('layouts.app')
@section('page-content')
    <h2>Import Students</h2>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <a href="{{ route('tests.index') }}">Tests</a>
        <div>/</div>
        <a href="{{ route('test.test-subjects.index', $testSubject->test) }}">Subjects</a>
        <div>/</div>
        <a href="{{ route('test-subject.results.index', $testSubject) }}">Result</a>
        <div>/</div>
        <div>Import Students</div>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-x-2 mt-8">
        <i class="bi bi-info-circle text-2xl w-8"></i>
        <ul class="text-sm">
            <li>If you find any student missing here, please contact admin </li>
            <li>Use "Check all" checkbox to check or uncheck all students </li>
        </ul>
    </div>

    <!-- page message -->
    @if ($errors->any())
        <x-message :errors='$errors'></x-message>
    @else
        <x-message></x-message>
    @endif

    <h2 class="text-red-600 mt-8">Total Students: {{ $missingStudents->count() }}</h2>
    <form action="{{ route('test-subject.import.store', $testSubject) }}" method="post">
        @csrf
        <div class="overflow-x-auto w-full mt-4">
            <table class="table-fixed borderless w-full">
                <thead>
                    <tr>
                        <th class="w-12">Roll #</th>
                        <th class="w-40 text-left">Name</th>
                        <th class="w-16"><input type="checkbox" id='chkAll' class="rounded"
                                onclick="checkAll()"><br><label for="">Check all</label></th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($missingStudents->sortBy('rollno') as $student)
                        <tr class="tr">
                            <td>{{ $student->rollno }}</td>
                            <td class="text-left">{{ $student->name }}<br><span
                                    class="text-xs text-slate-400">{{ $student->father_name }}</span></td>
                            <td>
                                <input type="checkbox" class="w-4 h-4 rounded" name="student_ids_array[]"
                                    value="{{ $student->id }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="divider my-3"></div>
        <div class="text-center">
            <button type="submit" class="btn-teal rounded py-2 px-4">Import Now</button>
        </div>
    </form>
@endsection
@section('script')
    <script type="text/javascript">
        function updateChkCount() {
            var chkArray = [];
            var chks = document.getElementsByName('application_ids_array[]');
            chks.forEach((chk) => {
                if (chk.checked) chkArray.push(chk.value);
            })

            document.getElementById("chkCount").innerHTML = chkArray.length;
        }

        function checkAll() {

            $('.tr').each(function() {
                if (!$(this).hasClass('hidden'))
                    $(this).children().find('input[type=checkbox]').prop('checked', $('#chkAll').is(':checked'));
                // updateChkCount()
            });
        }



        function search(event) {
            // var searchtext = event.target.value.toLowerCase();
            var searchtext = $('#searchby').val().toLowerCase();
            var str = 0;
            $('.tr').each(function() {
                if (!(
                        // search by form no or group name
                        $(this).children().eq(1).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(3).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }
    </script>
@endsection
