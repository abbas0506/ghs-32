@extends('layouts.app')
@section('page-content')
    <h1>Subjects</h1>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <div>Subjects</div>
        <div>/</div>
        <div>All</div>
    </div>


    <div class="md:w-4/5 mx-auto mt-8">
        <div class="flex items-center flex-wrap justify-between">
            <!-- search -->
            <div class="flex relative w-full md:w-1/3">
                <input type="text" id='searchby' placeholder="Search ..." class="custom-search w-full"
                    oninput="search(event)">
                <i class="bx bx-search absolute top-2 right-2"></i>
            </div>
            <a href="{{ route('subjects.create') }}" class="btn-teal rounded">New</a>
        </div>

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <table class="table-auto borderless w-full mt-8">
            <thead>
                <tr class="">
                    <th class="w-16">Sr</th>
                    <th class="text-left">Subject</th>
                    <th class="w-24">Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($subjects as $subject)
                    <tr class="tr">
                        <td>{{ $loop->index + 1 }}</td>
                        <td class="text-left">{{ $subject->name }} <br>
                            <span class="text-slate-400">{{ $subject->short_name }}</span>
                        </td>
                        <td>
                            <div class="flex justify-center items-center space-x-3">
                                <a href="{{ route('subjects.edit', $subject) }}">
                                    <i class="bx bx-pencil text-green-600"></i>
                                </a>
                                <span class="text-slate-400">|</span>
                                <form action="{{ route('subjects.destroy', $subject) }}" method="POST"
                                    id='del_form{{ $subject->id }}'>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-transparent p-0 border-0"
                                        onclick="delme('{{ $subject->id }}')">
                                        <i class="bx bx-trash text-red-600"></i>
                                    </button>
                                </form>
                            </div>

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function delme(formid) {

            event.preventDefault();

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
                    //submit corresponding form
                    $('#del_form' + formid).submit();
                }
            });
        }

        function search(event) {
            var searchtext = event.target.value.toLowerCase();
            var str = 0;
            $('.tr').each(function() {
                if (!(
                        $(this).children().eq(1).prop('outerText').toLowerCase().includes(searchtext)
                    )) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        }
    </script>
@endsection
