<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Slips</title>
    <link href="{{ public_path('css/pdf_tw.css') }}" rel="stylesheet">
    <style>
        @page {
            margin: 50px 30px 50px 30px;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 30px;
            right: 50px;
            background-color: white;
            height: 50px;
        }

        .page-break {
            page-break-after: always;
        }

        .data tr th,
        .data tr td {
            font-size: 11px;
            text-align: center;
            padding: 0px
        }
    </style>
</head>

@php
    $roman = config('global.romans');
    $i = 0;
    $numOfColumns = 3;
@endphp

<body>
    <main>
        <div class="custom-container">
            <small>Schedule slips printed on {{ now()->format('d-M-Y') }}</small>
            <table class="table-auto w-full mt-1" cellspacing="0">
                <tbody class="data">
                    @foreach ($users as $user)
                        @if ($i % $numOfColumns == 0)
                            <tr class="text-sm">
                        @endif
                        {{-- time table --}}
                        <td
                            style="border: 0.5px solid #777; padding: 10px; vertical-align: center; horizonatal-align: center; width: {{ 100 / $numOfColumns }}%">
                            <h4 class="text-center m-0 text-decoration underline underline-offset-2">
                                {{ $user->profile->name }}</h4>
                            <table class="w-full border-collapse border text-xs mt-2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Time</th>
                                        <th style="text-align: left">Subject</th>
                                        <th style="text-align: left">Class</th>
                                    </tr>
                                    @foreach ($user->schedules->sortBy('lecture_no') as $schedule)
                                        @php
                                            $lectureTiming = $lectureTimings
                                                ->where('lecture_no', $schedule->lecture_no)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $schedule->lecture_no }}</td>
                                            <td>{{ $lectureTiming->timeInterval($schedule->lecture_no) }}</td>
                                            <td style="text-align: left">{{ $schedule->subject->name }}</td>
                                            <td style="text-align: left">{{ $schedule->section->name }}</td>
                                        </tr>
                                    @endforeach

                                </thead>
                            </table>
                            <div style="margin-top: 10px; color:rgb(92, 105, 110)">Printed on
                                {{ now()->format('d-m-Y') }}
                            </div>
                        </td>

                        @if ($i % $numOfColumns == $numOfColumns - 1)
                            </tr>
                        @endif
                        @php $i++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <script type="text/php">
        if (isset($pdf) ) {
            $x = 300;
            $y = 20;
            $text = "{PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "bold");
            $size = 6;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>
</body>

</html>
