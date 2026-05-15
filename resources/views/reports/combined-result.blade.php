<!DOCTYPE html>
<html>
<head>
    <title>Combined Result Sheet - {{ $section->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d9488; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #0d9488; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-block: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 4px; text-align: center; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; text-transform: uppercase; font-size: 8px; }
        
        .student-info { text-align: left; padding-left: 8px; font-weight: bold; }
        .marks { font-weight: bold; }
        .total-row { background-color: #f1f5f9; font-weight: bold; }
        
        .pass { color: #059669; font-weight: bold; }
        .fail { color: #dc2626; font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 8px; color: #999; padding-top: 5px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Combined Assessment Report</h1>
        <p>Class: {{ $section->name }} | Generated on {{ date('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">Sr</th>
                <th style="text-align: left; padding-left: 8px;">Student Name</th>
                @foreach($tests as $test)
                    <th>
                        <div style="font-size: 8px;">{{ $test->title }}</div>
                        <div style="font-size: 7px; color: #64748b; font-weight: normal; margin-top: 2px;">(Total: {{ $test->section_total_marks }})</div>
                    </th>
                @endforeach
                <th style="background-color: #f0fdfa;">Obt.</th>
                <th style="background-color: #f0fdfa;">Total</th>
                <th style="background-color: #f0fdfa;">%</th>
                <th style="background-color: #f0fdfa;">Grade</th>
                <th style="background-color: #f0fdfa;">Pos.</th>
                <th style="background-color: #f0fdfa;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="student-info">{{ $row['name'] }}</td>
                    @foreach($tests as $test)
                        <td class="marks">{{ $row['test_marks'][$test->id] }}</td>
                    @endforeach
                    <td style="background-color: #f0fdfa; font-weight: bold;">{{ $row['obtained'] }}</td>
                    <td style="background-color: #f0fdfa;">{{ $row['total'] }}</td>
                    <td style="background-color: #f0fdfa; font-weight: bold;">{{ $row['percentage'] }}%</td>
                    <td style="background-color: #f0fdfa;">{{ $row['grade'] }}</td>
                    <td style="background-color: #f0fdfa; font-weight: bold;">{{ $row['position'] }}</td>
                    <td style="background-color: #f0fdfa;">
                        <span class="{{ strtolower($row['status']) }}">{{ $row['status'] }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        System Generated Combined Report | Page <script type="text/php">echo $PAGE_NUM . " of " . $PAGE_COUNT;</script>
    </div>
</body>
</html>
