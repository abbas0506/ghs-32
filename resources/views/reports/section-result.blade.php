<!DOCTYPE html>
<html lang="en">

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
            font-size: 11px;
            text-align: center;
            /* padding-bottom: 2px; */
            border: 0.5px solid;
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
                            <td class="text-center text-xl font-bold">{{ $test->title }} </td>
                        </tr>
                        <tr>
                            <td class="text-center text-sm">Govt. High School 32/2L, Okara</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- table header -->
            <h4 class="mt-4 text-center underline underline-offset-2">Class {{ $section->name }}</h4>

            {{-- Position Holders --}}
            <div class="font-bold text-sm mt-4 underline">Top 3 Postion Holders</div>
            <table class="table-auto border-collapse w-full mt-1 text-xs" cellspacing="0">
                <thead>
                    <tr class="">
                        <th class="w-12"></th>
                        <th class=""></th>
                        <th class="w-16"></th>
                        <th class="w-16"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topStudents as $student)
                        <tr>
                            <td><strong>#{{ $student['position'] }}</strong></td>
                            <td class="text-left">{{ $student['name'] }} S/O {{ $student['father'] }} (Roll No:
                                {{ $student['rollno'] }})
                            </td>
                            <td>{{ $student['obtained'] }}/{{ $student['total'] }}</td>
                            <td>{{ $student['percentage'] }}% — {{ $student['grade'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr class="w-8 m-auto mt-4">

            {{-- All students --}}
            <table class="w-full mt-4 border-collapse border text-xs">
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>

                        @foreach ($subjects as $subject)
                            <th>
                                {{ $subject->short_name }}<br>
                                <small>({{ $subjectMaxMarks[$subject->id] ?? 0 }})</small>
                            </th>
                        @endforeach

                        <th>Obtained</th>
                        <th>Total</th>
                        <th>%</th>
                        <th>Grade</th>
                        <th>Position</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $row['rollno'] }}</td>
                            <td>{{ Str::upper($row['name']) }}</td>

                            @foreach ($subjects as $subject)
                                <td>
                                    {{ $row['subjects'][$subject->id] > 0 ? $row['subjects'][$subject->id] : '-' }}
                                </td>
                            @endforeach

                            <td>{{ $row['obtained'] }}</td>
                            <td>{{ $row['total'] }}</td>
                            <td>{{ $row['percentage'] }}%</td>
                            <td>{{ $row['grade'] }}</td>
                            <td>{{ $row['position'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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
