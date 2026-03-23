@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h1>Classes</h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <div>Classes</div>
            <div>/</div>
            <div>All</div>
        </div>
        <div class="w-full md:w-4/5 mx-auto overflow-auto bg-white md:p-8 p-0 mt-8">
            <div class="flex items-center flex-wrap justify-between">
                <div><i class="ri-user-6-fill"></i> {{ $studentsCount }} <span class="text-slate-500 text-sm">Students
                        found</span>
                </div>
                @can('create', App\Models\Section::class)
                    <a href="{{ route('sections.create') }}" class="btn-teal rounded text-sm py-2 px-3 mt-2 md:mt-0">+ New
                        Class</a>
                @endcan
            </div>

            {{-- define array of colors --}}
            @php
                $colors = ['green', 'blue', 'indigo', 'orange', 'violet', 'rose', 'cyan', 'lime', 'emerald', 'amber'];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-4 mt-5 md:mt-8 text-xs md:text-sm">
                @foreach ($sections->sortBy('grade') as $section)
                    <a href="{{ route('sections.show', $section) }}"
                        class="statbox {{ $colors[$loop->index % count($colors)] }}">
                        <div class="flex justify-between items-center flex-1">

                            <div class=""><i class="ri-group-line"></i> {{ $section->name }}</div>
                            <span class=" font-normal text-xs rounded-full pl-2 py-[1px]">
                                +{{ $section->newAdmissions()->count() }} <i class="ri-user-received-line"></i></span>
                        </div>
                        <div class="font-semibold text-lg mt-[1px]">{{ $section->students->count() }}
                            {{-- round green badge  --}}


                            <span class=" font-normal text-xs rounded-full px-2 py-[1px]">
                                {{ $section->averageAttendance() }}% <i class="ri-user-location-line"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @can('create', Section::class)
        <a href="{{ route('sections.create') }}"
            class="fixed bottom-8 right-8 flex rounded-full w-12 h-12 btn-blue justify-center items-center text-2xl"><i
                class="bi bi-plus"></i></a>
    @endcan
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
