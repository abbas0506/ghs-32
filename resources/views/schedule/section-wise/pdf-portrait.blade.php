<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class-wise Time Table</title>
    <style>
        @page {
            margin: 40px 50px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
        }

        .page-break {
            page-break-after: always;
        }

        .section-container {
            position: relative;
            margin-bottom: 20px;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 10px;
        }

        .header-left {
            width: 12%;
            text-align: left;
            vertical-align: middle;
        }

        .header-center {
            width: 73%;
            text-align: center;
            vertical-align: middle;
        }

        .header-right {
            width: 15%;
            text-align: right;
            vertical-align: bottom;
            font-size: 8px;
            color: #64748b;
        }

        .school-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .doc-title {
            font-size: 13px;
            font-weight: normal;
            color: #0f172a;
        }

        .doc-title-highlight {
            font-weight: bold;
            color: #0d9488;
            background-color: #f0fdfa;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #ccfbf1;
        }

        /* Data Table */
        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 6px;
            border: 1px solid #cbd5e1;
            text-align: center;
        }

        table.data td {
            border: 1px solid #e2e8f0;
            padding: 6px;
            font-size: 11px;
            vertical-align: middle;
            text-align: center;
        }

        .alloc-cell {
            padding: 2px 0;
            line-height: 1.3;
        }

        .subject-text {
            font-weight: bold;
            color: #115e59;
            font-size: 11px;
        }

        .teacher-text {
            color: #475569;
            font-size: 10px;
        }

        .no-alloc {
            color: #cbd5e1;
            font-weight: bold;
            font-size: 16px;
        }

        .divider-line {
            height: 1px;
            background-color: #e2e8f0;
            margin: 4px 20%;
        }

        /* Footer & Stamp */
        .stamp-container {
            margin-top: 15px;
            width: 100%;
        }

        .stamp-table {
            width: 100%;
            border: none;
        }

        .stamp-table td {
            border: none;
            vertical-align: bottom;
        }

        .stamp-line {
            border-top: 1px solid #64748b;
            width: 150px;
            margin: 0 auto;
            margin-bottom: 4px;
        }

        .stamp-text {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            color: #475569;
        }

        .page-divider {
            border-top: 1px dashed #cbd5e1;
            margin: 30px 0;
            width: 100%;
            text-align: center;
        }

        .cut-icon {
            background-color: white;
            color: #cbd5e1;
            padding: 0 10px;
            position: relative;
            top: -8px;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <main>
        @foreach ($sections->chunk(2) as $chunk)
            @foreach ($chunk as $section)
                <div class="section-container">
                    <table class="header-table">
                        <tr>
                            <td class="header-left">
                                <img alt="logo" src="{{ public_path('/images/logo/ghs-32.png') }}" width="45" height="45">
                            </td>
                            <td class="header-center">
                                <div class="school-title">Govt. High School 32/2L, Okara</div>
                                <div class="doc-title">Time Table for Class <span class="doc-title-highlight">{{ $section->name }}</span></div>
                            </td>
                            <td class="header-right">
                                Printed: {{ now()->format('d M, Y') }}
                            </td>
                        </tr>
                    </table>

                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 15%">Lecture</th>
                                <th style="width: 25%">Time Slot</th>
                                <th style="width: 30%">Subject</th>
                                <th style="width: 30%">Assigned Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lectures as $lecture)
                                @php
                                    $allocations = $section->schedules()->where('lecture_no', $lecture->lecture_no)->get();
                                @endphp
                                <tr>
                                    <td style="font-weight: bold; background-color: #fcfcfd; font-size:12px;">{{ $lecture->lecture_no }}</td>
                                    <td style="color:#64748b; font-size:10px; white-space: nowrap;">
                                        {{ $lecture->starts_at->format('h:i A') }} - {{ $lecture->starts_at->copy()->addMinutes($lecture->duration)->format('h:i A') }}
                                    </td>
                                    <td>
                                        @forelse ($allocations as $idx => $allocation)
                                            <div class="alloc-cell subject-text">{{ $allocation->subject->name }}</div>
                                            @if (!$loop->last) <div class="divider-line"></div> @endif
                                        @empty
                                            <span class="no-alloc">-</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        @forelse ($allocations as $idx => $allocation)
                                            <div class="alloc-cell teacher-text">{{ $allocation->user->profile->name ?? 'N/A' }}</div>
                                            @if (!$loop->last) <div class="divider-line"></div> @endif
                                        @empty
                                            <span class="no-alloc">-</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="stamp-container">
                        <table class="stamp-table">
                            <tr>
                                <td style="width: 60%;"></td>
                                <td style="width: 40%; text-align: center; padding-top: 40px;">
                                    <div class="stamp-line"></div>
                                    <div class="stamp-text">Sr. Headmaster</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if (!$loop->last)
                    <div class="page-divider"><span class="cut-icon">✂--- Cut Here ---✂</span></div>
                @endif
            @endforeach

            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </main>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 285;
            $y = 815;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "normal");
            $size = 8;
            $color = array(0.4, 0.4, 0.4);
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</body>

</html>
