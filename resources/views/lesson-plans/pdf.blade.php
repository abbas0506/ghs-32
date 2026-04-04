<!DOCTYPE html>
<html lang="ur" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section Result</title>
    <link href="{{ public_path('css/pdf_tw.css') }}" rel="stylesheet">
    <style>
        @page {
            margin: 50px 80px 50px 50px;
        }

        body {
            font-family: 'urdu', 'DejaVu Sans', sans-serif;
            font-size: 9pt;
            color: #1a1a2e;
            direction: rtl;
            text-align: right;
        }

        /* English-only elements */
        .en {
            font-family: 'DejaVu Sans', sans-serif;
            direction: ltr;
        }

        /* Urdu or mixed content — reshaped text renders correctly with just rtl */
        .ur {
            font-family: 'urdu', serif;
            direction: rtl;
            text-align: right;
            line-height: 2.1;
            font-size: 10pt;
        }

        /* For cells that may have BOTH English labels and Urdu values */
        .mixed {
            direction: rtl;
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 30px;
            right: 50px;
            background-color: white;
            /* height: 20px; */
        }

        .page-break {
            page-break-after: always;
        }

        .data tr th,
        .data tr td {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            text-align: center;
            vertical-align: top;
            text-align: left;
            border: 0.5px solid;
        }

        table.borderless tr th,
        table.borderless tr td {
            border: none;
        }
    </style>
</head>
@php
    $roman = config('global.romans');
@endphp

<body>

    <main>
        <div class="custom-container">

            <div class="w-1/2 mx-auto">
                <div class="relative">
                    <div class="absolute"><img alt="logo" src="{{ public_path('/images/logo/ghs-32.png') }}"
                            class="w-16"></div>
                </div>
                <table class="w-full">
                    <tbody>
                        <tr>
                            <td class="text-center text-xl font-bold"> </td>
                        </tr>
                        <tr>
                            <td class="text-center text-sm">Govt. High School 32/2L, Okara</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- table header -->
            <h4 class="mt-4 text-center underline underline-offset-2">Grade{{ $meta['grade']->name ?? '—' }} </h4>


            @forelse($lessons as $lessonNo => $group)
                <div class="w-full mt-2">
                    <div class="text-sm font-bold"><u>Lesson Plan # {{ $lessonNo }}</u></div>
                    <table class="table-auto borderless xs w-full mt-1">
                        <thead class="data">
                            <tr>
                                <th style="width: 15%">Subject</th>
                                <th style="width: 40%">Lesson</th>
                                <th style="width: 15%">Activity</th>
                                <th style="width: 15%">Homework</th>
                                <th style="width: 15%">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="data">
                            @foreach ($group as $lesson)
                                <tr class="tr">
                                    <td class="mixed">
                                        {{ $lesson->subject->name ?? '—' }}
                                    </td>
                                    <td>
                                        @if ($lesson->title)
                                            <div style="font-weight: 400; text-align:left">{{ $lesson->title ?? '—' }}
                                            </div>
                                            <div style="text-align: left" class="mixed">{{ $lesson->objective ?? '—' }}
                                            </div>
                                            <ul style="text-align: left">
                                                @foreach ($lesson->cues as $cue)
                                                    <li style="font-size:9px;text-align:left;margin-top:3px;"
                                                        class="mixed">
                                                        {{ $cue->content }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div>No title</div>
                                        @endif
                                    </td>
                                    <td style="text-align: left" class="mixed">{{ $lesson->activity ?? '—' }}</td>
                                    <td style="text-align: left" class="mixed">{{ $lesson->homework ?? '—' }}</td>
                                    <td style="text-align: left" class="mixed">{{ $lesson->remarks ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="no-data">No lesson plans found for the selected criteria.</div>
            @endforelse

            <div style="margin-top:20px; text-align:right;font-size:10px;" class="footer">
                Printed on: {{ now()->format('d M Y, h:i A') }}
            </div>

    </main>

    <script type="text/php">
        if (isset($pdf) ) {
            $x = 285;
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
