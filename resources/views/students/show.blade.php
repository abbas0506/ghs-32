@extends('layouts.app')
@section('page-content')
    <h2>View Student</h2>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <a href="{{ route('sections.index') }}">Sections</a>
        <div>/</div>
        <a href="{{ route('sections.show', $section) }}">{{ $section->name }}</a>
        <div>/</div>
        <div>View Student</div>
    </div>
    <!-- display info -->

    <div class="mt-4 text-slate-800">
        <a href="{{ route('sections.show', $section) }}"><i class="ri-arrow-left-long-line"></i></a>
    </div>

    <div class="md:w-4/5 mx-auto mt-4 flex flex-wrap items-center justify-between relative">
        <div class="flex items-center gap-3">
            <div class="ico cyan w-10 h-10"><i class="ri-user-6-fill"></i></div>
            <div class="font-semibold leading-tight text-sm md:text-base">{{ $student->name }} <br>
                <span class="text-slate-400 font-normal text-xs">{{ $student->father_name }}</span>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-center gap-1">
                @can('update', $student)
                    <a href="{{ route('section.students.edit', [$section, $student]) }}">
                        <i class="bx bx-pencil text-green-600"></i></a>
                @endcan
                @can('delete', $student)
                    <form action="{{ route('section.students.destroy', [$section, $student]) }}" method="post"
                        onsubmit="return confirmDel(event)">
                        @csrf
                        @method('DELETE')
                        <button><i class="bx bx-trash text-red-600"></i></button>
                    </form>
                @endcan

            </div>
        </div>

    </div>

    <div class="md:w-4/5 mx-auto grid gap-3 md:p-8 p-5 border rounded mt-6">
        <h2 class="text-cyan-800">Other Info</h2>
        <p>Class {{ $section->name }} / <span class="text-slate-400 text-xs">Roll # {{ $student->rollno }}</span></p>
        <div>
            <p class="text-sm"> Phone: {{ $student->phone }}</p>
            <p class="text-slate-500 text-xs">Address: {{ $student->address }}</p>
        </div>

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
