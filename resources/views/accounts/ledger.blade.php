@extends('layouts.app')

@section('page-content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">General Ledger</h1>

        @foreach ($accounts as $account)
            <div class="mb-8 border">
                <!-- Account Header -->
                <div class="px-4 py-3">
                    <h2 class="text-lg font-semibold">
                        {{ $account->name }}
                    </h2>
                </div>

                <!-- Ledger Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full  text-sm">
                        <thead class="">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-gray-700">Date</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-700">Txn #</th>
                                <th class="px-4 py-2 text-right font-medium text-gray-700">Credit (Outflow)</th>
                                <th class="px-4 py-2 text-right font-medium text-gray-700">Debit (Inflow)</th>
                                <th class="px-4 py-2 text-right font-medium text-gray-700">Balance</th>
                                <th class="px-4 py-2 text-center font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @php $balance = 0; @endphp

                            @forelse ($account->lines as $line)
                                @php
                                    $balance += $line->debit - $line->credit;
                                    $txn = $line->transaction;
                                @endphp

                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        {{ optional($line->transaction)->date ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $line->transaction_id }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-red-600">
                                        {{ $line->credit ? number_format($line->credit, 2) : '—' }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-green-600">
                                        {{ $line->debit ? number_format($line->debit, 2) : '—' }}
                                    </td>
                                    <td class="px-4 py-2 text-right font-medium">
                                        {{ number_format($balance, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($txn)
                                            <form action="{{ route('accounts.transaction.destroy', $txn->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this transaction?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                        No transactions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <!-- Totals -->
                        <tfoot class="bg-gray-100 font-semibold">
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-right">Total</td>
                                <td class="px-4 py-2 text-right text-red-700">
                                    {{ number_format($account->lines->sum('credit'), 2) }}
                                </td>
                                <td class="px-4 py-2 text-right text-green-700">
                                    {{ number_format($account->lines->sum('debit'), 2) }}
                                </td>
                                <td class="px-4 py-2 text-right">
                                    {{ number_format($account->lines->sum('debit') - $account->lines->sum('credit'), 2) }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
@endsection
