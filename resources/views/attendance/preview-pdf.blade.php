<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Record</title>
    <style>
        * { box-sizing: border-box; }
        @page {
            margin: 0;
        }
        html, body {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: "DejaVu Serif", "Times New Roman", Times, serif;
            color: #000;
            font-size: 10.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .frame,
        .frame td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .gap-top {
            height: 20px;
            border: none !important;
            padding: 0 !important;
        }
        .gap-bottom {
            height: 42px;
            border: none !important;
            padding: 0 !important;
        }
        .gap-side {
            width: 30px;
            border: none !important;
            padding: 0 !important;
            background: transparent;
        }
        body {
            font-family: "DejaVu Serif", "Times New Roman", Times, serif;
            color: #000;
            font-size: 10.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-box {
            border: 1.2px solid #000;
            width: 100%;
        }
        .logo-row {
            text-align: center;
            padding: 8px 8px 6px;
        }
        .logo-row img {
            max-height: 52px;
            max-width: 210px;
        }
        .logo-fallback {
            display: inline-block;
            font-size: 16px;
            font-weight: 800;
            color: #080059;
            letter-spacing: 0.5px;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        .title-row {
            border-top: 1.2px solid #000;
            text-align: center;
            padding: 6px 8px;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        .title-row h1 {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }
        .meta-table td {
            width: 33.33%;
            border-top: 1.2px solid #000;
            padding: 8px 8px 7px;
            font-size: 10.5px;
            font-weight: 700;
            vertical-align: bottom;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        .meta-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 62%;
            height: 12px;
            margin-left: 4px;
            vertical-align: bottom;
        }
        .rules-table > tbody > tr > td {
            width: 50%;
            border-top: 1.2px solid #000;
            vertical-align: top;
            padding: 6px 7px 4px;
        }
        .rules-table > tbody > tr > td.right {
            border-left: 1px solid #000;
        }
        .rule-item {
            width: 100%;
            margin: 0 0 4px;
        }
        .rule-item td {
            border: none;
            padding: 0;
            font-size: 10px;
            line-height: 1.32;
            font-weight: normal;
            font-style: normal;
            font-family: "DejaVu Serif", "Times New Roman", Times, serif;
        }
        .rule-num {
            width: 24px;
            vertical-align: top;
            white-space: nowrap;
            padding-right: 3px !important;
        }
        .rule-text {
            vertical-align: top;
            text-align: justify;
        }
        .attendance-table thead {
            display: table-header-group;
        }
        .attendance-table tfoot {
            display: table-footer-group;
        }
        .attendance-table tbody {
            display: table-row-group;
        }
        .attendance-table th,
        .attendance-table td {
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        .attendance-table th {
            font-size: 9.5px;
            font-weight: 700;
            padding: 5px 3px;
            background: #f3f3f3;
            line-height: 1.2;
        }
        .attendance-table td {
            height: 20px;
            font-size: 9.5px;
            padding: 2px 3px;
        }
        .pdf-page {
            page-break-after: always;
        }
        .pdf-page:last-child {
            page-break-after: auto;
        }
        .col-sr { width: 8%; }
        .col-name { width: 30%; }
        .col-time { width: 15%; }
        .col-sign { width: 17%; }
        .attendance-table td.col-name { text-align: left; padding-left: 6px; }
        .attendance-table .gap-side,
        .attendance-table .gap-top,
        .attendance-table .gap-bottom {
            border: none !important;
            background: none !important;
        }
    </style>
</head>
<body>
@php
    $leftRules = [
        1 => 'Always wear the required PPE (helmet, safety shoes, reflective vest, gloves, and safety glasses)',
        2 => 'Follow site safety rules and instructions from supervisors.',
        3 => 'Use only authorized access routes and walkways.',
        4 => 'Maintain good housekeeping; keep work areas clean and free from trip hazards.',
        5 => 'Inspect tools and equipment before use.',
        6 => 'Do not operate machinery without proper training and authorization.',
        7 => 'Report unsafe conditions, near misses, and accidents immediately.',
        8 => 'Follow safe lifting techniques and seek assistance for heavy loads.',
        9 => 'Ensure proper use of ladders and scaffolds before working at height.',
        10 => 'Use fall protection systems when working at elevated locations.',
    ];
    $rightRules = [
        11 => 'Follow lockout/tagout procedures when required.',
        12 => 'Keep clear of moving equipment and vehicle operation areas.',
        13 => 'Observe all warning signs, barricades, and exclusion zones.',
        14 => 'Do not engage in horseplay, fighting, or unsafe behavior on site.',
        15 => 'Ensure electrical tools and cables are in good condition.',
        16 => 'Stay hydrated and take precautions during hot weather.',
        17 => 'Emergency procedures, assembly points, and escape routes.',
        18 => 'The location of first aid kits and emergency contact numbers.',
        19 => 'Stop work immediately if an unsafe condition is identified.',
    ];
@endphp
@foreach ($pages as $page)
    <div class="pdf-page">
    @if ($page['show_header'])
    <table class="frame">
        <tr>
            <td class="gap-top" colspan="3"></td>
        </tr>
        <tr>
            <td class="gap-side"></td>
            <td>
        <div class="header-box">
        <div class="logo-row">
            @if(!empty($logo))
                <img src="{{ $logo }}" alt="Company Logo">
            @else
                <span class="logo-fallback">Preowned Technical Services</span>
            @endif
        </div>
        <div class="title-row">
            <h1>Attendance Record</h1>
        </div>
        <table class="meta-table">
            <tr>
                <td>Project: <span class="meta-line"></span></td>
                <td>Supervisor: <span class="meta-line"></span></td>
                <td>Date: <span class="meta-line"></span></td>
            </tr>
        </table>
        <table class="rules-table">
            <tr>
                <td>
                    @foreach ($leftRules as $number => $text)
                        <table class="rule-item">
                            <tr>
                                <td class="rule-num">({{ $number }})</td>
                                <td class="rule-text">{{ $text }}</td>
                            </tr>
                        </table>
                    @endforeach
                </td>
                <td class="right">
                    @foreach ($rightRules as $number => $text)
                        <table class="rule-item">
                            <tr>
                                <td class="rule-num">({{ $number }})</td>
                                <td class="rule-text">{{ $text }}</td>
                            </tr>
                        </table>
                    @endforeach
                </td>
            </tr>
            </table>
        </div>
            </td>
            <td class="gap-side"></td>
        </tr>
    </table>
    @endif
        <table class="attendance-table">
        <thead>
            <tr>
                <th class="gap-top gap-side"></th>
                <th class="gap-top" colspan="6"></th>
                <th class="gap-top gap-side"></th>
            </tr>
            <tr>
                <th class="gap-side"></th>
                <th class="col-sr">Sr.</th>
                <th class="col-name">Name</th>
                <th class="col-time">Check-In Time</th>
                <th class="col-time">Check-Out Time</th>
                <th class="col-time">Break Time</th>
                <th class="col-sign">Signature</th>
                <th class="gap-side"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($page['rows'] as $row)
                <tr>
                    <td class="gap-side"></td>
                    <td class="col-sr">{{ $row['sr'] }}</td>
                    <td class="col-name">{{ $row['name'] }}</td>
                    <td class="col-time"></td>
                    <td class="col-time"></td>
                    <td class="col-time"></td>
                    <td class="col-sign"></td>
                    <td class="gap-side"></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="gap-bottom gap-side"></td>
                <td class="gap-bottom" colspan="6"></td>
                <td class="gap-bottom gap-side"></td>
            </tr>
        </tfoot>
    </table>
    </div>
@endforeach
</body>
</html>
