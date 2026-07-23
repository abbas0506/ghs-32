@extends('layouts.app')

@section('page-content')
    <div class="space-y-4 pb-6">
        {{-- Header Section (Teal Theme) --}}
        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 pb-2">
            <div class="flex items-center gap-2">
                <a href="{{ route('special-grants.index') }}" class="text-slate-400 hover:text-teal-600 text-xs transition-colors" title="Back to Grants">
                    <i class="bi bi-arrow-left text-sm font-bold"></i>
                </a>
                <span class="text-xs font-extrabold text-slate-800 tracking-tight uppercase truncate max-w-[160px]">{{ $specialGrant->title }}</span>
            </div>
            <a href="{{ route('special-grants.installments.create', $specialGrant->id) }}"
                class="px-2.5 py-1 text-[10px] bg-teal-50 hover:bg-teal-100 text-teal-700 rounded-lg font-bold transition-all shadow-sm">
                + Add Installment
            </a>
        </div>

        @if ($errors->any())
            <x-message :errors='$errors'></x-message>
        @else
            <x-message></x-message>
        @endif

        {{-- Grant Info Card (Teal Theme) --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-teal-500 to-emerald-600 text-white p-4 rounded-xl border border-teal-400/20 shadow-sm">
            <div class="absolute -right-10 -bottom-10 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="relative z-10 space-y-1">
                <p class="text-[8px] font-black uppercase tracking-wider text-teal-100/80">Special Grant Info</p>
                <h2 class="text-sm font-extrabold tracking-tight">{{ $specialGrant->title }}</h2>
                @if ($specialGrant->issued_by)
                    <p class="text-[9px] text-teal-100/90">Issued by: <span class="font-bold text-white">{{ $specialGrant->issued_by }}</span></p>
                @endif
                @if ($specialGrant->description)
                    <p class="text-[9px] text-teal-100/80 italic mt-1">{{ $specialGrant->description }}</p>
                @endif
                <div class="flex items-center gap-6 border-t border-white/10 pt-2 mt-2">
                    <div>
                        <p class="text-[8px] uppercase tracking-wider font-extrabold text-teal-100/80">Installments</p>
                        <p class="text-xs font-black leading-none mt-0.5">{{ $installments->count() }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] uppercase tracking-wider font-extrabold text-teal-100/80">Total Received</p>
                        <p class="text-xs font-black leading-none text-emerald-100 mt-0.5">{{ number_format($installments->sum('amount')) }} <span class="text-[8px] text-teal-200">PKR</span></p>
                    </div>
                    @if ($session)
                        <div>
                            <p class="text-[8px] uppercase tracking-wider font-extrabold text-teal-100/80">Session</p>
                            <p class="text-[9px] font-bold leading-none mt-0.5 text-white">{{ $session->name }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Installments Table --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-3 py-2 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-[9px] font-extrabold text-slate-500 uppercase tracking-wider">Installment Records</h3>
                <span class="px-1.5 py-0.5 bg-slate-200/60 rounded text-[8px] font-bold text-slate-655">
                    {{ $installments->count() }} records
                </span>
            </div>

            @if ($installments->count() == 0)
                <div class="flex flex-col items-center justify-center py-8 px-4 text-center">
                    <i class="bi bi-inbox text-lg text-slate-400 mb-2"></i>
                    <h4 class="text-xs font-bold text-slate-755">No Installments Yet</h4>
                    <p class="text-[9px] text-slate-400 mt-1">Add the first installment received for this grant.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/20">
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left">Amount</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left w-[110px] min-w-[110px]">Date</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-left">Notes</th>
                                <th class="py-2 px-3 text-[10px] font-extrabold uppercase text-slate-400 border-0 bg-transparent text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach ($installments as $installment)
                                <tr class="hover:bg-slate-50/60 transition-colors duration-150 text-xs text-slate-655">
                                    <td class="py-2.5 px-3 font-bold text-slate-800 text-xs">
                                        {{ number_format($installment->amount) }}
                                    </td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-600 text-[11px] w-[110px] min-w-[110px]">
                                        {{ $installment->received_date->format('M d, Y') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-500 text-xs max-w-[150px] truncate" title="{{ $installment->description }}">
                                        {{ $installment->description ?? '-' }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <div class="flex items-center justify-end gap-2.5">
                                            <a href="{{ route('special-grants.installments.edit', [$specialGrant->id, $installment->id]) }}"
                                                class="text-slate-400 hover:text-teal-650 transition-colors" title="Edit">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </a>
                                            <form id="del-inst-{{ $installment->id }}"
                                                action="{{ route('special-grants.installments.destroy', [$specialGrant->id, $installment->id]) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    onclick="confirmDelInst(event, {{ $installment->id }})"
                                                    class="text-red-500 hover:text-red-750 transition-colors p-0.5" title="Delete">
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
        function confirmDelInst(event, id) {
            event.preventDefault();
            Swal.fire({
                title: 'Delete Installment?',
                text: "This cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed || result.value) {
                    document.getElementById('del-inst-' + id).submit();
                }
            });
        }
    </script>
@endsection
