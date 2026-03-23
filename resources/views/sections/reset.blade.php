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
            <div>Reset</div>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="statbox cyan w-full md:w-1/2 mx-auto shadow-xl rounded-lg p-8 mt-8">
            <div class="text-center text-lg md:text-xl font-semibold mb-4"> <i class="ri-group-line font-normal"></i>
                {{ $section->name }}
            </div>
            <p class="">This action will reset all roll numbers of the class. Be careful, click on the reset button
                only if you understand the consequences! </p>
            <div class="grid place-items-center mt-4">
                <form action="{{ route('sections.reset.rollno', $section) }}" method="post"
                    onsubmit="return confirmDel(event)">
                    @csrf
                    <button class="btn-cyan rounded px-3 py-2">Reset Roll #</button>
                </form>
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
