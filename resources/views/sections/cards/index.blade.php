@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <!-- Title     -->
        <h1>Student Cards</h1>
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex-1">
                <div class="bread-crumb">
                    <a href="{{ url('/') }}">Home</a>
                    <div>/</div>
                    <a href="{{ route('sections.index') }}">Sections</a>
                    <div>/</div>
                    <a href="{{ route('sections.show', $section) }}">{{ $section->name }}</a>
                    <div>/</div>
                    <div>Cards</div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-4/5 mx-auto rounded border-[1px] p-4 md:px-8 mt-8">
            <div class="">
                <h1> <i class="ri-group-line"></i> {{ $section->name }} </h1>
                <div class="text-slate-600 text-xs md:text-sm">{{ $section->students->count() }} Students
                    found
                </div>
            </div>

            <form action="{{ route('section.cards.store', $section) }}" method="post">
                @csrf
                <div class="flex mt-4">
                    <!-- search -->
                    <div class="flex relative w-full md:w-1/3">
                        <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                            oninput="search(event)">
                        <i class="bx bx-search absolute top-2 right-2"></i>
                    </div>
                    <div class="flex justify-end w-full">
                        <div
                            class="flex w-12 h-12 items-center justify-center rounded-full bg-orange-100 hover:bg-orange-200">
                            <button type="submit"><i class="bi-printer"></i></button>
                        </div>
                    </div>

                </div>
                <!-- page message -->
                @if ($errors->any())
                    <x-message :errors='$errors'></x-message>
                @else
                    <x-message></x-message>
                @endif

                <div class="overflow-x-auto w-full mt-8">

                    <table class="table-fixed borderless w-full">
                        <thead>
                            <tr class="">
                                <th class="w-8">#</th>
                                <th class="w-48 text-left">Student</th>
                                <th class="w-16">Photo</th>
                                <th class="w-8 py-2"><input type="checkbox" id='chkAll' class="rounded"
                                        onclick="checkAll()">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($section->students as $student)
                                <tr class="tr text-sm border-b">
                                    <td>
                                        <div class="ico pink mx-auto">
                                            {{ $student->rollno }}
                                        </div>
                                    </td>
                                    <td class="text-left">
                                        {{ $student->name }}<br><span
                                            class="text-slate-400 text-xs">{{ $student->father_name }}</span>
                                    </td>
                                    <td><img src="{{ asset('storage/' . $student->photo) }}" alt="photo"
                                            class="rounded mx-auto w-8 h-8"></td>
                                    <td><input type="checkbox" class="w-4 h-4 rounded" name="student_ids_array[]"
                                            value="{{ $student->id }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function search(event) {
            // var searchtext = event.target.value.toLowerCase();
            var searchtext = $('#searchby').val().toLowerCase();
            var str = 0;
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(2).prop('outerText').toLowerCase().includes(searchtext)
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
