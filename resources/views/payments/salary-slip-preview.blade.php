<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip Preview</title>
    <style>
        * { box-sizing: border-box; }
        :root {
            --brand-navy: #080059;
            --brand-navy-soft: #1c109f;
            --brand-gold: #eabc73;
            --ink: #1f2340;
            --muted: #69709a;
            --line: #dfe3f2;
            --bg-soft: #f5f7ff;
        }
        body {
            margin: 0;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            color: var(--ink);
            background: #ffffff;
            padding: 18px;
        }
        .page {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 14px 34px rgba(8, 0, 89, 0.1);
        }
        .head-band {
            height: 5px;
            background: linear-gradient(100deg, var(--brand-navy), var(--brand-navy-soft), var(--brand-gold));
        }
        .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(155deg, #ffffff, #fafbff);
        }
        .logo-wrap {
            width: 250px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(140deg, var(--brand-gold), #f2cd8d);
            color: var(--brand-navy);
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            border: 2px solid rgba(8, 0, 89, 0.08);
        }
        .logo-text strong {
            color: var(--brand-navy);
            font-size: 13px;
            display: block;
            line-height: 1.2;
        }
        .logo-text span {
            color: var(--muted);
            font-size: 11px;
        }
        .title-wrap {
            flex: 1;
            text-align: center;
        }
        .title-main {
            margin: 0;
            color: var(--brand-navy);
            font-size: 26px;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            font-weight: 800;
        }
        .title-sub {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 12px;
            letter-spacing: 0.8px;
        }
        .meta {
            width: 250px;
            text-align: right;
            font-size: 12px;
            color: #343a61;
            line-height: 1.55;
        }
        .content {
            padding: 14px 18px 18px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 14px;
        }
        .box {
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 9px 10px;
            background: var(--bg-soft);
        }
        .label {
            font-size: 11px;
            color: var(--muted);
            margin-bottom: 3px;
        }
        .value {
            font-size: 15px;
            font-weight: 700;
            color: #11163a;
        }
        .payable-box {
            background: linear-gradient(120deg, rgba(8, 0, 89, 0.96), rgba(28, 16, 159, 0.92));
            border-color: rgba(8, 0, 89, 0.5);
        }
        .payable-box .label,
        .payable-box .value {
            color: #fff;
        }
        .section-title {
            margin: 14px 0 7px;
            font-size: 14px;
            color: var(--brand-navy);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .section-title::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--brand-gold);
            box-shadow: 0 0 0 3px rgba(234, 188, 115, 0.2);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid var(--line);
            padding: 7px;
            font-size: 11px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }
        thead th {
            background: #f0f3ff;
            color: #1f2653;
            font-weight: 700;
        }
        .table-summary td:first-child,
        .table-summary td:nth-child(3) {
            width: 29%;
            background: #fafbff;
        }
        .table-summary td:nth-child(2),
        .table-summary td:nth-child(4) {
            width: 21%;
            text-align: right;
            font-weight: 600;
            color: #27306a;
        }
        .payable-row td {
            background: #f5f8ff;
            font-size: 12px;
        }
        .payable-row td:last-child {
            color: var(--brand-navy);
        }
        .status {
            display: inline-block;
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: 700;
        }
        .present { background: #d6f3e7; color: #0d6c46; }
        .absent { background: #ffe2e8; color: #ad1d44; }
        .right { text-align: right; }
        .print-hint {
            margin-top: 11px;
            font-size: 11px;
            color: #5e648f;
            border-top: 1px dashed #c7cdea;
            padding-top: 8px;
        }
        @media (max-width: 920px) {
            .head {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            .logo-wrap,
            .meta {
                width: 100%;
            }
            .title-wrap,
            .meta {
                text-align: left;
            }
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .page {
                max-width: 100%;
                border: none;
                border-radius: 0;
                box-shadow: none;
            }
            .content {
                padding: 10px 0 0;
            }
            .print-hint { display: none; }
            .section-title,
            .box,
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="head-band"></div>
        <div class="head">
            <div class="logo-wrap">
                <div class="logo-badge">TS</div>
                <div class="logo-text">
                    <strong>Preowned Technical Services</strong>
                    <span>Engineering & Field Workforce</span>
                </div>
            </div>

            <div class="title-wrap">
                <h1 class="title-main">Salary Slip</h1>
                <p class="title-sub">Monthly Payment Statement</p>
            </div>

            <div class="meta">
                <div><strong>Month:</strong> {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</div>
                <div><strong>Generated:</strong> {{ now()->format('d/m/Y h:i A') }}</div>
                <div><strong>Allowed Off:</strong> {{ $allowedOff }}</div>
            </div>
        </div>

        <div class="content">
            <div class="grid">
                <div class="box">
                    <div class="label">Employee Name</div>
                    <div class="value">{{ $user->name }}</div>
                </div>
                <div class="box">
                    <div class="label">Email</div>
                    <div class="value" style="font-size: 13px;">{{ $user->email }}</div>
                </div>
                <div class="box">
                    <div class="label">Monthly Salary</div>
                    <div class="value">{{ number_format($summary['monthly_salary'], 2) }}</div>
                </div>
                <div class="box">
                    <div class="label">Final Salary</div>
                    <div class="value">{{ number_format($summary['final_salary'], 2) }}</div>
                </div>
                <div class="box">
                    <div class="label">Advance Deduction</div>
                    <div class="value">{{ number_format($summary['advance_deduction'] ?? 0, 2) }}</div>
                </div>
                <div class="box payable-box">
                    <div class="label">Payable Salary</div>
                    <div class="value">{{ number_format($summary['payable_salary'] ?? $summary['final_salary'], 2) }}</div>
                </div>
            </div>

            <h3 class="section-title">Salary Summary</h3>
            <table class="table-summary">
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
                        <td>
                            {{ number_format(max((max(($summary['net_advance'] ?? 0), 0) - ($summary['advance_deduction'] ?? 0)), 0), 2) }}
                        </td>
                        <td><strong>Final Salary</strong></td>
                        <td>{{ number_format($summary['final_salary'], 2) }}</td>
                    </tr>
                    <tr class="payable-row">
                        <td colspan="2"><strong>Payable Salary</strong></td>
                        <td colspan="2" class="right"><strong>{{ number_format($summary['payable_salary'] ?? $summary['final_salary'], 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <h3 class="section-title">Day-wise Work Details</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 110px;">Date</th>
                        <th style="width: 95px;">Status</th>
                        <th>Project(s)</th>
                        <th style="width: 95px;" class="right">Work Hrs</th>
                        <th style="width: 95px;" class="right">OT Hrs</th>
                        <th style="width: 120px;" class="right">Adj. OT Hrs</th>
                        <th>Description</th>
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
                            <td class="right">{{ number_format((float) $day['working_hours'], 2) }}</td>
                            <td class="right">{{ number_format((float) $day['overtime_hours'], 2) }}</td>
                            <td class="right">{{ number_format((float) $day['adjusted_overtime_hours'], 2) }}</td>
                            <td>{{ $day['description'] ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="print-hint">
                Preview mode: use browser print (Ctrl+P) and choose "Save as PDF" to test output design.
            </p>
        </div>
    </div>
</body>
</html>
