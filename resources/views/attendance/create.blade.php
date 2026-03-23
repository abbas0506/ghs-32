@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h1>Class: {{ $section->name }}</h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <a href="{{ route('attendance.summary') }}">Attendance</a>
            <div>/</div>
            <div>Create</div>
        </div>

        <!-- search -->
        <!-- <div class="flex justify-between items-center flex-wrap gap-6 mt-12"> -->


        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="w-full md:w-4/5 mx-auto border-[0.5px] rounded-lg bg-white mt-6">
            <div
                class="grid grid-cols-1 md:grid-cols-2 p-2 md:p-8 gap-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50">
                <div class="flex items-center gap-3 ">
                    <div class="ico blue w-9 h-9 rounded-xl ">
                        <i class="bi-calendar-check text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-800 text-sm leading-tight">
                            {{ now()->format('d-m-Y') }}
                        </h2>

                        <p class="text-xs text-gray-400"> <i class="ri-user-6-fill"></i> {{ $section->students->count() }}
                            students
                        </p>
                    </div>

                </div>
                <div class="flex relative w-full md:w-1/2">
                    <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                        oninput="search(event)">
                    <i class="bx bx-search absolute top-2 right-2"></i>
                </div>
            </div>
            {{-- students list --}}
            <div class="overflow-x-auto bg-white w-full px-2 md:px-8 mt-6">
                <form action="{{ route('section.attendance.store', $section) }}" method="post" class="mt-3">
                    @csrf
                    <table class="table-auto borderless w-full">
                        <thead>
                            <tr>
                                <th class="w-10">#</th>
                                <th class="w-48 text-left">Name</th>
                                <th class="w-6"><input type="checkbox" id='chkAll' class="rounded"
                                        onclick="checkAll()">

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($section->students->sortBy('rollno') as $student)
                                <tr class="tr">
                                    <td>
                                        <div class="ico emerald mx-auto">{{ $student->rollno }}</div>
                                    </td>
                                    <td class="text-left text-xs md:text-sm">{{ $student->name }} <br> <span
                                            class="text-slate-400">{{ $student->father_name }}</span></td>
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
                    <div class="text-center text-xs md:text-sm mt-8">
                        <a href="{{ route('section.attendance.index', $section) }}"
                            class="btn-gray rounded py-2 px-3 mr-3">Cancel <i class="ri-close-fill ml-1"></i></a>
                        <button type="submit" class="btn-blue rounded py-2 px-3">Submit <i
                                class="ri-user-follow-line ml-1"></i></button>
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
        </script>
    @endsection
