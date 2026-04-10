<!DOCTYPE html>
<html>
<head>
    <title>Combined Report Cards - {{ $section->name }}</title>
    <style>
        @page { margin: 30px; }
        body { font-family: sans-serif; font-size: 10px; color: #333; line-height: 1.3; }
        .card { border: 2px solid #0d9488; padding: 20px; margin-bottom: 20px; border-radius: 12px; position: relative; overflow: hidden; height: 450px; }
        .header { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 12px; }
        .school-name { font-size: 15px; font-weight: 800; color: #0d9488; text-transform: uppercase; margin: 0; }
        .school-info { font-size: 8px; color: #666; font-style: italic; margin: 1px 0 0; }
        .report-title { font-size: 11px; font-weight: 900; background: #0d9488; color: white; display: inline-block; padding: 3px 15px; border-radius: 50px; margin-top: 5px; }
        
        .student-box { display: table; width: 100%; margin-bottom: 12px; background: #f0fdfa; padding: 10px; border-radius: 8px; }
        .student-info { display: table-cell; vertical-align: middle; }
        .student-info p { margin: 1px 0; }
        .stat-badge { display: table-cell; vertical-align: middle; text-align: right; }
        .position-badge { background: #0d9488; color: white; padding: 6px 12px; border-radius: 10px; display: inline-block; }
        .position-badge .rank { font-size: 16px; font-weight: 900; display: block; line-height: 1; }
        .position-badge .label { font-size: 7px; text-transform: uppercase; font-weight: bold; }

        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #e2e8f0; padding: 6px 5px; text-align: center; }
        th { background: #f8fafc; color: #475569; font-weight: bold; text-transform: uppercase; font-size: 8px; }
        
        .test-title { text-align: left; padding-left: 10px; font-weight: bold; color: #1e293b; }
        .total-row { background: #f1f5f9; font-weight: bold; font-size: 9px; }
        
        .footer-grid { display: table; width: 100%; margin-top: 20px; }
        .footer-col { display: table-cell; width: 33.33%; text-align: center; vertical-align: bottom; }
        .signature-line { border-top: 1px solid #ccc; width: 100px; margin: 0 auto 3px; }
        .signature-label { font-size: 8px; font-weight: bold; text-transform: uppercase; color: #666; }
        
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 50px; color: rgba(13, 148, 136, 0.03); font-weight: 900; z-index: -1; pointer-events: none; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @foreach($data as $row)
        <div class="card">
            <div class="watermark">OFFICIAL</div>
            
            <div class="header">
                <p class="school-name">Govt High School 32/2L, Okara</p>
                <p class="school-info">Education Excellence Since 1955 | EMIS 39320021</p>
                <div class="report-title">Combined Assessment Report Card</div>
            </div>

            <div class="student-box">
                <div class="student-info">
                    <p style="font-size: 14px; font-weight: 900; color: #0d9488;">{{ strtoupper($row['student']->name) }}</p>
                    <p><strong>Father's Name:</strong> {{ strtoupper($row['student']->father_name) }}</p>
                    <p><strong>Class:</strong> {{ $section->name }} | <strong>Roll No:</strong> {{ $row['student']->rollno }}</p>
                </div>
                <div class="stat-badge">
                    <div class="position-badge">
                        <span class="label">Class Position</span>
                        <span class="rank">#{{ $row['rank'] }}</span>
                        <span class="label">of {{ $section->students->count() }} Students</span>
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="text-align: left; padding-left: 10px;">Subject</th>
                        @foreach($tests as $test)
                            <th style="font-size: 7px;">{{ $test->title }}</th>
                        @endforeach
                        <th style="width: 40px; background: #f0fdfa;">Obt.</th>
                        <th style="width: 40px; background: #f0fdfa;">Max</th>
                        <th style="width: 40px; background: #f0fdfa;">%</th>
                        <th style="width: 30px; background: #f0fdfa;">G.</th>
                        <th style="width: 40px; background: #f0fdfa;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($row['subject_results'] as $res)
                        <tr>
                            <td class="test-title" style="font-size: 9px;">{{ $res['subject']->name }}</td>
                            @foreach($tests as $test)
                                <td>{{ $res['test_marks'][$test->id] }}</td>
                            @endforeach
                            <td style="font-weight: bold;">{{ $res['obtained'] }}</td>
                            <td>{{ $res['max'] }}</td>
                            <td>{{ $res['percentage'] }}%</td>
                            <td style="font-weight: bold;">{{ $res['grade'] }}</td>
                            <td style="font-size: 8px; font-weight: bold;">{{ $res['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td style="text-align: left; padding-left: 10px;">GRAND AGGREGATE</td>
                        @foreach($tests as $test)
                            <td></td>
                        @endforeach
                        <td style="color: #0d9488;">{{ $row['total_obtained'] }}</td>
                        <td>{{ $row['total_max'] }}</td>
                        <td style="color: #0d9488;">{{ $row['percentage'] }}%</td>
                        <td style="color: #0d9488;" colspan="2">
                            @php
                                $finalP = $row['percentage'];
                                if($finalP >= 90) $grade = 'A+';
                                elseif($finalP >= 80) $grade = 'A';
                                elseif($finalP >= 70) $grade = 'B';
                                elseif($finalP >= 60) $grade = 'C';
                                elseif($finalP >= 50) $grade = 'D';
                                else $grade = 'F';
                            @endphp
                            Grade: {{ $grade }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div style="margin-top: 15px; display: table; width: 100%;">
                <div style="display: table-cell; width: 75%; border: 1px dashed #e2e8f0; padding: 10px; border-radius: 8px; vertical-align: top;">
                    <p style="margin: 0 0 4px; font-weight: bold; font-size: 8px; text-transform: uppercase; color: #475569;">Instructor Remarks:</p>
                    <p style="margin: 0; color: #cbd5e1; font-style: italic; font-size: 8px;">___________________________________________________________________________________________________________________________________________________________________________</p>
                </div>
                <div style="display: table-cell; width: 25%; text-align: right; vertical-align: bottom; padding-left: 20px;">
                    <img src="{{ public_path('/images/principal/sign.png') }}" style="width: 45px; margin-bottom: -8px;"><br>
                    <div style="border-top: 1px solid #ccc; width: 100px; margin-left: auto; margin-bottom: 2px;"></div>
                    <div style="font-size: 8px; font-weight: bold; text-transform: uppercase; color: #666;">Sr. Headmaster</div>
                </div>
            </div>

            <div class="footer-grid" style="position: absolute; bottom: 20px; left: 20px; right: 20px; width: auto;">
                <div class="footer-col" style="width: 100%; text-align: left;">
                    <div class="signature-line" style="margin: 0;"></div>
                    <div class="signature-label">Class Incharge</div>
                </div>
            </div>
        </div>

        @if(($loop->index + 1) % 2 == 0 && !$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
