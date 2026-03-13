@extends('layouts.app')

@section('page-content')
    <h2>Edit Allocation</h2>
    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('class-schedule') }}">Allocations</a>
        <div>/</div>
        <div>Edit</div>
    </div>

    <div class="md:w-3/4 mx-auto mt-6 bg-white md:p-8 rounded">
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <div class="flex justify-between">
            <h2>Class {{ $allocation->section->name }} / Lecture # {{ $allocation->lecture_no }}</h2>
            <form
                action="{{ route('section.lecture.schedule.destroy', [$allocation->section, $allocation->lecture_no, $allocation]) }}"
                method="POST" onsubmit="return confirmDel(event)">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex justify-center items-center w-12 h-12 rounded-full border hover:bg-slate-100">
                    <i class="bi bi-trash3"></i>
                </button>
            </form>
        </div>
        <div class="divider my-2"></div>
        <form
            action="{{ route('section.lecture.schedule.update', [$allocation->section, $allocation->lecture_no, $allocation]) }}"
            method='post' class="w-full grid gap-6" onsubmit="return validate(event)">
            @csrf
            @method('PATCH')
            <div>
                <label>Subject</label>
                <select name="subject_id" id="" class="custom-input-borderless">
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected($allocation->subject_id == $subject->id)>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>user</label>
                <select name="user_id" id="" class="custom-input-borderless">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected($allocation->user_id == $user->id)>{{ $user->profile?->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submmit" class="btn-teal rounded p-2 w-32 mt-3">Update Now</button>
            </div>
        </form>

    </div>
@endsection
@section('script')
    <script type="text/javascript">
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
