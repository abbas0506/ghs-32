<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>General Ledger — Financial Statement</title>
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

        .section-heading {
            font-size: 11pt;
            font-weight: bold;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 15px;
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
                <div class="header-subtitle">General Ledger Report</div>
            </td>
            <td class="header-meta" style="width: 30%;">
                Date: {{ date('d M Y') }}
            </td>
        </tr>
    </table>

    @foreach ($accounts as $account)
        @if($account->lines->count() > 0)
            <div class="section-heading">{{ $account->name }} ({{ $account->code }}) — {{ ucfirst($account->type) }}</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 10%;">Txn #</th>
                        <th style="width: 45%;">Particulars / Description</th>
                        <th class="text-right" style="width: 10%;">Credit (Outflow)</th>
                        <th class="text-right" style="width: 10%;">Debit (Inflow)</th>
                        <th class="text-right" style="width: 10%;">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $balance = 0;
                        $rowIndex = 0;
                    @endphp
                    @foreach ($account->lines as $line)
                        @php 
                            $rowIndex++;
                            if (in_array($account->type, ['asset', 'expense'])) {
                                $balance += $line->debit - $line->credit;
                            } else {
                                $balance += $line->credit - $line->debit;
                            }
                        @endphp
                        <tr class="{{ $rowIndex % 2 === 0 ? 'even-row' : '' }}">
                            <td>{{ $line->transaction ? \Carbon\Carbon::parse($line->transaction->date)->format('d M Y') : '-' }}</td>
                            <td>{{ $line->transaction_id }}</td>
                            <td>{{ $line->transaction ? $line->transaction->description : '-' }}</td>
                            <td class="text-right" style="color: #e11d48; font-weight: bold;">
                                {{ $line->credit ? number_format($line->credit) : '—' }}
                            </td>
                            <td class="text-right" style="color: #059669; font-weight: bold;">
                                {{ $line->debit ? number_format($line->debit) : '—' }}
                            </td>
                            <td class="text-right" style="font-weight: bold;">
                                {{ number_format($balance) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-footer">
                        <td colspan="3" class="text-right">Total</td>
                        <td class="text-right">{{ number_format($account->lines->sum('credit')) }}</td>
                        <td class="text-right">{{ number_format($account->lines->sum('debit')) }}</td>
                        <td class="text-right">
                            @if (in_array($account->type, ['asset', 'expense']))
                                {{ number_format($account->lines->sum('debit') - $account->lines->sum('credit')) }}
                            @else
                                {{ number_format($account->lines->sum('credit') - $account->lines->sum('debit')) }}
                            @endif
                        </td>
                    </tr>
                </tfoot>
            </table>
        @endif
    @endforeach

</body>
</html>
