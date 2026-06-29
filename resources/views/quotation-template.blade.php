<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Template</title>
    <style>
        :root {
            --brand-navy: #080059;
            --brand-gold: #eabc73;
            --ink: #1f2340;
            --muted: #6b7299;
            --line: #d9deef;
            --bg-soft: #f7f9ff;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #fff;
            color: var(--ink);
            padding: 24px;
        }
        .quotation-page {
            max-width: 980px;
            margin: 0 auto;
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 14px 30px rgba(8, 0, 89, 0.1);
        }
        .top-strip {
            height: 6px;
            background: linear-gradient(110deg, var(--brand-navy), #1a0f8b, var(--brand-gold));
        }
        .header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--line);
            background: linear-gradient(150deg, #ffffff, #f8f9ff);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }
        .brand {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(130deg, var(--brand-gold), #f3d7a8);
            color: var(--brand-navy);
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(8, 0, 89, 0.1);
        }
        .brand h1 {
            margin: 0;
            color: var(--brand-navy);
            font-size: 18px;
        }
        .brand p {
            margin: 3px 0 0;
            color: var(--muted);
            font-size: 12px;
        }
        .meta {
            text-align: right;
            font-size: 12px;
            color: #2e3562;
            line-height: 1.6;
        }
        .section {
            padding: 16px 20px;
        }
        .section-title {
            margin: 0 0 10px;
            color: var(--brand-navy);
            font-size: 15px;
            font-weight: 800;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .card {
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--bg-soft);
            padding: 10px;
        }
        .label {
            color: var(--muted);
            font-size: 11px;
            margin-bottom: 4px;
        }
        .value {
            color: #12183c;
            font-size: 14px;
            font-weight: 700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid var(--line);
            padding: 8px;
            font-size: 12px;
            vertical-align: top;
        }
        th {
            background: #eef2ff;
            color: #1d2554;
            text-align: left;
            font-weight: 700;
        }
        .num { text-align: right; }
        .total-row td {
            background: #f9fafe;
            font-weight: 700;
        }
        .grand-total td {
            background: var(--brand-navy);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
        }
        .footer {
            border-top: 1px solid var(--line);
            padding: 14px 20px 10px;
            background: #fbfcff;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 14px;
        }
        .footer-left {
            color: #535b88;
            font-size: 11px;
            line-height: 1.55;
            max-width: 72%;
        }
        .footer-left strong {
            color: #080059;
            font-size: 12px;
        }
        .signature {
            text-align: right;
            min-width: 160px;
        }
        .signature .line {
            border-top: 1px solid #9ea5cb;
            margin-top: 28px;
            padding-top: 6px;
            color: #4f5786;
            font-size: 11px;
        }
        .terms-line {
            border-top: 1px solid var(--line);
            padding: 8px 20px 12px;
            background: #fbfcff;
            color: #535b88;
            font-size: 11px;
            line-height: 1.45;
        }
        @media (max-width: 780px) {
            .header, .footer {
                flex-direction: column;
                align-items: flex-start;
            }
            .meta, .signature { text-align: left; }
            .grid { grid-template-columns: 1fr; }
            .footer-left { max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="quotation-page">
        <div class="top-strip"></div>

        <div class="header">
            <div class="brand">
                <div class="brand-mark">TS</div>
                <div>
                    <h1>Preowned Technical Services</h1>
                    <p>Quotation Document</p>
                </div>
            </div>
            <div class="meta">
                <div><strong>Quotation #:</strong> QTN-2026-0041</div>
                <div><strong>Date:</strong> {{ now()->format('d/m/Y') }}</div>
                <div><strong>Valid Till:</strong> {{ now()->addDays(15)->format('d/m/Y') }}</div>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">Quotation For</h3>
            <div class="grid">
                <div class="card">
                    <div class="label">Client Name</div>
                    <div class="value">ABC Industrial Solutions</div>
                </div>
                <div class="card">
                    <div class="label">Project Reference</div>
                    <div class="value">Electrical Maintenance & Labour Support</div>
                </div>
            </div>
        </div>

        <div class="section" style="padding-top: 0;">
            <h3 class="section-title">Scope & Pricing</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 42px;">#</th>
                        <th>Description</th>
                        <th style="width: 90px;" class="num">Qty</th>
                        <th style="width: 130px;" class="num">Unit Price</th>
                        <th style="width: 130px;" class="num">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Skilled Labour Support (Monthly)</td>
                        <td class="num">10</td>
                        <td class="num">1,500.00</td>
                        <td class="num">15,000.00</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Site Supervision & Reporting</td>
                        <td class="num">1</td>
                        <td class="num">2,000.00</td>
                        <td class="num">2,000.00</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Overtime Support (Estimated)</td>
                        <td class="num">40</td>
                        <td class="num">35.00</td>
                        <td class="num">1,400.00</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4" class="num">Subtotal</td>
                        <td class="num">18,400.00</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4" class="num">VAT (5%)</td>
                        <td class="num">920.00</td>
                    </tr>
                    <tr class="grand-total">
                        <td colspan="4" class="num">Grand Total</td>
                        <td class="num">19,320.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div class="footer-left">
                <strong>ALATechnical Services Manger</strong><br>
                Email: Atiflatif426@gmail.com<br>
                +971569581881<br>
                Dubai, United Arab Emirates
            </div>
            <div class="signature">
                <div class="line">Authorized Signature</div>
            </div>
        </div>
        <div class="terms-line">
            <strong>Terms & Conditions:</strong> Payment is due within 15 days from invoice date. Any additional work outside the agreed quotation scope will be charged separately after written approval.
        </div>
    </div>
</body>
</html>
