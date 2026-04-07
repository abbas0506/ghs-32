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

        /* Urdu / mixed content — Noto Nastaleeq (Noto Nastaliq Urdu) */
        .ur {
            font-family: 'notanastaliqurdu', sans-serif;
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
            line-height: 1.15;
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
                    <img alt="logo" src="{{ public_path('/images/logo/ghs-32.png') }}" style="width: 50px;">
                </td>
                <td style="vertical-align: middle; text-align: center;">
                    <h2 style="font-size: 16pt; font-weight: bold; margin: 0; padding: 0;">Lesson Plan — Grade
                        {{ $meta['grade']->name ?? '—' }}</h2>
                    <h4
                        style="font-size: 12pt; font-weight:600; margin: 10px 0 0 0; padding: 0; text-decoration: underline;">
                        Govt. High School 32/2L, Okara
                    </h4>
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <main>
        <div class="custom-container">


            @forelse($lessons as $lessonNo => $group)
                <div class="w-full mt-2">
                    <div class="text-sm font-bold"><u>Lesson Plan # {{ $lessonNo }}</u></div>
                    <table class="table-auto borderless sm w-full mt-1">
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
                                @php
                                    // Determine language for each field
                                    $titleIsUrdu = \App\Helpers\UrduHelper::hasUrdu($lesson->title ?? '');
                                    $objectiveIsUrdu = \App\Helpers\UrduHelper::hasUrdu($lesson->objective ?? '');
                                    $activityIsUrdu = \App\Helpers\UrduHelper::hasUrdu($lesson->activity ?? '');
                                    $homeworkIsUrdu = \App\Helpers\UrduHelper::hasUrdu($lesson->homework ?? '');
                                    $remarksIsUrdu = \App\Helpers\UrduHelper::hasUrdu($lesson->remarks ?? '');

                                    // Inline style strings — inline styles override CSS cascade & mPDF bidi
                                    $urStyle =
                                        'text-align:right; direction:rtl; font-family:notanastaliqurdu; line-height:1.8; font-size:10pt;';
                                    $enStyle = 'text-align:left;  direction:ltr; font-family:dejavusanscondensed;';

                                    $titleStyle = $titleIsUrdu ? $urStyle : $enStyle;
                                    $objectiveStyle = $objectiveIsUrdu ? $urStyle : $enStyle;
                                    $activityStyle = $activityIsUrdu ? $urStyle : $enStyle;
                                    $homeworkStyle = $homeworkIsUrdu ? $urStyle : $enStyle;
                                    $remarksStyle = $remarksIsUrdu ? $urStyle : $enStyle;
                                @endphp
                                <tr class="tr">
                                    <td style="text-align:center">{{ $lesson->subject->name ?? '—' }}</td>
                                    <td>
                                        @if ($lesson->title)
                                            <div style="font-weight:400; {{ $titleStyle }}"
                                                {{ $titleIsUrdu ? 'lang="ur"' : '' }}>{{ $lesson->title ?? '—' }}</div>
                                            <div style="{{ $objectiveStyle }}"
                                                {{ $objectiveIsUrdu ? 'lang="ur"' : '' }}>
                                                {{ $lesson->objective ?? '—' }}</div>
                                            <ul>
                                                @foreach ($lesson->cues as $cue)
                                                    @php
                                                        $cueIsUrdu = \App\Helpers\UrduHelper::hasUrdu(
                                                            $cue->content ?? '',
                                                        );
                                                        $cueStyle = $cueIsUrdu ? $urStyle : $enStyle;
                                                    @endphp
                                                    <li style="{{ $cueStyle }}"
                                                        {{ $cueIsUrdu ? 'lang="ur"' : '' }}>{{ $cue->content }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div style="{{ $enStyle }}">No title</div>
                                        @endif
                                    </td>
                                    <td style="{{ $activityStyle }}" {{ $activityIsUrdu ? 'lang="ur"' : '' }}>
                                        {{ $lesson->activity ?? '—' }}</td>
                                    <td style="{{ $homeworkStyle }}" {{ $homeworkIsUrdu ? 'lang="ur"' : '' }}>
                                        {{ $lesson->homework ?? '—' }}</td>
                                    <td style="{{ $remarksStyle }}" {{ $remarksIsUrdu ? 'lang="ur"' : '' }}>
                                        {{ $lesson->remarks ?? '—' }}</td>
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

</body>

</html>
