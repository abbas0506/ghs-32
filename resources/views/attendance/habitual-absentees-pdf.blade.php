<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitual Absentees</title>
    <style>
        @page {
            margin: 28px 26px 34px 26px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.45;
            background: #ffffff;
        }

        .page-shell {
            border: 1px solid #dbe7f1;
            border-radius: 20px;
            overflow: hidden;
        }

        .hero {
            padding: 24px 28px;
            background: linear-gradient(135deg, #020617 0%, #0f172a 45%, #0f766e 100%);
            color: #fff;
        }

        .eyebrow {
            font-size: 10px;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: #a5f3fc;
        }

        .hero-title {
            margin: 10px 0 6px;
            font-size: 24px;
            font-weight: 800;
        }

        .hero-subtitle {
            width: 68%;
            font-size: 11px;
            color: #dbeafe;
        }

        .meta-table {
            width: 100%;
            margin-top: 18px;
            border-collapse: separate;
            border-spacing: 10px 0;
        }

        .meta-box {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 12px 14px;
            text-align: center;
        }

        .meta-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.25em;
            color: #bae6fd;
        }

        .meta-value {
            margin-top: 4px;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
        }

        .content {
            padding: 18px 20px 10px;
            background: #f8fafc;
        }

        .section-card {
            margin-bottom: 16px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
        }

        .section-header {
            padding: 14px 18px;
            background: linear-gradient(90deg, #fff7ed 0%, #ffffff 52%, #ecfeff 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        .section-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.24em;
            color: #64748b;
        }

        .section-title {
            margin-top: 6px;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-table th,
        .student-table td {
            padding: 10px 12px;
            vertical-align: top;
            border-bottom: 1px solid #eef2f7;
        }

        .student-table th {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #64748b;
            text-align: left;
            background: #f8fafc;
        }

        .rank {
            width: 34px;
            text-align: center;
            font-weight: 800;
            color: #c2410c;
        }

        .student-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .muted {
            color: #64748b;
            font-size: 11px;
        }

        .stat-pill {
            display: inline-block;
            margin-top: 4px;
            margin-right: 6px;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .stat-pill.red {
            background: #fef2f2;
            color: #b91c1c;
        }

        .empty {
            padding: 20px 18px;
            color: #64748b;
            text-align: center;
            font-style: italic;
        }

        .footer {
            margin-top: 10px;
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <div class="page-shell">
        <div class="hero">
            <div class="eyebrow">Attendance Intelligence</div>
            <div class="hero-title">Habitual Absentees by Class</div>
            <div class="hero-subtitle">
                Top 3 students with the highest absence count in each class from {{ $sessionStart->format('d M Y') }} to
                {{ $reportDate->format('d M Y') }}.
                Printed on {{ now()->format('d M Y') }}.
            </div>

            <table class="meta-table">
                <tr>
                    <td>
                        <div class="meta-box">
                            <div class="meta-label">Classes covered</div>
                            <div class="meta-value">{{ $sectionsReport->count() }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="meta-box">
                            <div class="meta-label">Classes flagged</div>
                            <div class="meta-value">{{ $classesWithAbsentees }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="meta-box">
                            <div class="meta-label">Students highlighted</div>
                            <div class="meta-value">{{ $highlightedStudents }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="content">
            @foreach ($sectionsReport as $item)
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-label">Class</div>
                        <div class="section-title">{{ $item['class_label'] }}</div>
                    </div>

                    @if ($item['students']->isEmpty())
                        <div class="empty">No recorded absences for this class in the selected session window.</div>
                    @else
                        <table class="student-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th style="width: 28%;">Student</th>
                                    <th style="width: 12%;">Roll No</th>
                                    <th style="width: 16%;">Phone</th>
                                    <th style="width: 26%;">Address</th>
                                    <th style="width: 18%;">Absence Profile</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item['students'] as $index => $student)
                                    <tr>
                                        <td class="rank">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="student-name">{{ $student->name }}</div>
                                            <div class="muted">Father: {{ $student->father_name ?: 'N/A' }}</div>
                                            <div class="muted">Class: {{ $item['class_label'] }}</div>
                                        </td>
                                        <td>{{ $student->rollno ?: 'N/A' }}</td>
                                        <td>{{ $student->phone ?: 'Not provided' }}</td>
                                        <td>{{ $student->address ?: 'Not provided' }}</td>
                                        <td>
                                            <span class="stat-pill red">{{ $student->absence_count }} absences</span>
                                            <span class="stat-pill">{{ $student->absence_rate }}% rate</span>
                                            <div class="muted">{{ $student->attendance_count }} marked days</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endforeach

            <div class="footer">Generated on {{ now()->format('d M Y') }}</div>
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("helvetica", "normal");
            $pdf->page_text(760, 18, "Page {PAGE_NUM} / {PAGE_COUNT}", $font, 9, [100/255, 116/255, 139/255]);
        }
    </script>
</body>

</html>
