@extends('layouts.app')
@section('page-content')
    <style>
        table.sm tbody tr td,
        table.sm tbody tr td div {
            font-size: 12px;
        }
    </style>
    <h1>Allocations</h1>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <a href="{{ route('sections.index') }}">Sections</a>
        <div>/</div>
        <div>Allocations</div>
    </div>

    <!-- search -->
    <div class="flex items-center justify-between mt-12">
        <div class="flex relative w-full md:w-1/3">
            <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full" oninput="search(event)">
            <i class="bx bx-search absolute top-2 right-2"></i>
        </div>
        <div class="flex gap-3">
            <a href="{{ url('user-schedule') }}"><i class="bi-repeat text-green-700" title="User Schedule"></i></a>
            <a href="#" onclick="submitForm('landscape')"><i class="bi-printer text-cyan-700" title="Print Landscape (One Row/Section)"></i></a>
            <a href="#" onclick="submitForm('portrait')"><i class="bi-file-earmark-pdf text-red-700" title="Print Portrait (2 Sections/Page)"></i></a>
            {{-- data clear form --}}
            <form action="{{ url('class-schedule/clear') }}" method="post" onsubmit="return confirmClear(event)">
                @csrf
                <button type="submit"><i class="bi-recycle text-orange-600"></i></button>
            </form>

        </div>
    </div>

    <!-- page message -->
    @if ($errors->any())
        <x-message :errors='$errors'></x-message>
    @else
        <x-message></x-message>
    @endif
    <div class="mt-8">
        <input type="checkbox" id="chkAll" class="w-4 h-4 rounded ml-2" onclick="checkAll()" checked>
        <label for="" class="ml-2">Print All Sections</label>
    </div>
    <form action="{{ route('class-schedule.post') }}" method="post" id='form_sections'>
        @csrf
        <input type="hidden" name="layout" id="layout" value="landscape">
        <div class="overflow-x-auto bg-white w-full mt-2">
            <table class="table-auto sm w-full">
                <thead>
                    <tr>
                        <th class="chk w-6 hidden"></th>
                        <th class="w-24">Class</th>
                        @foreach ($lectures as $lecture)
                            <th>{{ $lecture->lecture_no }} <br><span
                                    class="text-slate-500 font-normal">{{ $lecture->starts_at->format('H:i') }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sections as $section)
                        <tr class="tr">
                            <td class="chk hidden"><input type="checkbox" class="w-4 h-4 rounded" name="section_ids_array[]"
                                    value="{{ $section->id }}" checked></td>
                            <td class="font-bold"> {{ $section->name }}</td>
                            @foreach ($lectures as $lecture)
                                <td class="p-1">
                                    @foreach ($section->schedules()->havingLectureNo($lecture->lecture_no)->get() as $allocation)
                                        <div class="text-sm bg-teal-50">
                                            <a href="{{ route('section.lecture.schedule.edit', [$section, $lecture->lecture_no, $allocation]) }}"
                                                class="link">{{ $allocation->subject->short_name }}</a>
                                            <p>{{ $allocation->user->profile?->short_name }}</p>
                                        </div>
                                        <div class="divider"></div>
                                    @endforeach
                                    <a href="{{ route('section.lecture.schedule.create', [$section, $lecture->lecture_no]) }}"
                                        class="text-sm link"><i class="bi-plus"></i></a>
                                </td>
                            @endforeach

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </form>


    </div>

    <script type="text/javascript">
        function confirmClear(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form

            Swal.fire({
                title: 'Are you sure?',
                text: "You are going to clean this class!",
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


        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            var str = 0;
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(0).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(1).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(2).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(3).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(4).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(4).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(5).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(6).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(7).prop('outerText').toLowerCase().includes(searchtext) ||
                        $(this).children().eq(8).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }

        function checkAll() {

            if ($('#chkAll').is(':checked')) {
                $('.chk').addClass('hidden')
            } else {
                $('.chk').removeClass('hidden')
            }
            $('.tr').each(function() {
                $(this).children().find('input[type=checkbox]').prop('checked', $('#chkAll').is(':checked'));
            })

        }

        function submitForm(layout = 'landscape') {
            document.getElementById("layout").value = layout;
            let form = document.getElementById("form_sections"); // storing the form
            form.submit();
        }
    </script>
@endsection
