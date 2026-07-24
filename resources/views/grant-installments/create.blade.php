@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-6">
        {{-- Header --}}
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('special-grants.show', $specialGrant->id) }}" class="text-slate-400 hover:text-teal-600 text-xs transition-colors" title="Back to Grant">
                    <i class="bi bi-arrow-left text-sm font-bold"></i>
                </a>
                <span class="text-xs font-extrabold text-slate-800 tracking-tight uppercase">Add Installment</span>
            </div>
        </div>

        {{-- Grant Context (Teal Theme) --}}
        <div class="bg-teal-50 border border-teal-100 rounded-lg px-3 py-2">
            <p class="text-[8px] font-bold uppercase text-teal-400 tracking-wider">Grant</p>
            <p class="text-[11px] font-bold text-teal-800">{{ $specialGrant->title }}</p>
            @if ($specialGrant->issued_by)
                <p class="text-[9px] text-teal-600">by {{ $specialGrant->issued_by }}</p>
            @endif
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @endif

        <form action="{{ route('special-grants.installments.store', $specialGrant->id) }}" method="POST" class="space-y-4">
            @csrf

            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 space-y-3">
                {{-- Amount --}}
                <div>
                    <label for="amount" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Amount (PKR) <span class="text-red-500">*</span></label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" min="1"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                        placeholder="e.g. 50000" required>
                </div>

                {{-- Received Date --}}
                <div>
                    <label for="received_date" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Received Date <span class="text-red-500">*</span></label>
                    <input type="date" id="received_date" name="received_date" value="{{ old('received_date', $session?->start_date?->toDateString()) }}"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                        required>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Notes</label>
                    <input type="text" id="description" name="description" value="{{ old('description') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                        placeholder="Optional note about this installment">
                </div>

                <button type="submit"
                    class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 shadow">
                    <i class="bi bi-check-circle-fill text-sm"></i>
                    Save Installment
                </button>
            </div>
        </form>
    </div>
@endsection
