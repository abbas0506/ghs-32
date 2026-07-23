@extends('layouts.app')
@section('page-content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Edit Payment: {{ $student->name }}</h2>
    </div>

    <div class="bread-crumb">
        <a href="/">Home</a>
        <div>/</div>
        <a href="{{ route('ftf-vouchers.index') }}">Vouchers</a>
        <div>/</div>
        <a href="{{ route('ftf-vouchers.show', $voucher) }}">Details</a>
        <div>/</div>
        <a href="{{ route('ftf-voucher.section.payments.index', [$voucher, $section]) }}">Payments</a>
        <div>/</div>
        <div>Edit</div>
    </div>

    <div class="md:w-1/2 mx-auto bg-white p-6 md:p-10 rounded-2xl border border-gray-100 shadow-sm mt-8">
        <form action="{{ route('ftf-voucher.section.payments.update', [$voucher, $section, $fee]) }}" method="post">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">Payment Date</label>
                <input type="date" name="payment_date" value="{{ $fee->payment_date ? $fee->payment_date->format('Y-m-d') : '' }}" 
                    class="w-full border-gray-200 rounded-xl focus:border-teal-400 focus:ring-teal-400">
                <p class="text-[10px] text-slate-400 mt-1">Leave empty to mark as unpaid.</p>
            </div>

            <div class="mt-8 flex justify-end space-x-2">
                <a href="{{ route('ftf-voucher.section.payments.index', [$voucher, $section]) }}" class="px-6 py-2 text-slate-400 text-[9px] font-bold uppercase">Cancel</a>
                <button type="submit" class="bg-teal-600 text-white px-8 py-2 rounded-xl font-bold uppercase text-xs hover:bg-teal-700 transition-all">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
@endsection
