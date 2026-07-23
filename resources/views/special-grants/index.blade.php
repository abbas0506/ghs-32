@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-6">
        {{-- Minimal Header Section (Teal Theme) --}}
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('finance.index') }}" class="text-slate-400 hover:text-teal-600 text-xs transition-colors" title="Back to Finance">
                    <i class="bi bi-arrow-left text-sm font-bold"></i>
                </a>
                <span class="text-xs font-extrabold text-slate-800 tracking-tight uppercase">Special Grants</span>
            </div>
            <a href="{{ route('special-grants.create') }}"
                class="px-2.5 py-1 text-[10px] bg-teal-50 hover:bg-teal-100 text-teal-700 rounded-lg font-bold transition-all shadow-sm">
                + Add Grant
            </a>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Session Balance Summary Card (Teal Theme) --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-teal-500 to-emerald-600 text-white p-4 rounded-xl border border-teal-400/20 shadow-sm flex flex-col gap-2">
            <div class="absolute -right-10 -bottom-10 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="space-y-0.5 relative z-10">
                <p class="text-[8px] font-black uppercase tracking-wider text-teal-100/80">Active Session · Special Grants</p>
                <h2 class="text-sm font-extrabold tracking-tight">
                    Session: {{ $session ? $session->name : 'No Active Session' }}
                </h2>
                @if ($session)
                    <p class="text-[9px] text-teal-100/90">
                        Opening Balance: <span class="font-bold text-white">{{ number_format($session->special_grants_start) }} PKR</span>
                        &nbsp;|&nbsp;
                        Balance: <span class="font-bold text-emerald-100">{{ number_format($session->special_grants_balance) }} PKR</span>
                    </p>
                @endif
            </div>
        </div>

        {{-- Special Grants Table --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-3 py-2 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider">Grant Records</h3>
                <span class="px-1.5 py-0.5 bg-slate-200/60 rounded text-[8px] font-bold text-slate-655">
                    {{ $grants->count() }} records
                </span>
            </div>

            @if ($grants->count() == 0)
                <div class="flex flex-col items-center justify-center py-8 px-4 text-center">
                    <i class="bi bi-gift text-lg text-slate-400 mb-2"></i>
                    <h4 class="text-xs font-bold text-slate-755">No Special Grants Found</h4>
                    <p class="text-[9px] text-slate-400 mt-1">Create a grant record to start tracking installments.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/20">
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left">Title</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left">Issued By</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left w-[80px]">Installments</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left w-[110px]">Total Amount</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($grants as $grant)
                                <tr class="hover:bg-slate-50/60 transition-colors duration-150 text-xs text-slate-655">
                                    <td class="py-2.5 px-3 font-bold text-slate-800 text-xs">
                                        {{ $grant->title }}
                                    </td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-600 text-[11px]">
                                        {{ $grant->issued_by ?? '-' }}
                                    </td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-600 text-xs">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-teal-50 text-teal-700">
                                            {{ $grant->installments_count }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800 text-xs">
                                        {{ number_format($grant->installments->sum('amount')) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <div class="flex items-center justify-end gap-2.5">
                                            <a href="{{ route('special-grants.show', $grant->id) }}"
                                                class="text-slate-400 hover:text-teal-650 transition-colors"
                                                title="View Installments">
                                                <i class="bi bi-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('special-grants.edit', $grant->id) }}"
                                                class="text-slate-400 hover:text-teal-650 transition-colors"
                                                title="Edit">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </a>
                                            <form id="delete-form-{{ $grant->id }}"
                                                action="{{ route('special-grants.destroy', $grant->id) }}"
                                                method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="confirmDelete(event, {{ $grant->id }})"
                                                    class="text-red-500 hover:text-red-750 transition-colors p-0.5"
                                                    title="Delete">
                                                    <i class="bi bi-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        function confirmDelete(event, id) {
            event.preventDefault();
            Swal.fire({
                title: 'Delete Grant?',
                text: "This will also delete all its installments. Cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed || result.value) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
