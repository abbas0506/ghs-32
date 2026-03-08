@extends('layouts.app')
@section('page-content')
    <div class="custom-container">
        <div class="md:w-4/5 mx-auto">
            <h1 class="text-lg md:text-2xl font-semibold text-cyan-700 mb-2">Attendance Management</h1>
            <div class="bread-crumb">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <div>Attendance</div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="md:w-4/5 mx-auto mt-8 mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 border rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center gap-2 md:gap-4 mb-4">
                    <i class="bi bi-calendar-range text-cyan-100 text-xl"></i>
                    <div>
                        <p class="text-cyan-100 text-xs font-semibold uppercase tracking-widest">Select Date</p>
                        <p class="text-sm font-semibold">Filter attendance by date</p>
                    </div>
                </div>
                <form action="{{ route('attendance.filter') }}" method="post" id="form_filter">
                    @csrf
                    <input type="hidden" name="date" id="date">
                    <input type="date" id='filter_date'
                        class="w-full md:w-64 px-3 py-2 rounded-lg border-0 text-slate-800 font-medium text-sm focus:ring-2 focus:ring-cyan-400">
                </form>
            </div>
        </div>

        <div class="md:w-4/5 mx-auto">
            <!-- page message -->
            @if ($errors->any())
                <x-message :errors='$errors'></x-message>
            @else
                <x-message></x-message>
            @endif

            {{-- Overall Stats Header --}}
            <div class="statbox primary mb-6">
                <div class="flex flex-1 flex-wrap gap-3 items-center justify-between">
                    <div>
                        <p class="text-cyan-600 text-xs font-semibold uppercase tracking-widest mb-1">Date</p>
                        <h2 class="text-base md:text-lg font-semibold text-cyan-700 flex items-center gap-2">
                            <i class="bi bi-calendar-check text-blue-600"></i>
                            {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                            @if (\Carbon\Carbon::parse($date)->isToday())
                                <span
                                    class="text-xs bg-green-200 text-green-800 px-3 py-1 rounded-full font-semibold">Today</span>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                @foreach ($sections as $section)
                    @php
                        $pct = $section->total ? round(($section->present / $section->total) * 100, 1) : 0;
                        $colorClass =
                            $pct >= 90
                                ? 'from-cyan-50 to-teal-50'
                                : ($pct >= 75
                                    ? 'from-blue-50 to-cyan-50'
                                    : 'from-purple-50 to-blue-50');
                        $borderColor =
                            $pct >= 90 ? 'border-teal-200' : ($pct >= 75 ? 'border-cyan-200' : 'border-purple-200');
                        $accentColor =
                            $pct >= 90
                                ? 'text-teal-600 bg-teal-100'
                                : ($pct >= 75
                                    ? 'text-cyan-600 bg-cyan-100'
                                    : 'text-purple-600 bg-purple-100');
                    @endphp
                    <div
                        class="bg-gradient-to-br {{ $colorClass }} rounded-xl shadow-md hover:shadow-xl border {{ $borderColor }} transition-all duration-300 transform hover:scale-105 p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex flex-1 justify-between">
                                <h3 class="text-base md:text-lg font-semibold text-cyan-700">{{ $section->name }}</h3>
                                <div
                                    class="flex items-center justify-center {{ $accentColor }} rounded-full px-3 py-0 text-xs">
                                    <i class="bx bx-trending-up mr-1"></i> {{ $pct }}%
                                </div>
                            </div>
                            {{-- <div class="rounded-full p-2 md:p-3 {{ $accentColor }}">
                                <i class="bi bi-people text-base"></i>
                            </div> --}}
                        </div>

                        <div class="space-y-3">
                            {{-- Stats --}}
                            <div>
                                <div class="flex items-baseline mb-2">
                                    <span
                                        class="text-xl md:text-2xl font-bold text-cyan-700">{{ $section->present ?? 0 }}</span>
                                    <span class="text-xs text-cyan-600 font-semibold">/
                                        {{ $section->students->count() ?? 0 }}
                                    </span>
                                </div>
                            </div>

                            {{-- Progress Bar --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold text-cyan-700">Attendance</span>
                                    <span
                                        class="text-xs font-semibold {{ $pct >= 90 ? 'text-teal-600' : ($pct >= 75 ? 'text-cyan-600' : 'text-purple-600') }}">{{ $pct }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r {{ $pct >= 90 ? 'from-teal-400 to-cyan-500' : ($pct >= 75 ? 'from-cyan-400 to-blue-500' : 'from-purple-400 to-blue-500') }} h-2 rounded-full transition-all"
                                        style="width: {{ min($pct, 100) }}%"></div>
                                </div>
                            </div>

                            {{-- Action Button --}}
                            <div class="pt-2 flex w-full justify-center">
                                @if ($section->attendance_marked() > 0)
                                    <a href="{{ route('section.attendance.index', $section) }}"
                                        aria-label="View attendance for {{ $section->name }}"
                                        class="inline-flex items-center gap-2 w-full justify-center px-3 py-2 rounded-lg border border-cyan-300 bg-white text-cyan-700 text-sm font-medium shadow-sm hover:bg-cyan-50 hover:border-cyan-400 transition-all duration-200">
                                        <i class="bi bi-eye"></i>
                                        View Details
                                    </a>
                                @else
                                    @if (\Carbon\Carbon::parse($date)->isToday())
                                        <a href="{{ route('section.attendance.create', $section) }}"
                                            aria-label="Mark attendance for {{ $section->name }}"
                                            class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-cyan-600 text-white text-xs hover:bg-cyan-700 hover:shadow-lg transition-all duration-200">
                                            <i class="bx bx-pencil mr-1"></i>
                                            Mark Attendance
                                        </a>
                                    @else
                                        <div
                                            class="inline-flex items-center gap-2 w-full justify-center px-3 py-2 rounded-lg bg-slate-100 text-slate-500 text-sm font-medium cursor-not-allowed">
                                            <i class="bi bi-lock-fill"></i>
                                            Not Available
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Overall Summary Footer --}}
            @if ($sections->count() > 1 && $overall_total)
                <div
                    class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl shadow-lg p-6 text-white border border-cyan-500">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                        <div class="text-center">
                            <p class="text-cyan-100 text-xs font-semibold uppercase tracking-widest mb-2">Total Present
                            </p>
                            <p class="text-xl md:text-2xl font-bold">{{ $overall_present }}</p>
                        </div>
                        <div class="text-center border-l border-cyan-400">
                            <p class="text-cyan-100 text-xs font-semibold uppercase tracking-widest mb-2">Total Enrolled
                            </p>
                            <p class="text-xl md:text-2xl font-bold">{{ $overall_total }}</p>
                        </div>
                        <div class="text-center border-l border-cyan-400">
                            <p class="text-cyan-100 text-xs font-semibold uppercase tracking-widest mb-2">Attendance %</p>
                            <p class="text-xl md:text-2xl font-bold">
                                {{ round(($overall_present / $overall_total) * 100, 1) }}%</p>
                        </div>
                        <div class="text-center border-l border-cyan-400">
                            <p class="text-cyan-100 text-xs font-semibold uppercase tracking-widest mb-2">Sections</p>
                            <p class="text-xl md:text-2xl font-bold">{{ $sections->count() }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
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
