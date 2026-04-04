<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Cards</title>
    <link href="{{ public_path('css/pdf_tw.css') }}" rel="stylesheet">

    <style>
        @page {
            margin: 10px;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .data tr th,
        .data tr td {
            font-size: 12px;
            text-align: center;
        }

        .card-container {
            position: relative;
            overflow: hidden;
            background-color: white;
            border: 1px solid;
        }

        .card-logo-bg {
            position: absolute;
            top: 140px;
            /* Adjust based on where you want the image */
            left: 40px;
            width: 130px;
            opacity: 0.25;
        }

        .wave-pattern {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 300px;
            opacity: 0.75;
        }

        .card-content {
            position: relative;
            height: 300px;
            width: 200px;
            z-index: 1;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@php
    $roman = config('global.romans');
    $i = 0;
    $numOfCardsPerRow = 3;
@endphp

<body>
    <main>
        <div class="custom-container">
            <table class="w-full">
                <tbody>
                    <tr class="text-xs">
                        <td class="text-left">Student Cards</td>
                        <td class="text-right">Printed on {{ now()->format('d-M-Y') }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table-auto w-full mt-2" cellspacing="0">
                <tbody class="data">
                    @foreach ($students as $student)
                        @if ($i % $numOfCardsPerRow == 0)
                            <tr class="text-sm">
                        @endif

                        <td class="p-2">
                            <div class="p-2 card-container">
                                <!-- Background Logo -->
                                <img src="{{ public_path('images/logo/black.png') }}" class="card-logo-bg"
                                    alt="Background Logo">
                                <!-- wave patten -->
                                <img src="{{ public_path('images/bg/waves.png') }}" class="wave-pattern"
                                    alt="Wave pattern">

                                <!-- Foreground Content -->
                                <div class="card-content">
                                    <div><img src="{{ public_path('images/logo/ghs-32.png') }}" alt=""
                                            width="36px" height="36px"></div>
                                    <p class="text-xs mt-2">Govt. High School 32/2L, Okara</p>

                                    <div class="w-24 h-24 m-auto mt-3">
                                        @if ($student->photo)
                                            <img src="{{ public_path('storage/' . $student->photo) }}"
                                                style="width:75px; height:75px; border-radius:10%; border:0.5px solid #fff; object-fit:cover;">
                                        @else
                                            <span style="color: #999;">No Photo</span>
                                        @endif
                                    </div>

                                    <div class="font-bold" style="color:rgb(12, 15, 12)">
                                        {{ Str::upper($student->name) }}</div>
                                    <div class="text-md mt-2">Class
                                        {{ $student->section->level }}-{{ $student->section->name }},
                                        {{ $student->group?->name }}</div>
                                    <div class="text-xs mt-1">{{ date('Y') }}-{{ date('y') + 1 }}</div>

                                    <table width="100%" style="margin-top: 24px;">
                                        <tr>
                                            <td style="text-align: left;">
                                                {!! DNS2D::getBarcodeHTML($student->bform, 'QRCODE', 2, 2) !!}
                                            </td>
                                            <td style="text-align: right; padding-right:8px">
                                                <div style="text-align: center; display: inline-block;">
                                                    <img src="{{ public_path('images/principal/sign.png') }}"
                                                        alt="" width="32px" height="32px">
                                                    <span
                                                        style="display: block; border-top: 1px solid #000; width: 80px; margin-top: 5px;"></span>
                                                    <span>Principal</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>

                        @if ($i % $numOfCardsPerRow == $numOfCardsPerRow - 1)
                            </tr>
                        @endif
                        @php $i++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 285;
            $y = 20;
            $text = "{PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica", "bold");
            $size = 6;
            $color = [0, 0, 0];
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</body>

</html>
