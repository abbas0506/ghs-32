@extends('layouts.app')
@section('page-content')
    <h2>New Allocation</h2>
    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('section.lecture.schedule.index', [0, 0]) }}">Allocations</a>
        <div>/</div>
        <div>New</div>
    </div>

    <div class="md:w-3/4 mx-auto mt-6 bg-white md:p-8 rounded">
        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        <form action="{{ route('section.lecture.schedule.store', [$section, $lecture_no]) }}" method='post'
            class="grid grid-cols-1 md:grid-cols-2 w-full gap-5 p-5" onsubmit="return validate(event)">
            @csrf
            <input type="hidden" name="session_id" value="1">
            <div class="col-span-full">
                <h1 class="font-bold">Class: {{ $section->name }}</h1>
                <h2><i class="bi-clock mr-2"></i>Lecture # {{ $lecture_no }}</h2>
                <div class="divider my-2"></div>
            </div>
            <div class="grid gap-5 p-8 md:px-12 place-items-center border rounded-lg">
                <div class="h-12 w-12 rounded-full border flex justify-center items-center">
                    <i class="bx bx-book"></i>
                </div>
                <select name="subject_id" id="" class="custom-input-borderless">
                    @foreach ($subjects->sortBy('name') as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-5 p-8 md:px-12 place-items-center border rounded-lg">
                <div class="h-12 w-12 rounded-full border flex justify-center items-center">
                    <i class="bx bx-user"></i>
                </div>
                <select name="user_id" id="" class="custom-input-borderless">
                    @foreach ($users->sortBy('seniority') as $user)
                        <option value="{{ $user->id }}">{{ $user->profile->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Days (Mon–Sat = 1–6) --}}
            <div class="">
                <label class="block mb-2">Select Days</label>
                <div class="flex gap-3">
                    @php $days = ['M','T','W','T','F','S']; @endphp
                    @foreach ($days as $i => $day)
                        <div class="day-circle bg-gray-200 text-gray-700 w-8 h-8 flex gap-2 items-center justify-center rounded-full cursor-pointer select-none transition"
                            data-day="{{ $i + 1 }}" title="Day {{ $i + 1 }}">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                {{-- Hidden field to store formatted selection --}}
                <input type="hidden" name="days" id="selectedDays">
            </div>
            <div class="flex justify-end items-center">
                <button type="submmit" class="btn-teal rounded p-2 w-32">Create Now</button>

            </div>
        </form>

    </div>
@endsection
@section('script')
    <script type="module">
        $(function() {
            let selectedDays = [];

            function formatDays(days) {
                // returns "1-3,5-6,8" style string
                if (!days.length) return "";
                // unique + sorted
                const sorted = [...new Set(days)].sort((a, b) => a - b);
                const parts = [];
                let start = sorted[0],
                    prev = sorted[0];

                for (let i = 1; i < sorted.length; i++) {
                    const cur = sorted[i];
                    if (cur === prev + 1) {
                        // still in a consecutive run
                        prev = cur;
                    } else {
                        // close previous run
                        parts.push(start === prev ? String(start) : `${start}-${prev}`);
                        // start a new run
                        start = prev = cur;
                    }
                }
                // close last run
                parts.push(start === prev ? String(start) : `${start}-${prev}`);

                return parts.join(',');
            }

            function updateHidden() {
                $('#selectedDays').val(formatDays(selectedDays));
            }

            $('.day-circle').on('click', function() {
                const day = parseInt($(this).data('day'), 10);
                const idx = selectedDays.indexOf(day);

                if (idx > -1) {
                    // remove
                    selectedDays.splice(idx, 1);
                    $(this).removeClass('bg-blue-500 text-white').addClass('bg-gray-200 text-gray-700');
                } else {
                    // add
                    selectedDays.push(day);
                    $(this).removeClass('bg-gray-200 text-gray-700').addClass('bg-blue-500 text-white');
                }

                updateHidden();
            });
        });
    </script>
@endsection
