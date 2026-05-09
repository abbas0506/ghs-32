<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitual Students</title>
    <style>
        @page {
            margin: 24px 22px 30px 22px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.45;
            color: #0f172a;
            background: #ffffff;
        }

        .sheet {
            border: 1px solid #dbe7f1;
            border-radius: 20px;
            overflow: hidden;
        }

        .hero {
            padding: 22px 24px;
            background: linear-gradient(90deg, #f0fdfa 0%, #ffffff 55%, #ecfeff 100%);
            color: #0f172a;
            border-bottom: 1px solid #dbe7f1;
        }

        .eyebrow {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: #0f766e;
        }

        .title {
            margin-top: 8px;
            font-size: 22px;
            font-weight: 800;
        }

        .subtitle {
            margin-top: 6px;
            width: 76%;
            font-size: 10px;
            color: #475569;
        }

        .stats {
            width: 100%;
            margin-top: 16px;
            border-collapse: separate;
            border-spacing: 10px 0;
        }

        .stat-box {
            padding: 10px 12px;
            border: 1px solid #dbe7f1;
            border-radius: 12px;
            background: #ffffff;
            text-align: center;
        }

        .stat-label {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: #64748b;
        }

        .stat-value {
            margin-top: 4px;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .content {
            padding: 16px;
            background: #f8fafc;
        }

        .section-card {
            margin-bottom: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            page-break-inside: avoid;
        }

        .section-top {
            padding: 12px 16px;
            background: linear-gradient(90deg, #fff7ed 0%, #ffffff 50%, #ecfeff 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        .section-label {
            font-size: 8px;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: #64748b;
        }

        .section-title {
            margin-top: 6px;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-table th,
        .student-table td {
            padding: 9px 10px;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid #eef2f7;
            word-break: break-word;
        }

        .student-table th {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: #64748b;
            background: #f8fafc;
        }

        .rank {
            width: 36px;
            text-align: center;
            font-weight: 800;
            color: #c2410c;
        }

        .student-name {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
        }

        .muted {
            color: #64748b;
            font-size: 9px;
        }

        .pill {
            display: inline-block;
            margin-top: 4px;
            margin-right: 4px;
            padding: 2px 7px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 700;
        }

        .pill.red {
            background: #fef2f2;
            color: #b91c1c;
        }

        .pill.blue {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .empty {
            padding: 24px 18px;
            text-align: center;
            color: #64748b;
            font-style: italic;
        }

        .summary {
            margin-top: 10px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
        }

        .footer {
            margin-top: 8px;
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <div class="sheet">
        <div class="hero">
            <div class="eyebrow">Attendance Intelligence</div>
            <div class="title">Habitual Absentees Report</div>
            <div class="subtitle">
                Students with an absence rate above 75% from
                {{ $sessionStart->format('d M Y') }}
                to {{ $reportDate->format('d M Y') }}.
            </div>

            <table class="stats">
                <tr>
                    <td>
                        <div class="stat-box">
                            <div class="stat-label">Habitual / Total Students</div>
                            <div class="stat-value">{{ $highlightedStudents }} <span class="muted" style="font-size: 10px;">/ {{ $studentsWithAttendance }}</span></div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-box">
                            <div class="stat-label">Flagged / Total Classes</div>
                            <div class="stat-value">{{ $classesWithStudents }} <span class="muted" style="font-size: 10px;">/ {{ $sectionsCount }}</span></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="content">
            @if ($highlightedStudents === 0)
                <div class="empty">No student has recorded critical absences (>75%) in the selected session window.</div>
            @else
                <div class="section-card">
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th style="width: 30px;">#</th>
                                <th style="width: 25%;">Student</th>
                                <th style="width: 15%;">Class</th>
                                <th style="width: 15%;">Contact</th>
                                <th style="width: 15%;">Attendance</th>
                                <th style="width: 30%;">Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $globalIndex = 1; @endphp
                            @foreach ($sectionsReport as $item)
                                @foreach ($item['students'] as $student)
                                    <tr>
                                        <td class="rank">{{ $globalIndex++ }}</td>
                                        <td>
                                            <div class="student-name">{{ $student->name }}</div>
                                            <div class="muted">Father: {{ $student->father_name ?: '---' }}</div>
                                            <span class="pill red">{{ $student->absence_count }} absences</span>
                                            <span class="pill blue">{{ $student->absence_rate }}% rate</span>
                                        </td>
                                        <td>
                                            <div style="font-weight: 700;">{{ $item['class_label'] }}</div>
                                            <div class="muted">Roll: {{ $student->rollno ?: 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="muted">Phone:</div>
                                            <div>{{ $student->phone ?: '---' }}</div>
                                        </td>
                                        <td>
                                            <div>{{ $student->attendance_count }} days</div>
                                            <div class="muted">Total Marked</div>
                                        </td>
                                        <td>
                                            <div class="muted">Address:</div>
                                            <div style="font-size: 8px;">{{ $student->address ?: 'Not provided' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="summary">
                <strong>Summary:</strong>
                Reporting window {{ $sessionStart->format('d M Y') }} to {{ $reportDate->format('d M Y') }}.
                A total of {{ $highlightedStudents }} students from {{ $classesWithStudents }} classes identified.
            </div>

            <div class="footer">Generated on {{ now()->format('d M Y') }}</div>
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font('helvetica', 'normal');
            $pdf->page_text(515, 16, 'Page {PAGE_NUM} / {PAGE_COUNT}', $font, 8, [100/255, 116/255, 139/255]);
        }
    </script>
</body>

</html>
