@extends('layouts.app')
@section('page-content')
    <style>
        table.sm tbody tr td,
        table.sm tbody tr td div {
            font-size: 12px;
        }
    </style>
    <h1>Teacher Wise Schedule</h1>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <div>Schedule</div>
    </div>

    <!-- search -->
    <div class="flex items-center justify-between mt-12">
        <div class="flex relative w-full md:w-1/3">
            <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full" oninput="search(event)">
            <i class="bx bx-search absolute top-2 right-2"></i>
        </div>
        </div>

    <!-- page message -->
    @if ($errors->any())
        <x-message :errors='$errors'></x-message>
    @else
        <x-message></x-message>
    @endif

    <div class="overflow-x-auto bg-white w-full mt-8">
        <div>
            <input type="checkbox" id="chkAll" class="w-4 h-4 rounded ml-2" onclick="checkAll()" checked>
            <label for="" class="ml-2">Print All</label>
        </div>

        <form action="{{ route('user-schedule.post') }}" method="post" id='form_post'>
            @csrf
            <input type="hidden" id="print_mode" name="print_mode" value="0">
            <table class="table-auto sm w-full">
                <thead>
                    <tr>
                        <th class="chk w-6 hidden"></th>
                        <th class="w-6">Sr</th>
                        <th class="w-24">user</th>
                        @foreach ($lectures as $lecture)
                            <th>{{ $lecture->lecture_no }} <br><span
                                    class="text-slate-500 font-normal">{{ $lecture->starts_at->format('H:i') }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="chk hidden"><input type="checkbox" class="w-4 h-4 rounded" name="user_ids_array[]"
                                    value="{{ $user->id }}" checked></td>
                            <td>{{ $loop->index + 1 }}</td>
                            <td class="font-semibold">{{ $user->profile?->short_name }} <br>
                                ({{ $user->schedules->count() }})
                            </td>
                            @foreach (range(1, 8) as $lecture_no)
                                <td class="p-1">
                                    @foreach ($user->schedules()->havingLectureNo($lecture_no)->get() as $allocation)
                                        <div class="text-sm bg-teal-50">
                                            <p class="font-bold">{{ $allocation->subject->short_name }}-
                                                {{ $allocation->section->grade?->grade_no }}
                                            </p>
                                        </div>
                                        <div class="divider"></div>
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </form>
    </div>

    <script type="text/javascript">
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

            if ($('#chkAll').is(':checked')) {
                $('.chk').addClass('hidden')
            } else {
                $('.chk').removeClass('hidden')
            }
            $('.tr').each(function() {
                $(this).children().find('input[type=checkbox]').prop('checked', $('#chkAll').is(':checked'));
            })

        }

        function setPrintModeAndSubmitForm(mode) {
            let form = document.getElementById("form_post"); // storing the form
            document.getElementById("print_mode").value = mode;
            form.submit();
        }
    </script>
@endsection
