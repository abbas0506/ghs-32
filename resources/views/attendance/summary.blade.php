@extends('layouts.app')
@section('page-content')
    <h2 class="">Attendance Management</h2>
    <div class="bread-crumb">
        <a href="{{ url('/') }}">Home</a>
        <div>/</div>
        <div>Attendance</div>

    </div>
    {{-- Filter Section --}}
    <div class="my-5">
        <div class="statbox primary border rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center gap-2 md:gap-4 mb-4">
                <i class="bi bi-calendar-range text-xl"></i>
                <div>
                    <p class=" text-xs font-semibold uppercase tracking-widest">Select Date</p>
                    <p class="text-sm font-semibold">Filter attendance by date</p>
                </div>
            </div>
            <form action="{{ route('attendance.filter') }}" method="post" id="form_filter">
                @csrf
                <input type="hidden" name="date" id="date">
                <input type="date" id='filter_date'
                    class="w-full md:w-64 px-3 py-2 border rounded-lg border-slate-200 text-slate-800 font-medium text-sm focus:ring-2 focus:ring-cyan-400">
            </form>
        </div>
    </div>

    <!-- page message -->
    @if ($errors->any())
        <x-message :errors='$errors'></x-message>
    @else
        <x-message></x-message>
    @endif

    {{-- Overall Stats Header --}}
    <div class="statbox sky mb-6">
        <div class="flex flex-1 flex-wrap gap-3 items-center md:justify-between">
            <div>
                <p class="text-cyan-600 text-xs font-semibold uppercase tracking-widest mb-1">Date</p>
                <h2 class="text-base md:text-lg font-semibold text-cyan-700 flex items-center gap-2">
                    <i class="bi bi-calendar-check text-blue-600"></i>
                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                    @if (\Carbon\Carbon::parse($date)->isToday())
                        <span class="text-xs bg-green-200 text-green-800 px-3 py-1 rounded-full font-semibold">Today</span>
                    @endif
                </h2>
            </div>
            @if ($sections->count() > 1 && $overall_total)
                <div class="text-right">
                    <p class="text-cyan-600 text-xs font-semibold uppercase tracking-widest mb-1">
                        Overall
                        Attendance</p>
                    <div class="flex items-baseline gap-2">
                        <span
                            class="text-xl md:text-2xl font-bold text-cyan-600">{{ round(($overall_present / $overall_total) * 100, 1) }}%</span>
                        <span class="text-xs text-cyan-600">({{ $overall_present }}/{{ $overall_total }})</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Section Cards Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach ($sections as $section)
            @php
                $pct = $section->total ? round(($section->present / $section->total) * 100, 1) : 0;
                $colorClass =
                    $pct >= 90
                        ? 'from-cyan-50 to-teal-50'
                        : ($pct >= 75
                            ? 'from-blue-50 to-cyan-50'
                            : 'from-slate-50 to-slate-100');
                $borderColor = $pct >= 90 ? 'border-teal-200' : ($pct >= 75 ? 'border-cyan-200' : 'border-slate-200');
                $accentColor =
                    $pct >= 90
                        ? 'text-teal-600 bg-teal-100'
                        : ($pct >= 75
                            ? 'text-cyan-600 bg-cyan-100'
                            : 'text-slate-600 bg-slate-100');
            @endphp
            <a href="@if ($section->attendance_marked() > 0) {{ route('section.attendance.index', $section) }} @elseif(\Carbon\Carbon::parse($date)->isToday()){{ route('section.attendance.create', $section) }} @else {{ route('section.attendance.index', $section) }} @endif "
                class="bg-gradient-to-br {{ $colorClass }} rounded border {{ $borderColor }} transition-all duration-300 transform hover:scale-105 p-2 md:p-4">
                <div class="flex items-start justify-between">
                    <div class="flex flex-1 justify-between">
                        <h3 class="font-semibold text-cyan-700">{{ $section->name }}</h3>
                    </div>
                </div>
                {{-- Progress Bar --}}
                <div class="flex items-center justify-between mt-1">
                    <div class="flex items-baseline mb-1">
                        <span class="text-sm md:text-xl font-semibold text-cyan-700">{{ $section->present ?? 0 }}</span>
                        <span class="text-xs text-cyan-600">/
                            {{ $section->students->count() ?? 0 }}
                        </span>
                    </div>
                    <span
                        class="text-xs font-semibold {{ $pct >= 90 ? 'text-teal-600' : ($pct >= 75 ? 'text-cyan-600' : 'text-slate-600') }}">{{ $pct }}%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r {{ $pct >= 90 ? 'from-teal-400 to-cyan-500' : ($pct >= 75 ? 'from-cyan-400 to-blue-500' : 'from-sky-400 to-blue-500') }} h-2 rounded-full transition-all"
                        style="width: {{ min($pct, 100) }}%"></div>
                </div>

            </a>
        @endforeach
    </div>
@endsection
@section('script')
    <script type="module">
        $(document).ready(function() {
            $('#filter_date').on('change', function() {
                let selected = $(this).val();
                $('#date').val(selected);
                $('#form_filter').submit();
            });
        });
    </script>
    <script type="text/javascript">
        function confirmClear(event) {
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
