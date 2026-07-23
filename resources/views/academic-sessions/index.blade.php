@extends('layouts.app')

@section('page-content')
    <div class="space-y-6 pb-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between py-2 border-b border-slate-100 pb-3">
            <div>
                <div class="flex items-center gap-2 text-slate-400 text-[9px] uppercase tracking-[0.1em] font-bold mb-0.5">
                    <a href="{{ url('/') }}" class="hover:text-indigo-600 transition-colors">School</a>
                    <i class="bi-chevron-right text-[8px]"></i>
                    <span class="text-indigo-600">Academic Sessions</span>
                </div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight leading-none">Academic Sessions</h1>
            </div>
            <a href="{{ route('academic-sessions.create') }}" class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all shadow-sm">
                <i class="bi-plus-lg"></i>
                <span>Add Session</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3 rounded-xl text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Session List Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.01)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="p-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Name</th>
                            <th class="p-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Start Date</th>
                            <th class="p-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">End Date</th>
                            <th class="p-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">FTF Start</th>
                            <th class="p-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">NSB Start</th>
                            <th class="p-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                            <th class="p-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-bold text-slate-800">{{ $session->name }}</div>
                                </td>
                                <td class="p-4 text-xs font-medium text-slate-600">
                                    {{ $session->start_date->format('M d, Y') }}
                                </td>
                                <td class="p-4 text-xs font-medium text-slate-600">
                                    {{ $session->end_date->format('M d, Y') }}
                                </td>
                                <td class="p-4 text-xs font-bold text-slate-800 text-right">
                                    {{ number_format($session->ftf_start) }} PKR
                                </td>
                                <td class="p-4 text-xs font-bold text-slate-800 text-right">
                                    {{ number_format($session->nsb_start) }} PKR
                                </td>
                                <td class="p-4 text-center">
                                    @if($session->is_current)
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-100 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md">
                                            Current
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-slate-50 text-slate-400 border border-slate-100 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('academic-sessions.show', $session->id) }}" class="p-1.5 bg-slate-50 text-slate-400 hover:text-indigo-650 hover:bg-indigo-50 border border-slate-100 rounded-lg transition-all" title="View Details">
                                            <i class="bi bi-eye-fill text-xs"></i>
                                        </a>
                                        <a href="{{ route('academic-sessions.edit', $session->id) }}" class="p-1.5 bg-slate-50 text-slate-400 hover:text-amber-650 hover:bg-amber-50 border border-slate-100 rounded-lg transition-all" title="Edit Session">
                                            <i class="bi bi-pencil-fill text-xs"></i>
                                        </a>
                                        <form action="{{ route('academic-sessions.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-slate-50 text-slate-400 hover:text-rose-650 hover:bg-rose-50 border border-slate-100 rounded-lg transition-all" title="Delete Session">
                                                <i class="bi bi-trash-fill text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    No academic sessions registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
