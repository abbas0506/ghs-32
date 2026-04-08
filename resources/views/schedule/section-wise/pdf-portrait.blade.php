<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class-wise Time Table</title>
    <style>
        @page {
            margin: 80px 50px;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
        }

        .page-break {
            page-break-after: always;
        }

        .data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data tr th,
        .data tr td {
            font-size: 12px;
            text-align: center;
            border: 0.5px solid #000;
            padding: 6px 4px;
        }

        .data tr th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .header-table {
            width: 100%;
            margin-bottom: 5px;
            border: none;
        }

        .header-table td {
            border: none;
            padding: 2px;
        }

        .header-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-sub {
            text-align: center;
            font-size: 14px;
            font-weight: normal;
        }

        .section-container {
            position: relative;
            margin-bottom: 30px;
        }

        .stamp {
            text-align: right;
            margin-top: 40px;
            font-weight: bold;
            font-size: 13px;
            padding-right: 10px;
        }

        .w-full {
            width: 100%;
        }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 40px 0;
            width: 100%;
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
                            <td class="header-title">Time Table for Class {{ $section->name }}</td>
                        </tr>
                        <tr>
                            <td class="header-sub">Govt High School 32/2L, Okara</td>
                        </tr>
                    </table>

                    <table class="data">
                        <thead>
                            <tr>
                                <th style="width: 15%">Lecture No.</th>
                                <th style="width: 25%">Time</th>
                                <th style="width: 30%">Subject</th>
                                <th style="width: 30%">Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lectures as $lecture)
                                @php
                                    $allocation = $section->schedules()->where('lecture_no', $lecture->lecture_no)->first();
                                @endphp
                                <tr>
                                    <td>{{ $lecture->lecture_no }}</td>
                                    <td>{{ $lecture->starts_at->format('h:i A') }} - {{ $lecture->starts_at->copy()->addMinutes($lecture->duration)->format('h:i A') }}</td>
                                    <td>{{ $allocation ? $allocation->subject->name : '-' }}</td>
                                    <td>{{ $allocation ? $allocation->user->profile->name : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="stamp">
                        Sr. Headmaster
                    </div>
                </div>

                @if (!$loop->last)
                    <div class="divider"></div>
                @endif
            @endforeach

            @if (!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </main>

    <script type="text/php">
        if (isset($pdf) ) {
            $x = 270;
            $y = 810;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "bold");
            $size = 8;
            $color = array(0,0,0);
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</body>

</html>
