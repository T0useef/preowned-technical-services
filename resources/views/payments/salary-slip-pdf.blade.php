<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 14px;
            font-family: DejaVu Sans, Arial, sans-serif;
            background: #ffffff;
            color: #1f2340;
            font-size: 12px;
        }
        .sheet {
            max-width: 980px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #d9deef;
            border-radius: 8px;
            overflow: hidden;
        }
        .strip { height: 6px; background: #080059; }
        .inner { padding: 12px 14px 14px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .header td { padding: 4px; vertical-align: top; }
        .brand { width: 36%; }
        .title { width: 28%; text-align: center; }
        .meta { width: 36%; text-align: right; }
        .logo {
            display: inline-block;
            width: 34px;
            height: 34px;
            line-height: 34px;
            text-align: center;
            border-radius: 50%;
            background: #f4d59f;
            border: 1px solid #d8ddf0;
            color: #080059;
            font-weight: 800;
            margin-right: 8px;
            vertical-align: middle;
        }
        .brand-name { display: inline-block; vertical-align: middle; color: #080059; font-weight: 700; line-height: 1.25; }
        .brand-sub { display: block; color: #6b7299; font-size: 10px; }
        .slip-title {
            margin: 2px 0;
            font-size: 34px;
            line-height: 1;
            font-weight: 900;
            letter-spacing: 3px;
            color: #080059;
            font-family: Georgia, "Times New Roman", serif;
        }
        .slip-sub { color: #6b7299; font-size: 10px; letter-spacing: 0.8px; }
        .meta-row { margin-bottom: 2px; }
        .meta-row strong { color: #080059; }

        .cards td {
            border: 1px solid #d8ddf0;
            background: #f7f9ff;
            border-radius: 6px;
            padding: 8px 10px;
            width: 33.33%;
        }
        .card-label { color: #6b7299; font-size: 10px; margin-bottom: 2px; }
        .card-value { color: #10153a; font-size: 17px; font-weight: 800; }
        .card-value.sm { font-size: 15px; }
        .payable {
            background: #080059 !important;
            border-color: #080059 !important;
        }
        .payable .card-label,
        .payable .card-value { color: #fff; }

        .section-title {
            margin: 10px 0 6px;
            color: #080059;
            font-size: 16px;
            font-weight: 800;
        }

        .summary th, .summary td,
        .details th, .details td {
            border: 1px solid #d8ddf0;
            padding: 7px;
            font-size: 11px;
        }
        .summary th, .details th {
            background: #eff2ff;
            color: #1f2653;
            text-align: left;
            font-weight: 700;
        }
        .summary td:nth-child(2), .summary td:nth-child(4),
        .num { text-align: right; color: #27306a; font-weight: 600; }
        .summary td:first-child, .summary td:nth-child(3) { background: #fafbff; }
        .payable-row td { background: #f7f8ff; font-weight: 800; font-size: 12px; }

        .status {
            display: inline-block;
            border-radius: 10px;
            padding: 2px 7px;
            font-size: 10px;
            font-weight: 700;
        }
        .present { background: #d9f3e8; color: #126d49; }
        .absent { background: #ffe1e7; color: #a9344f; }
        .print-hint {
            margin-top: 8px;
            border-top: 1px dashed #c6cbe8;
            padding-top: 6px;
            color: #5e648f;
            font-size: 10px;
        }
        @media print {
            body { padding: 0; background: #fff; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .sheet { border: none; border-radius: 0; max-width: 100%; }
            .print-hint { display: none; }
            tr, .section-title { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="strip"></div>
        <div class="inner">
            <table class="header">
                <tr>
                    <td class="brand">
                        <span class="logo">TS</span>
                        <span class="brand-name">
                            Preowned Technical Services
                            <span class="brand-sub">Engineering & Field Workforce</span>
                        </span>
                    </td>
                    <td class="title">
                        <div class="slip-title">SALARY SLIP</div>
                        <div class="slip-sub">Monthly Payment Statement</div>
                    </td>
                    <td class="meta">
                        <div class="meta-row"><strong>Month:</strong> {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</div>
                        <div class="meta-row"><strong>Generated:</strong> {{ now()->format('d/m/Y h:i A') }}</div>
                        <div class="meta-row"><strong>Allowed Off:</strong> {{ $allowedOff }}</div>
                    </td>
                </tr>
            </table>

            <table class="cards">
                <tr>
                    <td>
                        <div class="card-label">Employee Name</div>
                        <div class="card-value sm">{{ $user->name }}</div>
                    </td>
                    <td>
                        <div class="card-label">Email</div>
                        <div class="card-value sm">{{ $user->email }}</div>
                    </td>
                    <td>
                        <div class="card-label">Monthly Salary</div>
                        <div class="card-value">{{ number_format($summary['monthly_salary'], 2) }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="card-label">Final Salary</div>
                        <div class="card-value">{{ number_format($summary['final_salary'], 2) }}</div>
                    </td>
                    <td>
                        <div class="card-label">Advance Deduction</div>
                        <div class="card-value">{{ number_format($summary['advance_deduction'] ?? 0, 2) }}</div>
                    </td>
                    <td class="payable">
                        <div class="card-label">Payable Salary</div>
                        <div class="card-value">{{ number_format($summary['payable_salary'] ?? $summary['final_salary'], 2) }}</div>
                    </td>
                </tr>
            </table>

            <div class="section-title">Salary Summary</div>
            <table class="summary">
                <tbody>
                    <tr>
                        <td><strong>Days in Month</strong></td>
                        <td>{{ $summary['days_in_month'] }}</td>
                        <td><strong>Presents</strong></td>
                        <td>{{ $summary['presents'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Offs</strong></td>
                        <td>{{ $summary['offs'] }}</td>
                        <td><strong>Extra Offs</strong></td>
                        <td>{{ $summary['extra_off'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Single Day Salary</strong></td>
                        <td>{{ number_format($summary['single_day_salary'], 2) }}</td>
                        <td><strong>Hourly Rate</strong></td>
                        <td>{{ number_format($summary['hourly_rate'], 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total OT Hours</strong></td>
                        <td>{{ number_format($summary['overtime_hours'], 2) }}</td>
                        <td><strong>Adjusted OT Hours</strong></td>
                        <td>{{ number_format($summary['adjusted_overtime_hours'], 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>OT Amount</strong></td>
                        <td>{{ number_format($summary['overtime_amount'], 2) }}</td>
                        <td><strong>Off Deduction</strong></td>
                        <td>{{ number_format($summary['deduction'], 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Advance Taken</strong></td>
                        <td>{{ number_format(max(($summary['net_advance'] ?? 0), 0), 2) }}</td>
                        <td><strong>Amount Paid By Now</strong></td>
                        <td>{{ number_format($summary['advance_deduction'] ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Remaining Advance</strong></td>
                        <td>{{ number_format(max((max(($summary['net_advance'] ?? 0), 0) - ($summary['advance_deduction'] ?? 0)), 0), 2) }}</td>
                        <td><strong>Final Salary</strong></td>
                        <td>{{ number_format($summary['final_salary'], 2) }}</td>
                    </tr>
                    <tr class="payable-row">
                        <td colspan="2"><strong>Payable Salary</strong></td>
                        <td colspan="2" class="num"><strong>{{ number_format($summary['payable_salary'] ?? $summary['final_salary'], 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="section-title">Day-wise Work Details</div>
            <table class="details">
                <thead>
                    <tr>
                        <th style="width: 78px;">Date</th>
                        <th style="width: 70px;">Status</th>
                        <th>Project(s)</th>
                        <th style="width: 64px;" class="num">Work</th>
                        <th style="width: 64px;" class="num">OT</th>
                        <th style="width: 72px;" class="num">Adj. OT</th>
                        <th style="width: 120px;">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyDetails as $day)
                        <tr>
                            <td>{{ $day['date'] }}</td>
                            <td>
                                <span class="status {{ $day['status'] === 'present' ? 'present' : 'absent' }}">
                                    {{ strtoupper($day['status']) }}
                                </span>
                            </td>
                            <td>{{ $day['projects'] }}</td>
                            <td class="num">{{ number_format((float) $day['working_hours'], 2) }}</td>
                            <td class="num">{{ number_format((float) $day['overtime_hours'], 2) }}</td>
                            <td class="num">{{ number_format((float) $day['adjusted_overtime_hours'], 2) }}</td>
                            <td>{{ $day['description'] ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="print-hint">Preview mode: use browser print (Ctrl+P) and choose "Save as PDF".</p>
        </div>
    </div>
</body>
</html>
