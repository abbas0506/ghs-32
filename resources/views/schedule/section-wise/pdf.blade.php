<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section-Wise Schedule</title>
    <link href="{{ public_path('css/pdf_tw.css') }}" rel="stylesheet">
    <style>
        @page {
            margin: 40px 40px 50px 40px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
        }

        /* Header Layout */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .header-left {
            width: 15%;
            text-align: left;
            vertical-align: middle;
        }

        .header-center {
            width: 70%;
            text-align: center;
            vertical-align: middle;
        }

        .header-right {
            width: 15%;
            text-align: right;
            vertical-align: top;
            font-size: 9px;
            color: #64748b;
        }

        .school-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .doc-title {
            font-size: 13px;
            font-weight: bold;
            color: #0d9488;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Core Data Table */
        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 4px;
            border: 1px solid #cbd5e1;
            text-align: center;
        }

        table.data td {
            border: 1px solid #e2e8f0;
            padding: 4px;
            font-size: 10px;
            vertical-align: top;
            text-align: center;
        }

        table.data td.class-col {
            font-weight: bold;
            background-color: #fcfcfd;
            vertical-align: middle;
            color: #0f172a;
            width: 10%;
        }

        /* Allocation Blocks */
        .alloc-block {
            background-color: #f0fdfa;
            border: 1px solid #ccfbf1;
            padding: 3px;
            margin-bottom: 2px;
            border-radius: 2px;
        }

        .alloc-block.multiple {
            border-bottom: 1px dashed #99f6e4;
        }

        .subject {
            font-weight: bold;
            color: #115e59;
            font-size: 10px;
        }

        .teacher {
            color: #0d9488;
            font-size: 9px;
        }
        
        .divider-line {
            height: 1px;
            background-color: #99f6e4;
            margin: 2px 10%;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0px;
            left: 30px;
            right: 50px;
            height: 30px;
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <main>
        <!-- Header Section -->
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <img alt="logo" src="{{ public_path('/images/logo/ghs-32.png') }}" width="50" height="50">
                </td>
                <td class="header-center">
                    <div class="school-title">Govt. High School 32/2L, Okara</div>
                    <div class="doc-title">Master Class Schedule - {{ now()->format('Y') }}</div>
                </td>
                <td class="header-right">
                    Printed:<br>{{ now()->format('d M, Y') }}
                </td>
            </tr>
        </table>

        <!-- Schedule Table -->
        <table class="data">
            <thead>
                <tr>
                    <th class="class-col">Class</th>
                    @foreach ($lectures as $lecture)
                        <th style="width: {{ 90 / count($lectures) }}%;">
                            <div style="margin-bottom:2px;">Period {{ $lecture->lecture_no }}</div>
                            <span style="font-weight:normal; font-size:8px; color:#64748b;">
                                {{ $lecture->starts_at->format('H:i') }}
                            </span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($sections as $section)
                    <tr>
                        <td class="class-col">{{ $section->name }}</td>
                        @foreach ($lectures as $lecture)
                            <td>
                                @php
                                    $allocations = $section->schedules()->havingLectureNo($lecture->lecture_no)->get();
                                @endphp
                                @foreach ($allocations as $allocation)
                                    <div class="alloc-block">
                                        <div class="subject">{{ $allocation->subject->short_name }}</div>
                                        <div class="teacher">{{ $allocation->user->profile->short_name }}</div>
                                    </div>
                                    @if (!$loop->last)
                                        <div class="divider-line"></div>
                                    @endif
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

    </main>

    <!-- Page Numbering Script for DomPDF -->
    <script type="text/php">
        if (isset($pdf)) {
            $x = 450;
            $y = 15;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "normal");
            $size = 8;
            $color = array(0.4, 0.4, 0.4);
            $word_space = 0.0;
            $char_space = 0.0;
            $angle = 0.0;
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>

</html>
