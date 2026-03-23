@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <h1>Classes</h1>
        <div class="bread-crumb">
            <a href="{{ url('/') }}">Dashoboard</a>
            <div>/</div>
            <div>Classes</div>
            <div>/</div>
            <div>All</div>
        </div>
        <div class="w-full md:w-4/5 mx-auto overflow-auto bg-white md:p-8 p-0 mt-4">

            <!-- page message -->
            @if ($errors->any())
                <x-message :errors='$errors'></x-message>
            @else
                <x-message></x-message>
            @endif

            <div class="flex items-center flex-wrap justify-between">
                <div><i class="ri-user-6-fill"></i> {{ $studentsCount }} <span
                        class="text-slate-500 text-xs md:text-sm">Students
                        found</span>
                </div>
                @can('create', App\Models\Section::class)
                    <a href="{{ route('sections.create') }}"
                        class="fixed bottom-2 right-2 w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center p-2 bg-teal-600 text-white hover:bg-teal-700 transition duration-300 ease-in-out"><i
                            class="bi-plus"></i>
                    </a>
                @endcan
            </div>

            {{-- define array of colors --}}
            @php
                $colors = ['green', 'blue', 'indigo', 'orange', 'violet', 'rose', 'cyan', 'lime', 'emerald', 'amber'];
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 md:gap-4 mt-5 md:mt-8 text-xs md:text-sm">
                @foreach ($sections->sortBy('grade') as $section)
                    <a href="{{ route('sections.show', $section) }}"
                        class="statbox {{ $colors[$loop->index % count($colors)] }}">

                        <div class=""><i class="ri-group-line"></i> {{ $section->name }}</div>

                        <div class="font-semibold text-lg mt-[1px]">{{ $section->students->count() }}
                            {{-- round green badge  --}}
                            <span class=" font-normal text-xs rounded-full pl-2 py-[1px]">
                                +{{ $section->newAdmissions()->count() }} <i class="ri-user-received-line"></i></span>
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
