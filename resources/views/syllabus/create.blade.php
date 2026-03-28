@extends('layouts.app')
@section('page-content')
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Syllabus:Add Subject</h1>
        <div class="bread-crumb mt-1">
            <a href="{{ url('/') }}">Home</a>
            <div>/</div>
            <a href="{{ route('syllabi.index') }}">Syllabus</a>
            <div>/</div>
            <span class="text-gray-500">Add Subject</span>
        </div>
    </div>

    <div class="w-full md:w-4/5 mx-auto space-y-5">

        {{-- Flash Messages --}}
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        @if ($grade)
            {{-- Active filter pills --}}
            <div class="flex items-center gap-3 flex-wrap">
                <span
                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 border border-teal-200 text-teal-700 rounded-full text-sm font-medium">
                    <i class="ri-school-line text-teal-500"></i>
                    {{ $grade->name ?? 'N/A' }}
                </span>

                <a href="{{ route('syllabi.index') }}"
                    class="ml-2 text-xs text-gray-400 underline hover:text-gray-600 transition">Change</a>

            </div>
        @else
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                <div class="w-20 h-20 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                    <i class="ri-file-list-3-line text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-600 mb-1">Grade not selected</h3>
                <p class="text-sm text-gray-400 mb-6 max-w-xs">
                    Probably this page has been accessed via direct url
                </p>
                @role('head|admin')
                    <a href="{{ route('syllabi.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-500 to-green-500 text-white text-sm font-semibold rounded-xl shadow hover:from-teal-600 hover:to-green-600 transition">
                        <i class="ri-add-circle-line text-base"></i>
                        Select Grade
                    </a>
                @endrole

            </div>
        @endif
        {{-- Form Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50 rounded-t-2xl">
                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="ri-book-open-line text-teal-500"></i>
                    + Add Subject
                </h2>
            </div>

            <div>
                {{-- Subject that can be added --}}
                <form action="{{ route('syllabi.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="grade_id" value="{{ $grade->id }}">
                    <table class="table-fixed borderless w-full text-sm xs md:sm">
                        <thead class="">
                            <tr>
                                <th class="w-16">#</th>
                                <th class="w-40 text-left">Subject</th>
                                <th class="w-16"><input type="checkbox" id='chkAll' class="rounded"
                                        onclick="checkAll()"><br><label for="">Check all</label></th>
                                </th>

                            </tr>
                        </thead>


                        @if ($subjects->count())
                            <tbody>

                                @foreach ($subjects as $subject)
                                    <tr class="tr">
                                        <td>
                                            <div class="ico teal mx-auto">{{ $loop->index + 1 }}</div>
                                        </td>
                                        <td class="text-left">{{ $subject->name }}</td>
                                        <td>
                                            <input type="checkbox" class="w-4 h-4 rounded" name="subject_ids_array[]"
                                                value="{{ $subject->id }}">
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                    <div class="flex justify-center my-5">

                        <button type="submit" class="btn-blue rounded px-3 py-1"><i class="bi-plus-circle"></i> Add
                            Now</button>
                    </div>
                </form>

            </div>

        </div>
    @endsection
