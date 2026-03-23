@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h1>Move/Export Students</h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <a href="{{ route('sections.index') }}">Sections</a>
            <div>/</div>
            <a href="{{ route('sections.show', $section) }}">{{ $section->name }}</a>
            <div>/</div>
            <div>Export</div>
        </div>

        <div class="w-full md:w-4/5 mx-auto rounded border-[1px] p-4 md:px-8 mt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                <div class="">
                    <h1> <i class="ri-group-line"></i> {{ $section->name }} </h1>
                    <div class="text-slate-600 text-xs md:text-sm">{{ $section->students->count() }} Students
                        found
                    </div>
                </div>
                <div class="">
                    <label for="" class="text-red-600"><i class="ri-user-shared-line"></i> Export To Class</label>
                    <select id="section_id_export" class="custom-input-borderless text-sm md:text-base px-4 text-slate-500"
                        required>
                        <option value="">—— Select Class ———</option>
                        @foreach ($exportSections as $exSec)
                            <option value="{{ $exSec->id }}" class=""> Class {{ $exSec->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <form action="{{ route('sections.export.post') }}" method="post" onsubmit="return validateBeforeSubmit(event)">
                @csrf

                <!-- search -->
                <div class="flex relative w-full md:w-1/3 mt-5">
                    <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                        oninput="search(event)">
                    <i class="bx bx-search absolute top-2 right-2"></i>
                </div>

                <!-- page message -->
                @if ($errors->any())
                    <x-message :errors='$errors'></x-message>
                @else
                    <x-message></x-message>
                @endif
                {{-- hidden element for section id --}}
                <input type=" hidden" name="section_id" id="section_id">
                <div class="overflow-x-auto bg-white w-full mt-8">

                    <table class="table-auto borderless w-full">
                        <thead>
                            <tr>
                                <th class="w-8">#</th>
                                <th class="w-40 text-left">Name</th>
                                <th class="w-6"><input type="checkbox" id='chkAll' class="rounded"
                                        onclick="checkAll()">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($section->students->sortBy('rollno') as $student)
                                <tr class="tr">
                                    <td>
                                        <div class="ico cyan mx-auto">
                                            {{ $student->rollno }}</div>
                                    </td>
                                    <td class="text-left">
                                        <div class="text-xs md:text-sm">{{ $student->name }}<br>
                                            <span class="text-xs text-slate-400">{{ $student->father_name }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-center">
                                            <input type="checkbox" class="w-4 h-4 rounded" name="student_ids_array[]"
                                                value="{{ $student->id }}">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-5 bg-blue-50 mt-2 text-right">
                    <button type="submit" class="btn-blue rounded text-xs md:text-sm px-3 py-2">Move / Export</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            var str = 0;
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(0).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(1).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }

        function checkAll() {

            $('.tr').each(function() {
                if (!$(this).hasClass('hidden'))
                    $(this).children().find('input[type=checkbox]').prop('checked', $('#chkAll').is(':checked'));
                // updateChkCount()
            });
        }

        function validateBeforeSubmit(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form
            var sectionId = $('#section_id_export').val();
            // section id provided
            if (sectionId) {
                $('#section_id').val(sectionId)
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, move it!'
                }).then((result) => {
                    if (result.value) {
                        form.submit();
                    }
                })
                // section id mission
            } else {
                Swal.fire({
                    title: 'Alert',
                    text: "Where do you want to export the selected students? Section missing!",
                    type: 'warning',
                    showCancelButton: false,
                    ShowConfirmButton: true,
                    confirmButtonText: 'Ok',
                    confirmButtonColor: '#3085d6',
                })
            }


        }
    </script>
@endsection
