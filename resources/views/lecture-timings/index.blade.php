@extends('layouts.app')
@section('page-content')
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Lecture Timings</h1>
            <div class="bread-crumb mt-1">
                <a href="{{ url('/') }}">Home</a>
                <div>/</div>
                <a href="{{ route('config') }}">Config</a>
                <div>/</div>
                <span class="text-gray-500">Lecture Timings</span>
            </div>
        </div>
        <a href="{{ route('lecture-timings.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-teal-500 to-green-500 text-white text-sm font-semibold rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
            <i class="ri-add-line"></i> Add New Timing
        </a>
    </div>

    <div class="md:w-11/12 lg:w-3/4 xl:w-2/3 mx-auto">
        <x-message></x-message>

        <div class="bg-white rounded shadow-sm border border-gray-100 overflow-auto">
            <table class="table-fixed borderless xs w-full">
                <thead class="data">
                    <tr>
                        <th class="w-12">Lec #</th>
                        <th class="w-24">Starts At</th>
                        <th class="w-24">Duration</th>
                        <th class="w-24">End Time</th>
                        <th class="w-24 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="data">
                    @forelse ($lectures as $lecture)
                        <tr class="tr">
                            <td class="">
                                <a href="{{ route('lecture-timings.edit', $lecture) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-teal-100 text-teal-600 font-bold">
                                    {{ $lecture->lecture_no }}
                                </a>
                            </td>
                            <td class="">
                                {{ $lecture->starts_at->format('h:i A') }}
                            </td>
                            <td class="">
                                {{ $lecture->duration }} mins
                            </td>
                            <td class="">
                                {{ $lecture->starts_at->copy()->addMinutes($lecture->duration)->format('h:i A') }}
                            </td>
                            <td class="">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('lecture-timings.edit', $lecture) }}"
                                        class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form action="{{ route('lecture-timings.destroy', $lecture) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this timing?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="ri-time-line text-4xl block mb-2 opacity-20"></i>
                                No lecture timings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
