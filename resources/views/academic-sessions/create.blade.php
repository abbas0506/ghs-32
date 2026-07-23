@extends('layouts.app')

@section('page-content')
    <div class="space-y-6 pb-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between py-2 border-b border-slate-100 pb-3">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-0.5">
                    <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <a href="{{ route('academic-sessions.index') }}" class="hover:text-indigo-600 transition-colors">Academic Sessions</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-indigo-600">Create</span>
                </div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight leading-none">Create Session</h1>
            </div>
            <a href="{{ route('academic-sessions.index') }}" class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                <i class="bi-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-800 px-4 py-3 rounded-xl text-xs font-bold">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Container -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-[0_4px_20px_rgba(0,0,0,0.01)] max-w-xl">
            <form action="{{ route('academic-sessions.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Session Name -->
                <div class="space-y-1.5">
                    <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Session Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. 2025-2026" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-all" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Start Date -->
                    <div class="space-y-1.5">
                        <label for="start_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 transition-all" required>
                    </div>

                    <!-- End Date -->
                    <div class="space-y-1.5">
                        <label for="end_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 transition-all" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- FTF Opening Balance -->
                    <div class="space-y-1.5">
                        <label for="ftf_start" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">FTF Opening Balance (PKR)</label>
                        <input type="number" name="ftf_start" id="ftf_start" value="{{ old('ftf_start', 0) }}" min="0" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 transition-all" required>
                    </div>

                    <!-- NSB Opening Budget -->
                    <div class="space-y-1.5">
                        <label for="nsb_start" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">NSB Allocated Budget (PKR)</label>
                        <input type="number" name="nsb_start" id="nsb_start" value="{{ old('nsb_start', 0) }}" min="0" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 focus:outline-none focus:border-indigo-500 transition-all" required>
                    </div>
                </div>

                <!-- Is Current Session Toggle -->
                <div class="flex items-center gap-3 py-2">
                    <input type="checkbox" name="is_current" id="is_current" value="1" {{ old('is_current') ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <label for="is_current" class="text-xs font-semibold text-slate-660 select-none cursor-pointer">Mark as Current active session</label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-md">
                        Save Academic Session
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
