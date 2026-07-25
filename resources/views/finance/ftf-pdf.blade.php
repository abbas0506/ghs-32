<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FTF Cash Book — Financial Statement</title>
    <style>
        body {
            font-family: 'dejavusanscondensed', sans-serif;
            font-size: 9.5pt;
            color: #1e293b;
            line-height: 1.35;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-bottom: 2.5px solid #0f766e;
            padding-bottom: 6px;
        }

        .header-title {
            font-size: 15pt;
            font-weight: bold;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-subtitle {
            font-size: 10.5pt;
            font-weight: bold;
            color: #334155;
            margin-top: 2px;
        }

        .header-meta {
            text-align: right;
            font-size: 9pt;
            color: #475569;
        }

        .meta-badge {
            background: #ccfbf1;
            color: #0f766e;
            border: 1px solid #99f6e4;
            font-weight: bold;
            padding: 2px 7px;
            font-size: 8.5pt;
        }

        .summary-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin-bottom: 15px;
        }

        .summary-card {
            border-radius: 6px;
            padding: 7px 9px;
            text-align: center;
        }

        .card-teal { background: #f0fdfa; border: 1.5px solid #99f6e4; }
        .card-emerald { background: #f0fdf4; border: 1.5px solid #a7f3d0; }
        .card-rose { background: #fff1f2; border: 1.5px solid #fecdd3; }

        .summary-card-title {
            font-size: 7.5pt;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .summary-card-value {
            font-size: 12pt;
            font-weight: bold;
        }

        .text-teal { color: #0f766e; }
        .text-emerald { color: #059669; }
        .text-rose { color: #e11d48; }

        .section-heading {
            font-size: 11pt;
            font-weight: bold;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            border-left: 4px solid #0f766e;
            padding-left: 8px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table th {
            background-color: #0f766e;
            color: #ffffff;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 6px;
            border: 1px solid #0d9488;
            text-align: left;
        }

        table.data-table td {
            padding: 4.5px 6px;
            border: 1px solid #cbd5e1;
            font-size: 8.5pt;
            color: #1e293b;
        }

        table.data-table tr.even-row {
            background-color: #f8fafc;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .table-footer td {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            border: 1px solid #334155;
            font-size: 8.5pt;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <div class="header-title">Government High School 32 3/R</div>
                <div class="header-subtitle">Farogh-e-Taleem Fund (FTF) Cash Book</div>
            </td>
            <td class="header-meta" style="width: 30%;">
                Date: {{ date('d M Y') }}
            </td>
        </tr>
    </table>

    {{-- Summary Cards --}}
    <table class="summary-grid">
        <tr>
            <td style="width: 25%;">
                <div class="summary-card card-teal">
                    <div class="summary-card-title text-teal">Opening Balance</div>
                    <div class="summary-card-value text-teal">{{ number_format($openingBalance) }}</div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="summary-card card-emerald">
                    <div class="summary-card-title text-emerald">Total Received</div>
                    <div class="summary-card-value text-emerald">{{ number_format($totalReceived) }}</div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="summary-card card-rose">
                    <div class="summary-card-title text-rose">Total Spent</div>
                    <div class="summary-card-value text-rose">{{ number_format($totalGross) }}</div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="summary-card card-teal" style="background: #f8fafc; border-color: #e2e8f0;">
                    <div class="summary-card-title" style="color: #475569;">Closing Balance</div>
                    <div class="summary-card-value" style="color: #1e293b;">{{ number_format($balance) }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Detailed Ledger --}}
    <div class="section-heading">Detailed Chronological Cash Book</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Ref & Date</th>
                <th style="width: 45%;">Particulars / Description</th>
                <th class="text-right" style="width: 13%;">Outflow (Spent)</th>
                <th class="text-right" style="width: 13%;">Inflow (Received)</th>
                <th class="text-right" style="width: 14%;">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $rowIndex = 0; @endphp
            @foreach ($ledger as $item)
                @php $rowIndex++; @endphp
                <tr class="{{ $rowIndex % 2 === 0 ? 'even-row' : '' }}">
                    <td>
                        <div style="font-weight: bold; font-size: 8pt; color: #475569;">{{ $item->receipt_no ?? '—' }}</div>
                        <div style="font-size: 7.5pt; color: #64748b; margin-top: 2px;">{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d M Y') : '—' }}</div>
                    </td>
                    <td>
                        {{ $item->description }}
                    </td>
                    <td class="text-right" style="color: #e11d48; font-weight: bold;">
                        @if ($item->type === 'expense' || ($item->type === 'manual_transaction' && $item->txn_direction === 'credit'))
                            -{{ number_format($item->amount) }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-right" style="color: #059669; font-weight: bold;">
                        @if ($item->type === 'receipt' || ($item->type === 'manual_transaction' && $item->txn_direction === 'debit'))
                            +{{ number_format($item->amount) }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-right" style="font-weight: bold;">
                        {{ number_format($item->running_balance) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-footer">
                <td colspan="2" class="text-right">Total Outflows / Inflows</td>
                <td class="text-right">-{{ number_format($totalGross) }}</td>
                <td class="text-right">+{{ number_format($totalReceived) }}</td>
                <td class="text-right">{{ number_format($balance) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
