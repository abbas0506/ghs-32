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
                            <td class="text-center text-lg font-bold">{{ $testSubject->subject->name }},
                                {{ $testSubject->section->name }} </td>
                        </tr>
                        <tr>
                            <td class="text-center text-base">{{ $testSubject->test->title }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Position Holders --}}
            <div class="font-bold text-sm mt-4 underline">Top 3</div>
            <table class="table-auto borderless w-full mt-1" cellspacing="0">
                <thead class="data">
                    <tr class="">
                        <th class="w-12"></th>
                        <th class=""></th>
                        <th class="w-16"></th>
                        <th class="w-16"></th>
                    </tr>
                </thead>
                <tbody class="data">

                    @foreach ($testSubject->results->sortByDesc('obtained_marks')->take(3) as $result)
                        <!-- calculate percentage -->
                        @php $percentage=round($result->obtained_marks/$testSubject->max_marks*100,1); @endphp
                        <tr class="">
                            {{-- <td>{{ Number::ordinal($loop->index + 1) }}</td> --}}
                            <td>{{ $loop->index + 1 }}</td>
                            <td class="text-left">{{ ucwords(strtolower($result->student->name)) }} s/o
                                {{ ucwords(strtolower($result->student->father_name)) }}</td>
                            <td>{{ $result->obtained_marks }}</td>
                            <td>{{ $percentage }} %</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr class="w-8 m-auto mt-4">

            {{-- Overall results --}}
            <table class="w-full mt-3">
                <tbody>
                    <tr>
                        <td class="text-left text-sm font-bold">Result Detail</td>
                        <td class="text-right text-sm font-bold">Total Marks: {{ $testSubject->max_marks }}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table-auto w-full mt-1" cellspacing="0">
                <thead class="data">
                    <tr class="border">
                        <th class="w-12">Roll #</th>
                        <th class="">Student Name</th>
                        <th class="w-16">Obtained</th>
                        <th class="w-16">Percentage</th>
                        <th class="w-16">Status</th>
                    </tr>
                </thead>
                <tbody class="data">

                    @foreach ($testSubject->results->sortBy('rollno') as $result)
                        <!-- calculate percentage -->
                        @php $percentage=round($result->obtained_marks/$testSubject->max_marks*100,1); @endphp
                        <tr class="border">
                            <td>{{ $result->student->rollno }}</td>
                            <td class="text-left">{{ ucwords(strtolower($result->student->name)) }}</td>
                            <td>{{ $result->obtained_marks }}</td>
                            <td>{{ $percentage }} %</td>
                            <td>
                                @if ($percentage >= 33)
                                    Pass
                                @else
                                    Fail
                                @endif
                            </td>

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
