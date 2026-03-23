@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h2>View Class</h2>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <a href="{{ route('sections.index') }}">Sections</a>
            <div>/</div>
            <div>{{ $section->name }}</div>
        </div>

        <div class="flex statbox cyan p-5 justify-between items-center border rounded md:w-4/5 mt-8 mx-auto">
            <div>
                <h2> <i class="ri-group-line"></i> {{ $section->name }} </h2>
                <div class="text-slate-600 text-xs md:text-sm">{{ $section->students->count() }} Students
                    found
                </div>
            </div>
            <div class="flex space-x-2 items-center">
                @can('update', $section)
                    <a href="{{ route('sections.edit', $section) }}"><i class="bx bx-pencil text-green-600"></i></a>
                @endcan
                @can('delete', $section)
                    <form action="{{ route('sections.destroy', $section) }}" method="POST" onsubmit="return confirmDel(event)">
                        @csrf @method('DELETE')
                        <button type="submit"><i class="bx bx-trash text-red-600"></i></button>
                    </form>
                @endcan
            </div>
        </div>
        <!-- search -->

        <div class="md:w-4/5 mx-auto p-5 md:p-8 rounded text-sm border mt-5">
            <div class="flex justify-center items-center gap-3 flex-wrap">
                <a href="{{ route('section.students.create', $section) }}"><i
                        class="bi bi-person-add text-teal-600"></i></a>
                <a href="{{ route('sections.export', $section) }}" class=""><i
                        class="bi bi-arrow-right-square text-teal-600"></i></a>
                <a href="{{ route('sections.reset', $section) }}" class=""><i
                        class="bi-repeat-1 text-orange-600"></i></a>
                <a href="{{ route('section.cards.index', $section) }}" class=""><i
                        class="bi-person-badge text-indigo-600"></i></a>

                @can('clean', $section)
                    <a href="{{ route('sections.clean', $section) }}" class=""><i
                            class="bx bx-recycle text-orange-600"></i></a>
                @endcan
                <a href="{{ route('section.cards.index', $section) }}" class=""><i
                        class="bi-printer text-teal-600"></i></a>
            </div>

            <!-- page message -->
            @if ($errors->any())
                <x-message :errors='$errors'></x-message>
            @else
                <x-message></x-message>
            @endif

            <div class="flex relative w-full md:w-1/3 mt-3">
                <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                    oninput="search(event)">
                <i class="bx bx-search absolute top-2 right-2"></i>
            </div>

            <div class="overflow-x-auto bg-white w-full mt-8">

            </div>
            <table class="table-auto borderless w-full mt-1">
                <thead>
                    <tr>
                        <th class="w-10">#</th>
                        <th class="w-48 text-left">Name</th>
                        {{-- <th class="w-16">Photo</th> --}}

                    </tr>
                </thead>
                <tbody>
                    @foreach ($section->students->sortBy('rollno') as $student)
                        <tr class="tr">
                            <td>
                                <a href="{{ route('section.students.show', [$section, $student]) }}"
                                    class="link text-sm ico teal mx-auto">{{ $student->rollno }}</a>
                            </td>
                            <td class="text-left  text-xs md:text-sm">
                                {{ $student->name }} @if ($student->hasBeenCreatedThisWeek())
                                    <i class="ri-user-received-line ml-3"></i>
                                @endif
                                </a>
                                <br><span class="text-slate-400">{{ $student->father_name }}</span>
                            </td>
                            {{-- <td><img src="{{ asset('storage/' . $student->photo) }}" alt="photo"
                                        class="rounded mx-auto w-8 h-8"></td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

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
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            })
        }
    </script>
@endsection
