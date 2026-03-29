@extends('layouts.app')
@section('page-content')
    {{-- Page Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Syllabus</h1>
            <div class="bread-crumb mt-1">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <a href="{{ route('syllabi.index') }}">Syllabus</a>
                <div>/</div>
                <a href="{{ route('syllabi.index', ['grade_id' => $syllabus->grade_id]) }}"
                    class="text-teal-600 hover:underline">
                    {{ $syllabus->grade?->name }}
                </a>
                <div>/</div>
                <div>{{ $syllabus->subject->name }}</div>
            </div>
        </div>
    </div>

    <div class="md:w-11/12 lg:w-3/4 xl:w-2/3 mx-auto space-y-5">

        {{-- Flash Messages --}}
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Context Banner --}}
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4 flex items-center justify-between flex-wrap gap-4">

            <div class="flex items-center gap-3">
                <div
                    class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-400 to-green-400 text-white text-2xl font-bold shadow">
                    <i class="bi-layers"></i>
                </div>
                {{ $syllabus->grade?->name }}-{{ $syllabus->subject?->name }}

            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('syllabi.edit', $syllabus->id) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-teal-500 to-green-500 rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
                    <i class="ri-pencil-line"></i> Edit
                </a>
                <form action="{{ route('syllabi.destroy', $syllabus->id) }}" method="POST" id="deleteForm" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-red-500 bg-red-50 border border-red-200 rounded-xl hover:bg-red-100 transition">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Detail Cards --}}
        <div class="grid bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-200">

            {{-- Term 1 --}}
            <div class="px-6 py-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-teal-100 text-teal-600">
                        <i class="ri-focus-3-line text-sm"></i>
                    </span>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">1st Term</h3>
                </div>
                @if ($syllabus->term1)
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $syllabus->term1 }}</p>
                @else
                    <p class="text-sm text-gray-300 italic">Not specified</p>
                @endif
            </div>

            {{-- Term 2 --}}
            <div class="px-6 py-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-teal-100 text-teal-600">
                        <i class="ri-focus-3-line text-sm"></i>
                    </span>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Second Term</h3>
                </div>
                @if ($syllabus->term2)
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $syllabus->term2 }}</p>
                @else
                    <p class="text-sm text-gray-300 italic">Not specified</p>
                @endif
            </div>

            {{-- Term 3 --}}
            <div class="px-6 py-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-teal-100 text-teal-600">
                        <i class="ri-focus-3-line text-sm"></i>
                    </span>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">3rd Term</h3>
                </div>
                @if ($syllabus->term3)
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $syllabus->term3 }}</p>
                @else
                    <p class="text-sm text-gray-300 italic">Not specified</p>
                @endif
            </div>

        </div>

    </div>


    </div>

    </div>
@endsection

@section('script')
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Delete this plan?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>
@endsection
