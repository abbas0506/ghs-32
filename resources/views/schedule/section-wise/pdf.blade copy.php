<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classs Wise Time Table</title>
    <link href="{{ public_path('css/pdf_tw.css') }}" rel="stylesheet">
    <style>
        @page {
            margin: 50px 50px 50px 80px;
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
            /* padding-bottom: 0px;
            padding-top: 0px; */
            border: 0.5px solid;
            line-height: 12px;
        }
    </style>
</head>

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
                            <td class="text-center text-xl font-bold">Class Wise Time Table {{ now()->format('Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center text-sm">Govt. High School 32/2L, Okara</td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <!-- table header -->
            <div class="mt-2">
                <table class="w-full">
                    <tbody>
                        <tr class="text-xs">
                            <td class="text-right">Printed on {{ now()->format('d-M-Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <table class="w-full mt-2 data">
                <thead>
                    <tr>
                        <th class="w-24">Class</th>
                        @foreach ($lectures as $lecture)
                            <th>{{ $lecture->lecture_no }} <br><span
                                    class="text-slate-500 font-normal">{{ $lecture->starts_at->format('H:i') }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>

                    @foreach ($sections as $section)
                        <tr>
                            <td class="font-semibold">{{ $section->name }}</td>
                            @foreach ($lectures as $lecture)
                                <td class="p-1">
                                    @foreach ($section->schedules()->havingLectureNo($lecture->lecture_no)->get() as $allocation)
                                        <div class="text-sm bg-teal-50">
                                            <div class="font-bold">{{ $allocation->subject->short_name }}</div>
                                            <div>{{ $allocation->user->profile->short_name }}</div>
                                        </div>
                                        @if (!$loop->last)
                                            <div>---</div>
                                        @endif
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                </tbody>
            </table>

    </main>

    <script type="text/php">
        if (isset($pdf) ) {
            $x = 425;
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
