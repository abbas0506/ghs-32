@extends('layouts.app')
@section('page-content')
    <h2>New Fee Voucher</h2>
    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('fee-vouchers.index') }}">Fee Vouchers</a>
        <div>/</div>
        <div>New</div>
    </div>

    <div
        class="w-full md:w-4/5 mx-auto flex items-center gap-3 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50 mt-8">
        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-teal-100 text-teal-600">
            <i class="ri-money-rupee-circle-line text-lg"></i>
        </div>
        <div>
            <h2 class="font-semibold text-gray-800 text-sm leading-tight">New Fee Voucher</h2>
            <p class="text-xs text-gray-400">Select atleast one class</p>
        </div>
    </div>

    <div class="w-full md:w-4/5 mx-auto p-4 md:p-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- page message -->
        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif
        <form action="{{ route('fee-vouchers.store') }}" method='post' class="w-full grid gap-6"
            onsubmit="return validate(event)">
            @csrf
            <div class="grid md:grid-cols-2 gap-3">
                <div class="md:col-span-2">
                    <label>Description / Title</label>
                    <input type="text" name='description' class="custom-input" placeholder="Describe fee voucher"
                        value="" required>
                </div>
                <div>
                    <label>Amount *</label>
                    <input type="number" name='amount' class="custom-input" placeholder="Amount" value="" required>
                </div>
                <div>
                    <label>Due Date</label>
                    <input type="date" name='due_date' class="custom-input text-center" placeholder="Due date" required>

                </div>
            </div>
            <h2 class="text-sm text-teal-600 mt-6"><i class="ri-bubble-chart-line mr-2"></i>List of available sections
            </h2>
            <div>
                @foreach ($sections as $section)
                    <div class="flex items-center odd:bg-slate-100 checkable-row px-4">
                        <!-- <div class="flex flex-1 items-center justify-between space-x-2 pr-3"> -->
                        <label for='section{{ $section->id }}'
                            class="flex-1 text-sm text-slate-800 hover:cursor-pointer py-2">{{ $section->name }}
                        </label>
                        <!-- </div> -->
                        <div class="text-base">
                            <input type="checkbox" id='section{{ $section->id }}' name='section_ids_array[]'
                                class="custom-input w-4 h-4 rounded hidden" value="{{ $section->id }}">
                            <i class="bx bx-check"></i>
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="btn-teal rounded-lg p-2 w-32 mt-5">Create Now</button>
            </div>
        </form>

    </div>
    </div>
@endsection
@section('script')
    <script type="module">
        $('.checkable-row input').change(function() {
            if ($(this).prop('checked'))
                $(this).parents('.checkable-row').addClass('active')
            else
                $(this).parents('.checkable-row').removeClass('active')
        })

        $('#check_all').change(function() {
            if ($(this).prop('checked')) {
                $('.checkable-row input').each(function() {
                    $(this).prop('checked', true)
                    $(this).parents('.checkable-row').addClass('active')
                })
            } else {
                $('.checkable-row input').each(function() {
                    $(this).prop('checked', false)
                    $(this).parents('.checkable-row').removeClass('active')
                })
            }
        })
    </script>
@endsection
