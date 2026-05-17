<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Incident Report - {{ $report->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .report-info {
            margin-bottom: 30px;
        }
        .report-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-info th {
            text-align: left;
            width: 30%;
            padding: 10px;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .report-info td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .priority-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority-critical { background-color: #dc2626; color: #fff; }
        .priority-high { background-color: #f97316; color: #fff; }
        .priority-medium { background-color: #3b82f6; color: #fff; }
        .priority-low { background-color: #64748b; color: #fff; }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 30px;
            margin-bottom: 15px;
            border-left: 4px solid #1e293b;
            padding-left: 10px;
        }
        .description {
            background-color: #fff;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        .evidence-img {
            max-width: 100%;
            height: auto;
            margin-top: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">SafetyNet.</div>
        <p style="margin: 5px 0; font-size: 14px; color: #64748b;">Community Driven Neighborhood Security System</p>
        <h2 style="margin-top: 20px; color: #1e293b;">OFFICIAL INCIDENT CASE FILE</h2>
    </div>

    <div class="report-info">
        <table>
            <tr>
                <th>Case ID:</th>
                <td>#SN-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <th>Incident Title:</th>
                <td style="font-weight: bold;">{{ $report->title }}</td>
            </tr>
            <tr>
                <th>Incident Type:</th>
                <td>{{ ucfirst($report->type) }}</td>
            </tr>
            <tr>
                <th>Threat Priority:</th>
                <td>
                    <span class="priority-badge priority-{{ $report->priority }}">
                        {{ $report->priority }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Reported By:</th>
                <td>{{ $report->user->name }}</td>
            </tr>
            <tr>
                <th>Location:</th>
                <td>{{ $report->location }}</td>
            </tr>
            <tr>
                <th>Date & Time:</th>
                <td>{{ \Carbon\Carbon::parse($report->datetime)->format('F d, Y - h:i A') }}</td>
            </tr>
            <tr>
                <th>Current Status:</th>
                <td><strong>{{ strtoupper($report->status) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="section-title">Incident Description</div>
    <div class="description">
        {{ $report->description }}
    </div>

    @if($report->image)
    <div class="section-title">Visual Evidence</div>
    <div style="text-align: center;">
        <img src="{{ public_path('storage/' . $report->image) }}" class="evidence-img">
    </div>
    @endif

    <div class="footer">
        <p>This is a computer-generated official document from the SafetyNet System.</p>
        <p>Generated on: {{ date('F d, Y H:i:s') }} | Security Verification Code: {{ md5($report->id . $report->created_at) }}</p>
    </div>
</body>
</html>
