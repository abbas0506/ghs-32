<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $grant->title }} — Financial Statement & Tax Report</title>
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

        /* Urdu text — mPDF auto-detects via autoScriptToLang + lang="ur" */
        .ur {
            font-family: 'xbriyaz', sans-serif;
            direction: rtl;
            text-align: right;
            line-height: 1.6;
            font-size: 9.5pt;
        }

        .en {
            font-family: 'dejavusanscondensed', sans-serif;
            direction: ltr;
            text-align: left;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
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
            margin-bottom: 10px;
        }

        .summary-card {
            border-radius: 6px;
            padding: 7px 9px;
            text-align: center;
        }

        .card-teal { background: #f0fdfa; border: 1.5px solid #99f6e4; }
        .card-emerald { background: #f0fdf4; border: 1.5px solid #a7f3d0; }
        .card-rose { background: #fff1f2; border: 1.5px solid #fecdd3; }
        .card-amber { background: #fffbeb; border: 1.5px solid #fef3c7; }

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
        .text-amber { color: #d97706; }

        .section-heading {
            font-size: 10pt;
            font-weight: bold;
            color: #0f766e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            border-left: 3.5px solid #0f766e;
            padding-left: 6px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
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

        .tag-purchase { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; padding: 0.5px 4px; font-weight: bold; font-size: 7pt; }
        .tag-service { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; padding: 0.5px 4px; font-weight: bold; font-size: 7pt; }
        .tag-utility { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; padding: 0.5px 4px; font-weight: bold; font-size: 7pt; }
        .tag-other { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 0.5px 4px; font-weight: bold; font-size: 7pt; }

        .table-footer td {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            border: 1px solid #334155;
            font-size: 8.5pt;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signatures-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding-top: 30px;
        }

        .signature-line {
            border-top: 1.5px solid #475569;
            width: 80%;
            margin: 0 auto 4px auto;
        }

        .signature-title {
            font-size: 8pt;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td style="width: 65%;">
                <div class="header-title">Government High School 32 3/R</div>
                @php
                    $titleIsUrdu = \App\Helpers\UrduHelper::hasUrdu($grant->title ?? '');
                @endphp
                <div class="header-subtitle" {{ $titleIsUrdu ? 'lang="ur" dir="rtl" style="font-family:xbriyaz;"' : '' }}>
                    Financial Statement & Tax Audit Report — {{ $grant->title }}
                </div>
            </td>
            <td class="header-meta">
                <div style="margin-bottom: 3px;">
                    <span class="meta-badge">Session: {{ $session ? $session->name : 'Active Session' }}</span>
                </div>
                <div>Issued By: <strong>{{ $grant->issued_by ?? 'Government of Punjab' }}</strong></div>
                <div>Date Generated: {{ date('d M Y, h:i A') }}</div>
            </td>
        </tr>
    </table>

    {{-- Section 1: Executive Financial Summary Cards --}}
    <table class="summary-grid">
        <tr>
            <td style="width: 25%;">
                <div class="summary-card card-teal">
                    <div class="summary-card-title">Opening Balance</div>
                    <div class="summary-card-value text-teal">{{ number_format($openingBalance) }} <span style="font-size:7pt; color:#64748b;">PKR</span></div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="summary-card card-emerald">
                    <div class="summary-card-title">Total Inflow (Receipts)</div>
                    <div class="summary-card-value text-emerald">{{ number_format($totalReceived) }} <span style="font-size:7pt; color:#64748b;">PKR</span></div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="summary-card card-rose">
                    <div class="summary-card-title">Total Outflow (Expenses)</div>
                    <div class="summary-card-value text-rose">{{ number_format($totalGross) }} <span style="font-size:7pt; color:#64748b;">PKR</span></div>
                </div>
            </td>
            <td style="width: 25%;">
                <div class="summary-card card-amber">
                    <div class="summary-card-title">Net Available Balance</div>
                    <div class="summary-card-value text-amber">{{ number_format($balance) }} <span style="font-size:7pt; color:#64748b;">PKR</span></div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Section 2: Tax Withholding Details --}}
    <div class="section-heading">Tax Withholding Breakdown (Purchases vs Services)</div>
    <table class="data-table" style="margin-bottom: 12px;">
        <thead>
            <tr>
                <th>Category</th>
                <th class="text-center">Count</th>
                <th class="text-right">Net Paid</th>
                <th class="text-right">GST Withheld</th>
                <th class="text-right">PST Withheld</th>
                <th class="text-right">Income Tax</th>
                <th class="text-right">Total Tax Withheld</th>
                <th class="text-right">Gross Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Purchases (Goods / Supplies)</strong></td>
                <td class="text-center">{{ $purchasesSummary->count }}</td>
                <td class="text-right">{{ number_format($purchasesSummary->net_paid) }}</td>
                <td class="text-right" style="color:#0f766e; font-weight:bold;">{{ number_format($purchasesSummary->gst) }}</td>
                <td class="text-right" style="color:#94a3b8;">—</td>
                <td class="text-right" style="color:#0f766e; font-weight:bold;">{{ number_format($purchasesSummary->it) }}</td>
                <td class="text-right" style="font-weight:bold; color:#0f766e;">{{ number_format($purchasesSummary->gst + $purchasesSummary->it) }}</td>
                <td class="text-right" style="font-weight:bold;">{{ number_format($purchasesSummary->gross) }}</td>
            </tr>
            <tr class="even-row">
                <td><strong>Services (Labour / Repairs)</strong></td>
                <td class="text-center">{{ $servicesSummary->count }}</td>
                <td class="text-right">{{ number_format($servicesSummary->net_paid) }}</td>
                <td class="text-right" style="color:#94a3b8;">—</td>
                <td class="text-right" style="color:#0f766e; font-weight:bold;">{{ number_format($servicesSummary->pst) }}</td>
                <td class="text-right" style="color:#0f766e; font-weight:bold;">{{ number_format($servicesSummary->it) }}</td>
                <td class="text-right" style="font-weight:bold; color:#0f766e;">{{ number_format($servicesSummary->pst + $servicesSummary->it) }}</td>
                <td class="text-right" style="font-weight:bold;">{{ number_format($servicesSummary->gross) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="table-footer">
                <td><strong>Total Tax Summary</strong></td>
                <td class="text-center">{{ $purchasesSummary->count + $servicesSummary->count }}</td>
                <td class="text-right" style="color:#e2e8f0;">{{ number_format($purchasesSummary->net_paid + $servicesSummary->net_paid) }}</td>
                <td class="text-right" style="color:#99f6e4;">{{ number_format($purchasesSummary->gst) }}</td>
                <td class="text-right" style="color:#99f6e4;">{{ number_format($servicesSummary->pst) }}</td>
                <td class="text-right" style="color:#99f6e4;">{{ number_format($purchasesSummary->it + $servicesSummary->it) }}</td>
                <td class="text-right" style="color:#5eead4; font-weight:bold;">{{ number_format($totalGst + $totalPst + $totalIt) }}</td>
                <td class="text-right" style="color:#fef08a; font-weight:bold;">{{ number_format($purchasesSummary->gross + $servicesSummary->gross) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Section 3: Grant Ledger Details --}}
    <div class="section-heading">Detailed Grant Ledger (Chronological Audit)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">Date</th>
                <th style="width: 9%;">Receipt #</th>
                <th style="width: 25%;">Particulars / Description</th>
                <th class="text-right" style="width: 9%;">Net Paid</th>
                <th class="text-right" style="width: 8%;">GST</th>
                <th class="text-right" style="width: 8%;">PST</th>
                <th class="text-right" style="width: 8%;">IT</th>
                <th class="text-right" style="width: 8%;">Outflow</th>
                <th class="text-right" style="width: 8%;">Inflow</th>
                <th class="text-right" style="width: 9%;">Balance</th>
            </tr>
        </thead>
        <tbody>
            {{-- Opening Balance Row --}}
            <tr style="background-color: #f0fdf4; font-weight: bold;">
                <td>—</td>
                <td><span style="color:#059669; font-weight:bold;">INIT</span></td>
                <td>Opening Balance Forward (Session {{ $session ? $session->name : '' }})</td>
                <td class="text-right">—</td>
                <td class="text-right">—</td>
                <td class="text-right">—</td>
                <td class="text-right">—</td>
                <td class="text-right">—</td>
                <td class="text-right" style="color:#059669; font-weight:bold;">{{ number_format($openingBalance) }}</td>
                <td class="text-right" style="font-weight:bold;">{{ number_format($openingBalance) }}</td>
            </tr>

            @php $rowIndex = 0; @endphp
            @foreach ($ledger as $item)
                @php $rowIndex++; @endphp
                <tr class="{{ $rowIndex % 2 === 0 ? 'even-row' : '' }}">
                    <td>{{ $item->date ? \Carbon\Carbon::parse($item->date)->format('d M Y') : '—' }}</td>
                    <td>
                        @if ($item->type === 'receipt')
                            <span style="color:#059669; font-weight:bold;">RCPT</span>
                        @else
                            {{ $item->receipt_no ?? '—' }}
                        @endif
                    </td>
                    <td>
                        @php
                            $desc = $item->description ?? '—';
                            $isUrdu = \App\Helpers\UrduHelper::hasUrdu($desc);
                        @endphp
                        @if ($isUrdu)
                            <div class="ur" lang="ur" dir="rtl">
                                {{ $desc }}
                                @if ($item->type === 'expense' && !empty($item->expense_type))
                                    <span class="tag-{{ $item->expense_type }}" lang="en" dir="ltr" style="font-family: dejavusanscondensed;">{{ strtoupper($item->expense_type) }}</span>
                                @endif
                            </div>
                        @else
                            <div class="en">
                                {{ $desc }}
                                @if ($item->type === 'expense' && !empty($item->expense_type))
                                    <span class="tag-{{ $item->expense_type }}">{{ strtoupper($item->expense_type) }}</span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td class="text-right">
                        {{ $item->type === 'expense' ? number_format($item->net_amount) : '—' }}
                    </td>
                    <td class="text-right">
                        {{ ($item->type === 'expense' && $item->gst_amount > 0) ? number_format($item->gst_amount) : '—' }}
                    </td>
                    <td class="text-right">
                        {{ ($item->type === 'expense' && $item->pst_amount > 0) ? number_format($item->pst_amount) : '—' }}
                    </td>
                    <td class="text-right">
                        {{ ($item->type === 'expense' && $item->it_amount > 0) ? number_format($item->it_amount) : '—' }}
                    </td>
                    <td class="text-right" style="color:#e11d48; font-weight:bold;">
                        {{ $item->type === 'expense' ? number_format($item->amount) : '—' }}
                    </td>
                    <td class="text-right" style="color:#059669; font-weight:bold;">
                        {{ $item->type === 'receipt' ? number_format($item->amount) : '—' }}
                    </td>
                    <td class="text-right" style="font-weight:bold; color:#0f172a;">
                        {{ number_format($item->running_balance) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-footer">
                <td colspan="3">TOTALS SUMMARY</td>
                <td class="text-right" style="color:#e2e8f0;">{{ number_format($totalNetPaid) }}</td>
                <td class="text-right" style="color:#99f6e4;">{{ $totalGst > 0 ? number_format($totalGst) : '—' }}</td>
                <td class="text-right" style="color:#99f6e4;">{{ $totalPst > 0 ? number_format($totalPst) : '—' }}</td>
                <td class="text-right" style="color:#99f6e4;">{{ $totalIt > 0 ? number_format($totalIt) : '—' }}</td>
                <td class="text-right" style="color:#fecdd3;">{{ number_format($totalGross) }}</td>
                <td class="text-right" style="color:#a7f3d0;">{{ number_format($totalReceived) }}</td>
                <td class="text-right" style="color:#fef08a;">{{ number_format($balance) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Official Signatures --}}
    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div class="signature-title">Prepared By (School Accountant)</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div class="signature-title">Verified By (Audit / Incharge)</div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div class="signature-title">Approved By (Headmaster / SMC Chair)</div>
            </td>
        </tr>
    </table>

</body>
</html>
