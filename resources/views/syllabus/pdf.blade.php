<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syllabus PDF</title>
    <link href="{{ public_path('css/pdf_tw.css') }}" rel="stylesheet">
    <style>
        @page {
            margin: 100px 80px 50px 50px;
            header: html_schoolHeader;
        }

        body {
            font-family: 'dejavusanscondensed', sans-serif;
            font-size: 9pt;
            color: #1a1a2e;
            direction: ltr;
        }

        /* English-only elements */
        .en {
            font-family: 'dejavusanscondensed', sans-serif;
            direction: ltr;
            text-align: left !important;
        }

        /* Urdu / mixed content — mPDF uses xbriyaz via lang="ur" */
        .ur {
            font-family: 'xbriyaz', sans-serif;
            direction: rtl;
            text-align: right !important;
            line-height: 1.8;
            font-size: 10pt;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 30px;
            right: 50px;
            background-color: white;
        }

        .page-break {
            page-break-after: always;
        }

        /* Table headers: always center */
        .data tr th {
            font-family: 'dejavusanscondensed', sans-serif;
            font-size: 10px;
            text-align: center;
            vertical-align: top;
            border: 0.5px solid;
            padding: 4px;
        }

        /* Table data cells: do NOT set text-align here — let .ur / .en control it */
        .data tr td {
            font-family: 'dejavusanscondensed', sans-serif;
            font-size: 10px;
            vertical-align: top;
            border: 0.5px solid;
            padding: 4px;
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

    <htmlpageheader name="schoolHeader">
        <!-- Header: Logo on Left, School/Grade Name on Right -->
        <table class="w-full" style="padding-bottom: 5px;">
            <tr>
                <td style="width: 70px; vertical-align: middle;">
                    <img alt="logo" src="{{ public_path('/images/logo/black.png') }}" style="width: 50px;">
                </td>
                <td>
                    <div style="font-size: 16pt; font-weight: bold; margin: 0; padding: 0;">Syllabus Outline  — Grade {{ $meta['grade']->grade_no ?? '—' }} </div>
                    <div style="font-size: 12pt; font-weight: 600; margin: 0;">Government High School 32/2L, Okara</div>  
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <main>
        <div class="custom-container">

            <div class="w-full mt-2">
                <table class="table-auto borderless xs w-full mt-1">
                    <thead class="data">
                        <tr>
                            <th style="width: 15%">Subject</th>
                            <th style="width: 28%">Term 1</th>
                            <th style="width: 28%">Term 2</th>
                            <th style="width: 29%">Term 3</th>
                        </tr>
                    </thead>
                    <tbody class="data">
                        @foreach ($syllabi as $syllabus)
                            @php
                                $term1IsUrdu = \App\Helpers\UrduHelper::hasUrdu($syllabus->term1 ?? '');
                                $term2IsUrdu = \App\Helpers\UrduHelper::hasUrdu($syllabus->term2 ?? '');
                                $term3IsUrdu = \App\Helpers\UrduHelper::hasUrdu($syllabus->term3 ?? '');

                                $urStyle = 'text-align:right; direction:rtl; font-family:xbriyaz; line-height:1.8; font-size:10pt;';
                                $enStyle = 'text-align:left;  direction:ltr; font-family:dejavusanscondensed;';

                                $term1Style = $term1IsUrdu ? $urStyle : $enStyle;
                                $term2Style = $term2IsUrdu ? $urStyle : $enStyle;
                                $term3Style = $term3IsUrdu ? $urStyle : $enStyle;
                            @endphp
                            <tr class="tr">
                                <td style="text-align:center">{{ $syllabus->subject->name ?? '—' }}</td>
                                <td><div style="{{ $term1Style }}" {{ $term1IsUrdu ? 'lang="ur"' : '' }}>{{ trim($syllabus->term1) ?? '—' }}</div></td>
                                <td><div style="{{ $term2Style }}" {{ $term2IsUrdu ? 'lang="ur"' : '' }}>{{ trim($syllabus->term2) ?? '—' }}</div></td>
                                <td><div style="{{ $term3Style }}" {{ $term3IsUrdu ? 'lang="ur"' : '' }}>{{ trim($syllabus->term3) ?? '—' }}</div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($syllabi->isEmpty())
                <div class="no-data" style="margin-top:20px; text-align:center;">No Syllabus found for this grade.</div>
            @endif

            <div style="margin-top:20px; text-align:right;font-size:10px;" class="footer">
                Printed on: {{ now()->format('d M Y, h:i A') }}
            </div>
        </div>
    </main>

</body>

</html>
