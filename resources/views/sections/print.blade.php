@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h1>Reset Index</h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <a href="{{ route('sections.index') }}">Sections</a>
            <div>/</div>
            <a href="{{ route('sections.show', $section) }}">{{ $section->name }}</a>
            <div>/</div>
            <div>Print</div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="w-full mt-8">
            <h1 class="text-center">{{ $section->name }}</h1>
            <div class="grid grid-cols-2 place-items-center w-full md:w-3/4 mx-auto gap-4 mt-5">
                <a href="{{ route('sections.print.phoneList', $section) }}" target="_blank"
                    class="link w-full p-6 border shadow-md rounded-lg bg-slate-100 hover:no-underline text-center">
                    <div><i class="bi-telephone"></i></div>
                    <p>Phone List</p>
                </a>
                <a href="{{ route('sections.print.attendanceList', $section) }}" target="_blank"
                    class="link w-full p-6 border shadow-md rounded-lg bg-slate-100 hover:no-underline text-center">
                    <div><i class="bi-check"></i></div>
                    <p>Attendance List</p>
                </a>
                <a href="{{ route('sections.print.studentDetail', $section) }}" target="_blank"
                    class="link w-full p-6 border shadow-md rounded-lg bg-slate-100 hover:no-underline text-center">
                    <div><i class="bi-people"></i></div>
                    <p>Students Detail</p>
                </a>
                <a href="{{ route('sections.print.orphanList', $section) }}" target="_blank"
                    class="link w-full p-6 border shadow-md rounded-lg bg-slate-100 hover:no-underline text-center">
                    <div><i class="bi-file-earmark-text"></i></div>
                    <p>Orphan List</p>
                </a>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        function confirmDel(event) {
            event.preventDefault(); // prevent form submit
            var form = event.target; // storing the form

            Swal.fire({
                title: 'Are you sure?',
                text: "Reset action is destructive!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, perform reset!'
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            })
        }

        function confirmClean(event) {
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
    </script>
@endsection
