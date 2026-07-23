@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-6">
        <!-- Minimal Header Section -->
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('nsb-receipts.index') }}" class="text-slate-400 hover:text-indigo-600 text-xs transition-colors" title="Back">
                    <i class="bi bi-arrow-left text-sm font-bold"></i>
                </a>
                <span class="text-xs font-extrabold text-slate-800 tracking-tight uppercase">New NSB Receipt</span>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        @php
            $currentSession = \App\Models\AcademicSession::current();
        @endphp

        <!-- Form Card -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm max-w-md mx-auto overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-50 bg-slate-50/50">
                <h3 class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">NSB Receipt Details</h3>
                <p class="text-[9px] text-slate-400 mt-0.5">Session: <span class="font-bold text-indigo-600">{{ $currentSession ? $currentSession->name : 'None (Activate first!)' }}</span></p>
            </div>

            <form action="{{ route('nsb-receipts.store') }}" method="POST" class="p-4 space-y-3">
                @csrf

                <!-- Quarter Selection -->
                <div class="space-y-1">
                    <label for="quarter" class="text-[10px] font-bold text-slate-550 block">Quarter <span class="text-rose-500">*</span></label>
                    <select name="quarter" id="quarter" class="w-full text-xs border border-slate-200 rounded-lg p-2 outline-none focus:border-indigo-500 transition-all bg-white" required>
                        <option value="">-- Select Quarter --</option>
                        <option value="1" {{ old('quarter') == 1 ? 'selected' : '' }}>Q1 (First Quarter)</option>
                        <option value="2" {{ old('quarter') == 2 ? 'selected' : '' }}>Q2 (Second Quarter)</option>
                        <option value="3" {{ old('quarter') == 3 ? 'selected' : '' }}>Q3 (Third Quarter)</option>
                        <option value="4" {{ old('quarter') == 4 ? 'selected' : '' }}>Q4 (Fourth Quarter)</option>
                    </select>
                </div>

                <!-- Amount (Rs.) -->
                <div class="space-y-1">
                    <label for="amount" class="text-[10px] font-bold text-slate-550 block">Amount (Rs.) <span class="text-rose-500">*</span></label>
                    <div class="relative rounded-lg overflow-hidden">
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" placeholder="Enter amount" min="1" class="w-full text-xs border border-slate-200 rounded-lg p-2 outline-none focus:border-indigo-500 transition-all" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-[9px] font-bold uppercase">PKR</div>
                    </div>
                </div>

                <!-- Received Date -->
                <div class="space-y-1">
                    <label for="received_date" class="text-[10px] font-bold text-slate-550 block">Received Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="received_date" id="received_date" value="{{ old('received_date') }}" class="w-full text-xs border border-slate-200 rounded-lg p-2 outline-none focus:border-indigo-500 transition-all" required>
                </div>

                <!-- Description / Notes -->
                <div class="space-y-1">
                    <label for="description" class="text-[10px] font-bold text-slate-550 block">Description / Notes</label>
                    <textarea name="description" id="description" rows="2" placeholder="Enter notes or description..." class="w-full text-xs border border-slate-200 rounded-lg p-2 outline-none focus:border-indigo-500 transition-all">{{ old('description') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-1">
                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold shadow transition-all duration-200">
                        <i class="bi bi-check-circle"></i> Save NSB Receipt
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
