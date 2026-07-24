@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-6">
        {{-- Header --}}
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('grants.index') }}" class="text-slate-400 hover:text-teal-600 text-xs transition-colors" title="Back to Grants">
                    <i class="bi bi-arrow-left text-sm font-bold"></i>
                </a>
                <span class="text-xs font-extrabold text-slate-800 tracking-tight uppercase">New Grant Type</span>
            </div>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @endif

        <form action="{{ route('grants.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 space-y-3">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Grant Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                        placeholder="e.g. Computer Lab Infrastructure Grant" required>
                </div>

                {{-- Issued By --}}
                <div>
                    <label for="issued_by" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Issued By</label>
                    <input type="text" id="issued_by" name="issued_by" value="{{ old('issued_by') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition"
                        placeholder="e.g. Punjab Education Foundation">
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Notes</label>
                    <textarea id="description" name="description" rows="2"
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-transparent outline-none transition resize-none"
                        placeholder="Any additional notes about this grant...">{{ old('description') }}</textarea>
                </div>

                <button type="submit"
                    class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 shadow">
                    <i class="bi bi-check-circle-fill text-sm"></i>
                    Save Grant
                </button>
            </div>
        </form>
    </div>
@endsection
