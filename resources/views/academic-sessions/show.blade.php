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
                    <span class="text-indigo-600">Details</span>
                </div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight leading-none">Session Details</h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('academic-sessions.edit', $session->id) }}" class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                    <i class="bi-pencil"></i>
                    <span>Edit</span>
                </a>
                <a href="{{ route('academic-sessions.index') }}" class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                    <i class="bi-arrow-left"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>

        <!-- Session Overview Card -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-[0_4px_20px_rgba(0,0,0,0.01)] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800 mb-1">Academic Session: {{ $session->name }}</h2>
                <p class="text-xs font-medium text-slate-500">
                    Duration: {{ $session->start_date->format('M d, Y') }} — {{ $session->end_date->format('M d, Y') }}
                </p>
            </div>
            <div>
                @if($session->is_current)
                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-xl">
                        <i class="bi-check-circle-fill"></i> Current Active Session
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-slate-50 text-slate-400 border border-slate-100 text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-xl">
                        Inactive
                    </span>
                @endif
            </div>
        </div>

        <!-- Section Heading: FTF -->
        <div class="pt-2">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Farogh-e-Taleem Fund (FTF)</h3>
        </div>

        <!-- FTF Detailed Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- FTF Start -->
            <div class="bg-gradient-to-br from-indigo-50/20 to-white p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-28 shadow-sm">
                <div>
                    <h4 class="text-slate-450 text-[9px] font-extrabold uppercase tracking-wider mb-1">FTF Opening Balance</h4>
                    <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ number_format($session->ftf_start) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                </div>
                <div class="text-[9px] font-semibold text-slate-400">Opening balance before session start</div>
            </div>

            <!-- FTF Collection Box -->
            <div class="bg-gradient-to-br from-emerald-50/20 to-white p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-28 shadow-sm">
                <div>
                    <h4 class="text-slate-450 text-[9px] font-extrabold uppercase tracking-wider mb-1">FTF Collection</h4>
                    <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ number_format($session->ftf_collection) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                </div>
                <div class="text-[9px] font-semibold text-emerald-600">Total FTF collected from vouchers</div>
            </div>

            <!-- FTF Expenses Box -->
            <div class="bg-gradient-to-br from-rose-50/20 to-white p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-28 shadow-sm">
                <div>
                    <h4 class="text-slate-450 text-[9px] font-extrabold uppercase tracking-wider mb-1">FTF Expenses</h4>
                    <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ number_format($session->ftf_expenses) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                </div>
                <div class="text-[9px] font-semibold text-rose-650">Total FTF expenses during session</div>
            </div>
        </div>

        <!-- FTF Net Balance display box -->
        <div class="relative overflow-hidden bg-gradient-to-r from-emerald-50/20 via-white to-white p-5 rounded-2xl border border-emerald-100 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h4 class="text-slate-450 text-[9px] font-black uppercase tracking-widest mb-1.5">Net FTF Balance</h4>
                    <p class="text-2xl font-black text-emerald-600 leading-none tracking-tight">
                        {{ number_format($session->ftf_balance) }} <span class="text-xs font-bold text-slate-500">PKR</span>
                    </p>
                </div>
                <div class="max-w-xs">
                    <p class="text-[9px] text-slate-450 font-bold leading-relaxed">
                        Calculated as: `FTF Start` + `FTF Collection` - `FTF Expenses` during this academic session.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section Heading: NSB -->
        <div class="pt-4">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Non-Salary Budget (NSB)</h3>
        </div>

        <!-- NSB Detailed Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- NSB Start (Budget) -->
            <div class="bg-gradient-to-br from-indigo-50/20 to-white p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-28 shadow-sm">
                <div>
                    <h4 class="text-slate-455 text-[9px] font-extrabold uppercase tracking-wider mb-1">NSB Allocated Budget</h4>
                    <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ number_format($session->nsb_start) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                </div>
                <div class="text-[9px] font-semibold text-slate-400">Total NSB budget allocation</div>
            </div>

            <!-- NSB Receipts Box -->
            <div class="bg-gradient-to-br from-amber-50/20 to-white p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-28 shadow-sm">
                <div>
                    <h4 class="text-slate-455 text-[9px] font-extrabold uppercase tracking-wider mb-1">NSB Receipts Received</h4>
                    <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ number_format($session->nsb_collection) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                </div>
                <div class="text-[9px] font-semibold text-amber-600">Total received quarterly NSB funds</div>
            </div>

            <!-- NSB Expenses Box -->
            <div class="bg-gradient-to-br from-rose-50/20 to-white p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-28 shadow-sm">
                <div>
                    <h4 class="text-slate-455 text-[9px] font-extrabold uppercase tracking-wider mb-1">NSB Expenses</h4>
                    <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ number_format($session->nsb_expenses) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                </div>
                <div class="text-[9px] font-semibold text-rose-650">Total NSB expenses during session</div>
            </div>
        </div>

        <!-- NSB Net Balance display box -->
        <div class="relative overflow-hidden bg-gradient-to-r from-amber-50/20 via-white to-white p-5 rounded-2xl border border-amber-100 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h4 class="text-slate-455 text-[9px] font-black uppercase tracking-widest mb-1.5">Net NSB Balance</h4>
                    <p class="text-2xl font-black text-amber-650 leading-none tracking-tight">
                        {{ number_format($session->nsb_balance) }} <span class="text-xs font-bold text-slate-500">PKR</span>
                    </p>
                </div>
                <div class="max-w-xs">
                    <p class="text-[9px] text-slate-455 font-bold leading-relaxed">
                        Calculated as: `NSB Start` + `NSB Receipts` - `NSB Expenses` during this academic session.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section Heading: Special Grants -->
        <div class="pt-4">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Special Grants</h3>
        </div>

        <!-- Special Grants Detailed Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Special Grants Start -->
            <div class="bg-gradient-to-br from-indigo-50/20 to-white p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-28 shadow-sm">
                <div>
                    <h4 class="text-slate-455 text-[9px] font-extrabold uppercase tracking-wider mb-1">Grants Opening Balance</h4>
                    <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ number_format($session->special_grants_start) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                </div>
                <div class="text-[9px] font-semibold text-slate-400">Opening grants allocation</div>
            </div>

            <!-- Special Grants Collection -->
            <div class="bg-gradient-to-br from-indigo-50/20 to-white p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-28 shadow-sm">
                <div>
                    <h4 class="text-slate-455 text-[9px] font-extrabold uppercase tracking-wider mb-1">Grants Received</h4>
                    <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ number_format($session->special_grants_collection) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                </div>
                <div class="text-[9px] font-semibold text-indigo-650">Total ad-hoc grants received</div>
            </div>

            <!-- Special Grants Expenses -->
            <div class="bg-gradient-to-br from-rose-50/20 to-white p-5 rounded-2xl border border-slate-100 flex flex-col justify-between h-28 shadow-sm">
                <div>
                    <h4 class="text-slate-455 text-[9px] font-extrabold uppercase tracking-wider mb-1">Grants Expenses</h4>
                    <p class="text-lg font-black text-slate-800 leading-none tracking-tight">{{ number_format($session->special_grants_expenses) }} <span class="text-[10px] font-bold text-slate-500">PKR</span></p>
                </div>
                <div class="text-[9px] font-semibold text-rose-650">Total grant expenses during session</div>
            </div>
        </div>

        <!-- Special Grants Net Balance display box -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-50/20 via-white to-white p-5 rounded-2xl border border-indigo-100 shadow-sm">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h4 class="text-slate-455 text-[9px] font-black uppercase tracking-widest mb-1.5">Net Special Grants Balance</h4>
                    <p class="text-2xl font-black text-indigo-655 leading-none tracking-tight">
                        {{ number_format($session->special_grants_balance) }} <span class="text-xs font-bold text-slate-500">PKR</span>
                    </p>
                </div>
                <div class="max-w-xs">
                    <p class="text-[9px] text-slate-455 font-bold leading-relaxed">
                        Calculated as: `Grants Start` + `Grants Received` - `Grants Expenses` during this academic session.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
