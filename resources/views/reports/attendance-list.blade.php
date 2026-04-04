<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance List</title>
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
            line-height: 14px;
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
                            <td class="text-center text-xl font-bold">Attendance List</td>
                        </tr>
                        <tr>
                            <td class="text-center text-sm">Govt. High School 32/2L, Okara</td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <!-- table header -->
            <div class="mt-8">
                <table class="w-full">
                    <tbody>
                        <tr class="">
                            <td class="text-left font-bold text-base">Class {{ $section->name }} | Total Students:
                                {{ $section->students->count() }}</td>
                            <td class="text-right text-xs">Printed on {{ now()->format('d-M-Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @php $i=1; @endphp

            <table class="w-full mt-2 data">
                <thead>
                    <tr style="background-color: #bbb;">
                        <th class="w-8">Roll#</th>
                        <th>Name</th>
                        <th class="border w-8"></th>
                        <th class="border w-8"></th>
                        <th class="border w-8"></th>
                        <th class="border w-8"></th>
                        <th class="border w-8"></th>
                        <th class="border w-8"></th>
                        <th class="border w-8"></th>
                        <th class="border w-8"></th>
                        <th class="border w-8"></th>
                        <th class="border w-8"></th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($section->students->sortBy('rollno') as $student)
                        <tr class="text-base">
                            <td>{{ $student->rollno }}</td>
                            <td style="text-align: left !important; padding:1px 6px;">
                                {{ ucwords(strtolower($student->name)) }} s/o
                                {{ ucwords(strtolower($student->father_name)) }}
                            </td>
                            <td class="border"></td>
                            <td class="border"></td>
                            <td class="border"></td>
                            <td class="border"></td>
                            <td class="border"></td>
                            <td class="border"></td>
                            <td class="border"></td>
                            <td class="border"></td>
                            <td class="border"></td>
                            <td class="border"></td>

                        </tr>
                        @php $i++; @endphp
                    @endforeach

                </tbody>
            </table>

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
