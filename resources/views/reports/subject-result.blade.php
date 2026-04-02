<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Cards</title>
    <link href="{{ public_path('css/pdf_tw.css') }}" rel="stylesheet">
    <style>
        @page {
            margin: 50px 80px;
        }

        .footer {
            position: fixed;
            bottom: 50px;
            left: 0px;
            right: 0px;
            background-color: white;
            height: 50px;
        }

        .page-break {
            page-break-after: always;
        }

        .data tr th,
        .data tr td {
            font-size: 12px;
            padding-left: 4px;
            padding-right: 4px;
            /* text-align: left; */
        }

        .border {
            border: solid 0.1px;
        }
    </style>
</head>
@php
    $roman = config('global.romans');
@endphp


<body>

    <main>
        <div class="container">
            <!-- front page ... section gazzet -->
            <div class="w-1/2 mx-auto">
                <div class="relative">
                    <div class="absolute"><img alt="logo" src="{{ public_path('/images/logo/ghs-32.png') }}"
                            class="w-16"></div>
                </div>
                <table class="w-full">
                    <tbody>
                        <tr>
                            <td class="text-center text-lg">{{ $testSubject->test->title }}</td>
                        </tr>
                        <tr>
                            <td class="text-center text-base">{{ $testSubject->subject->name }},
                                {{ $testSubject->section->name }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- ── TOP 3 ── --}}
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

            {{-- ── FULL RESULT TABLE ── --}}
            <h5 style="font-size: 12px;">
                {{ $subject->name }} — {{ $section->name }}
                | Lecture: {{ $testSubject->lecture_no }}
                | Date: {{ $testSubject->test_date }}
                | Max Marks: {{ $testSubject->max_marks }}
            </h5>
            <table class="table-auto text-xs w-full">
                <thead>
                    <tr>
                        <th class="w-12">Roll No</th>
                        <th>Name</th>
                        <th class="w-16">Obtained</th>
                        <th class="w-16">Total</th>
                        <th class="w-16">%</th>
                        <th class="w-16">Grade</th>
                        <th class="w-16">Position</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $student)
                        <tr>
                            <td>{{ $student['rollno'] }}</td>
                            <td>{{ $student['name'] }}</td>
                            <td>{{ $student['obtained'] }}</td>
                            <td>{{ $student['total'] }}</td>
                            <td>{{ $student['percentage'] }}%</td>
                            <td>{{ $student['grade'] }}</td>
                            <td>{{ $student['position'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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
