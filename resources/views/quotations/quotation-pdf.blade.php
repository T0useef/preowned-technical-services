<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation {{ $quotation->quotation_number }}</title>
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
        .brand { width: 50%; }
        .meta { width: 50%; text-align: right; }
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
        .brand-name {
            display: inline-block;
            vertical-align: middle;
            color: #080059;
            font-weight: 700;
            line-height: 1.25;
        }
        .brand-sub { display: block; color: #6b7299; font-size: 10px; }
        .meta-row { margin-bottom: 2px; }
        .meta-row strong { color: #080059; }

        .section-title {
            margin: 10px 0 6px;
            color: #080059;
            font-size: 15px;
            font-weight: 800;
        }

        .info td {
            border: 1px solid #d8ddf0;
            background: #f7f9ff;
            border-radius: 6px;
            padding: 8px 10px;
            width: 50%;
            vertical-align: top;
        }
        .info-label { color: #6b7299; font-size: 10px; margin-bottom: 2px; }
        .info-value { color: #10153a; font-size: 14px; font-weight: 800; }

        .items th, .items td {
            border: 1px solid #d8ddf0;
            padding: 7px;
            font-size: 11px;
            vertical-align: top;
        }
        .items th {
            background: #eff2ff;
            color: #1f2653;
            text-align: left;
            font-weight: 700;
        }
        .num { text-align: right; color: #27306a; font-weight: 600; }
        .grand-total td {
            background: #080059;
            color: #fff;
            font-weight: 800;
            font-size: 12px;
        }
        .grand-total .num { color: #fff; }

        .notes-box {
            margin: 8px 0;
            border: 1px solid #d8ddf0;
            background: #fbfcff;
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 11px;
            color: #535b88;
        }
        .footer td {
            border-top: 1px solid #d8ddf0;
            padding-top: 10px;
            vertical-align: bottom;
        }
        .footer-left {
            color: #535b88;
            font-size: 10px;
            line-height: 1.55;
            width: 70%;
        }
        .footer-left strong { color: #080059; font-size: 11px; }
        .signature {
            text-align: right;
            width: 30%;
            color: #4f5786;
            font-size: 10px;
        }
        .signature-line {
            border-top: 1px solid #9ea5cb;
            margin-top: 28px;
            padding-top: 6px;
        }
        .terms {
            margin-top: 8px;
            border-top: 1px dashed #c6cbe8;
            padding-top: 6px;
            color: #5e648f;
            font-size: 10px;
            line-height: 1.45;
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
                            <span class="brand-sub">Quotation Document</span>
                        </span>
                    </td>
                    <td class="meta">
                        <div class="meta-row"><strong>Quotation #:</strong> {{ $quotation->quotation_number }}</div>
                        <div class="meta-row"><strong>Date:</strong> {{ $quotation->quotation_date->format('d/m/Y') }}</div>
                        <div class="meta-row"><strong>Valid Till:</strong> {{ $validTill->format('d/m/Y') }}</div>
                        <div class="meta-row"><strong>Generated:</strong> {{ now()->format('d/m/Y h:i A') }}</div>
                    </td>
                </tr>
            </table>

            <div class="section-title">Quotation For</div>
            <table class="info">
                <tr>
                    <td>
                        <div class="info-label">Client / Company Name</div>
                        <div class="info-value">{{ $quotation->company_name }}</div>
                    </td>
                    <td>
                        <div class="info-label">Total Quoted Amount</div>
                        <div class="info-value">{{ number_format($quotation->total_amount, 2) }}</div>
                    </td>
                </tr>
            </table>

            @if($quotation->notes)
                <div class="notes-box">
                    <strong style="color:#080059;">Notes:</strong> {{ $quotation->notes }}
                </div>
            @endif

            <div class="section-title">Scope &amp; Pricing</div>
            <table class="items">
                <thead>
                    <tr>
                        <th style="width: 36px;">#</th>
                        <th>Description</th>
                        <th style="width: 70px;" class="num">Qty</th>
                        <th style="width: 100px;" class="num">Unit Price</th>
                        <th style="width: 100px;" class="num">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->description }}</td>
                            <td class="num">{{ number_format($item->qty, 2) }}</td>
                            <td class="num">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="num">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="grand-total">
                        <td colspan="4" class="num"><strong>Grand Total</strong></td>
                        <td class="num"><strong>{{ number_format($quotation->total_amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <table class="footer">
                <tr>
                    <td class="footer-left">
                        <strong>ALATechnical Services Manager</strong><br>
                        Email: Atiflatif426@gmail.com<br>
                        +971569581881<br>
                        Dubai, United Arab Emirates
                    </td>
                    <td class="signature">
                        <div class="signature-line">Authorized Signature</div>
                    </td>
                </tr>
            </table>

            <div class="terms">
                <strong>Terms &amp; Conditions:</strong> Payment is due within 15 days from invoice date.
                Any additional work outside the agreed quotation scope will be charged separately after written approval.
            </div>
        </div>
    </div>
</body>
</html>
