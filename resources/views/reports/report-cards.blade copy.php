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
            bottom: 20px;
            left: 0px;
            right: 0px;
            background-color: white;
            height: 20px;
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

            @foreach ($section->students as $student)
                @if (($loop->index + 1) % 2 == 0)
                    <div class="mt-16"></div>
                @endif
                <div class="w-1/2 mx-auto">
                    <div class="relative">
                        <div class="absolute"><img alt="logo" src="{{ public_path('/images/logo/ghs-32.png') }}"
                                class="w-12"></div>
                    </div>
                    <table class="w-full">
                        <tbody>
                            <tr>
                                <td class="text-center text-lg font-bold">Report Card - {{ $test->title }}</td>
                            </tr>
                            <tr>
                                <td class="text-center text-sm font-bold">Govt High School 32/2L, Okara</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @php
                    $obtained = $student->results()->test($test->id)->get()->sum('obtained_marks');
                    $total = $student->maximumMarks($test->id);
                    $presences = $student->attendances->where('status', 1)->count();
                    $attendances = $student->attendances->count();
                    //$attendanceBonus = round(($presences / $attendances) * 10, 1);
                @endphp

                <table class="table-auto w-full mt-4" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="w-24"></th>
                            <th class="w-6"></th>
                            <th class="w-48"></th>
                            <th rowspan="4" class="w-32"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="data">
                        <tr>
                            <td class="text-left font-bold">Student</td>
                            <td>:</td>
                            <td class="text-left">{{ ucwords(strtolower($student->name)) }}</td>
                            <td rowspan="3" class="text-left px-4 bg-slate-300">
                                @if ($total)
                                    <div><span class="font-bold">Attendance:</span>
                                        {{ $presences }} /
                                        {{ $attendances }}
                                    </div>
                                    <div><span class="font-bold">Obtained:</span> {{ $obtained }} /
                                        {{ $total }} = {{ round(($obtained / $total) * 100, 2) }} %
                                    </div>
                                    <div> <span class="font-bold w-24">Position:</span>
                                        {{ $student->testRank($sortedResult) }} /
                                        {{ $student->section->students->count() }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left font-bold">Father Name</td>
                            <td>:</td>
                            <td class="text-left">{{ ucwords(strtolower($student->father_name)) }}</td>
                        </tr>
                        <tr>
                            <td class="text-left font-bold">Class</td>
                            <td>:</td>
                            <td class="text-left">{{ $section->name }} / Roll # {{ $student->rollno }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table-auto w-full mt-4" cellspacing="0">
                    <thead class="data">
                        <tr class="border">
                            <th>Sr</th>
                            <th>Subject</th>
                            <th>Total</th>
                            <th>Marks</th>
                            <th>Percentage</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="data">

                        @foreach ($student->results()->test($test->id)->get() as $result)
                            <tr class="border">
                                <td>{{ $loop->index + 1 }}</td>
                                <td class="text-left">{{ $result->testSubject->subject->name }}</td>
                                <td>{{ $result->testSubject->max_marks }}</td>
                                <td>{{ $result->obtained_marks }}</td>
                                @php
                                    $percentage = round(
                                        ($result->obtained_marks / $result->testSubject->max_marks) * 100,
                                        2,
                                    );
                                @endphp
                                <td>{{ $percentage }} %</td>
                                <td>
                                    @if ($percentage >= 33)
                                        Pass
                                    @else
                                        Fail
                                    @endif
                                </td>
                            </tr>
                            @php
                            @endphp
                        @endforeach

                    </tbody>
                </table>
                <table class="w-full mt-2">
                    <thead>
                        <tr>
                            <th class="w-32"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="w-32"></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr>
                            <td></td>
                            <td colspan="3"></td>
                            <td><img src="{{ public_path('/images/principal/sign.png') }}" alt=""
                                    class="w-12"></td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>
                                <div class="pt-2" style="border-top:solid 0.1px">Sr. Headmaster</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @if (($loop->index + 1) % 2 == 0 && !$loop->last)
                    <div class="page-break"></div>
                @else
                    <p></p>
                    <p></p>
                @endif
            @endforeach
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
