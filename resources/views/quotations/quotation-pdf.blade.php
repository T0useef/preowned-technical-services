<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        @page {
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1f2340;
            font-size: 12px;
            background: transparent;
        }

        .page {
            position: relative;
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            background: transparent;
            overflow: hidden;
        }

        .page.page-break {
            page-break-after: always;
        }

        .letterhead-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: 0;
        }

        .footer-page-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
            background: transparent;
            padding: 256px 82px 90px;
        }

        .footer-page {
            position: relative;
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            page-break-before: always;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            background: transparent;
        }

        .doc-title {
            text-align: center;
            color: #080059;
            font-weight: 800;
            font-size: 20px;
            margin: 0 0 14px;
            letter-spacing: 0.5px;
            background: transparent;
        }

        .header-cover {
            width: 100%;
            margin: 0 0 14px;
            background: transparent;
        }

        .header-cover td {
            vertical-align: middle;
            background: transparent;
            padding: 0;
        }

        .header-cover .title-cell {
            width: 100%;
            text-align: center;
            padding-bottom: 8px;
        }

        .header-cover .meta-left {
            width: 50%;
            text-align: left;
        }

        .header-cover .meta-right {
            width: 50%;
            text-align: right;
        }

        .doc-title-center {
            color: #080059;
            font-weight: 800;
            font-size: 24px;
            letter-spacing: 0.5px;
            margin: 0;
            line-height: 1.2;
            background: transparent;
            text-align: center;
        }

        .header-meta-line {
            color: #2a3158;
            font-size: 12px;
            line-height: 1.45;
            margin: 0;
            background: transparent;
        }

        .header-meta-line strong {
            color: #080059;
        }

        .subject-line {
            margin: 0 0 12px;
            color: #2a3158;
            font-size: 12px;
            line-height: 1.45;
            background: transparent;
        }

        .subject-line strong {
            color: #080059;
        }

        .party-boxes {
            width: 100%;
            margin: 10px 0 14px;
            border-collapse: collapse;
            table-layout: fixed;
            background: transparent;
        }

        .party-boxes col.party-col-box {
            width: 35%;
        }

        .party-boxes col.party-col-gap {
            width: 30%;
        }

        .party-boxes td {
            vertical-align: top;
            background: transparent;
            padding: 0;
        }

        .party-boxes .party-box-left,
        .party-boxes .party-box-right {
            width: 35%;
        }

        .party-boxes .party-box-gap {
            width: 30%;
        }

        .party-card {
            position: relative;
            border: 2.5px solid #080059;
            border-radius: 14px;
            background: transparent;
            padding: 18px 10px 12px;
            min-height: 78px;
        }

        .party-badge-wrap {
            text-align: center;
            margin-top: -28px;
            margin-bottom: 8px;
            background: transparent;
        }

        .party-badge {
            display: inline-block;
            background: #080059;
            color: #ffffff;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            padding: 5px 12px 6px;
            border-radius: 8px;
            line-height: 1.2;
        }

        .party-text-cell {
            text-align: left;
            padding: 0 4px;
        }

        .party-box-line {
            color: #111111;
            font-size: 10px;
            line-height: 1.4;
            margin: 0 0 2px;
            background: transparent;
        }

        .party-box-line.company {
            font-weight: 700;
            font-size: 10.5px;
            margin-bottom: 3px;
        }

        .intro-box {
            width: 100%;
            margin: 0 0 12px;
            border-collapse: collapse;
            table-layout: fixed;
            background: #e9e9e9;
            border: 1px solid #d4d4d4;
        }

        .intro-box td {
            vertical-align: middle;
            background: #e9e9e9;
            padding: 10px 8px;
        }

        .intro-box .intro-icon-cell {
            width: 10%;
            text-align: center;
            padding: 8px 6px;
            vertical-align: middle;
        }

        .intro-box .intro-text-cell {
            width: 90%;
            text-align: left;
            color: #333333;
            font-size: 10.5px;
            line-height: 1.55;
            padding: 10px 10px 10px 6px;
            vertical-align: middle;
        }

        .intro-icon {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
        }

        .meta-table td {
            padding: 5px 0;
            vertical-align: top;
            background: transparent;
            font-size: 12px;
            color: #2a3158;
        }

        .meta-table .left { width: 55%; text-align: left; }
        .meta-table .right { width: 45%; text-align: right; }
        .meta-table strong { color: #080059; }

        .items th,
        .items td {
            border: 1px solid rgba(8, 0, 89, 0.22);
            padding: 6px 7px;
            font-size: 11px;
            vertical-align: middle;
            background: transparent;
            text-align: left;
        }

        .items th {
            color: #080059;
            font-weight: 700;
            background: transparent;
        }

        .items thead {
            display: table-header-group;
        }

        .items tfoot {
            display: table-row-group;
        }

        .items tr {
            page-break-inside: avoid;
        }

        .items th.col-center,
        .items td.col-center,
        .items td.num,
        .items .num {
            text-align: center !important;
            color: #27306a;
            font-weight: 600;
        }

        .items th.col-center {
            color: #080059;
            font-weight: 700;
        }

        .grand-total td {
            background: transparent;
            color: #080059;
            font-weight: 800;
            font-size: 12px;
            border-top: 2px solid #080059;
            text-align: center;
            vertical-align: middle;
        }

        .grand-total .num {
            color: #080059;
            text-align: center;
        }

        .items tr.is-sub-heading td {
            font-weight: 700;
            background: rgba(8, 0, 89, 0.04);
        }

        .items tr.is-sub-item td.description-cell {
            padding-left: 14px;
        }
    </style>
</head>
<body>
    @php
        $items = $quotation->items->values()->all();

        $pageHeightPx = 1123;
        $topPadding = 256;
        $bottomPadding = 90;
        $contentHeight = $pageHeightPx - $topPadding - $bottomPadding;

        $theadHeight = 30;
        $firstPageOverhead = 320;
        $closingHeight = 40;
        $firstPageCapacity = $contentHeight - $firstPageOverhead;
        $nextPageCapacity = $contentHeight;

        $estimateRowHeight = function ($item): int {
            $description = trim((string) ($item->description ?? ''));
            $descriptionLength = function_exists('mb_strlen') ? mb_strlen($description) : strlen($description);

            // Approximate wrapped lines for the 52% description column.
            $charsPerLine = 42;
            $descriptionLines = max(1, (int) ceil($descriptionLength / $charsPerLine));

            // Base row + extra height for each wrapped line.
            return max(34, 22 + (($descriptionLines - 1) * 14));
        };

        $chunks = [[
            'items' => [],
            'show_closing' => false,
        ]];

        $currentPageIndex = 0;
        $currentUsedHeight = $theadHeight;

        foreach ($items as $item) {
            $rowHeight = $estimateRowHeight($item);
            $currentCapacity = $currentPageIndex === 0 ? $firstPageCapacity : $nextPageCapacity;

            if (!empty($chunks[$currentPageIndex]['items']) && ($currentUsedHeight + $rowHeight) > $currentCapacity) {
                $chunks[] = [
                    'items' => [],
                    'show_closing' => false,
                ];
                $currentPageIndex++;
                $currentUsedHeight = $theadHeight;
                $currentCapacity = $nextPageCapacity;
            }

            $chunks[$currentPageIndex]['items'][] = $item;
            $currentUsedHeight += $rowHeight;
        }

        $lastPageCapacity = $currentPageIndex === 0 ? $firstPageCapacity : $nextPageCapacity;
        $showGrandTotal = (bool) ($quotation->show_grand_total ?? true);
        if ($showGrandTotal) {
            if (($currentUsedHeight + $closingHeight) > $lastPageCapacity) {
                $chunks[] = [
                    'items' => [],
                    'show_closing' => true,
                ];
            } else {
                $chunks[$currentPageIndex]['show_closing'] = true;
            }
        }

        $rowOffset = 0;
        $totalPages = count($chunks);
    @endphp

    @foreach($chunks as $pageIndex => $page)
        @php
            $isFirst = $pageIndex === 0;
            $isLast = $pageIndex === $totalPages - 1;
            $pageItems = $page['items'];
            $showClosing = $page['show_closing'];
        @endphp

        <div class="page{{ $isLast ? '' : ' page-break' }}">
            <img class="letterhead-bg" src="{{ $letterhead }}" alt="">
            <div class="content">
                @if($isFirst)
                    <table class="header-cover">
                        <tr>
                            <td class="title-cell" colspan="2">
                                <div class="doc-title-center">Quotation</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="meta-left">
                                <div class="header-meta-line"><strong>Quotation Number:</strong> {{ $quotation->quotation_number }}</div>
                            </td>
                            <td class="meta-right">
                                <div class="header-meta-line"><strong>Date:</strong> {{ $quotation->quotation_date->format('d/m/Y') }}</div>
                            </td>
                        </tr>
                    </table>

                    @if($quotation->subject)
                        <div class="subject-line"><strong>Subject:</strong> {{ $quotation->subject }}</div>
                    @endif

                    <table class="party-boxes">
                        <colgroup>
                            <col class="party-col-box" style="width:35%;">
                            <col class="party-col-gap" style="width:30%;">
                            <col class="party-col-box" style="width:35%;">
                        </colgroup>
                        <tr>
                            <td class="party-box-left" width="35%">
                                <div class="party-card">
                                    <div class="party-badge-wrap">
                                        <span class="party-badge">Quotation From</span>
                                    </div>
                                    <div class="party-text-cell">
                                        <div class="party-box-line company">Preowned Technical Services</div>
                                        <div class="party-box-line">Key person : Zahid Maqsood</div>
                                        <div class="party-box-line">Tel : +971 568144848</div>
                                    </div>
                                </div>
                            </td>
                            <td class="party-box-gap" width="30%"></td>
                            <td class="party-box-right" width="35%">
                                <div class="party-card">
                                    <div class="party-badge-wrap">
                                        <span class="party-badge">Quotation To</span>
                                    </div>
                                    <div class="party-text-cell">
                                        <div class="party-box-line company">{{ $quotation->company_name }}</div>
                                        @if(!empty($quotation->contact_person))
                                            <div class="party-box-line">{{ $quotation->contact_person }}</div>
                                        @endif
                                        @if(!empty($quotation->contact_phone))
                                            <div class="party-box-line">Tel : {{ $quotation->contact_phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="intro-box">
                        <tr>
                            <td class="intro-icon-cell" width="10%">
                                @if(!empty($noteIcon))
                                    <img class="intro-icon" src="{{ $noteIcon }}" width="70" height="70" alt="">
                                @endif
                            </td>
                            <td class="intro-text-cell" width="90%">
                                Following your Request for Quotation (RFQ) and our site visit, Preowned Technical Services is pleased to submit our proposal for the supply and application of epoxy floor coating works. The pricing below reflects our scope of work and commercial offer.
                            </td>
                        </tr>
                    </table>
                @endif

                @if(count($pageItems) || $showClosing)
                    <table class="items">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 52%;">Description</th>
                                <th style="width: 10%; text-align:center;" class="col-center">Unit</th>
                                <th style="width: 10%; text-align:center;" class="col-center">Qty</th>
                                <th style="width: 10%; text-align:center;" class="col-center">Unit Price</th>
                                <th style="width: 13%; text-align:center;" class="col-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pageItems as $index => $item)
                                @php
                                    $itemType = $item->item_type ?? 'main_item';
                                    $isHeading = $itemType === 'sub_heading';
                                    $rowClass = $isHeading ? 'is-sub-heading' : ($itemType === 'sub_item' ? 'is-sub-item' : '');
                                    $displayNumber = $item->display_number ?? ($rowOffset + $index + 1);
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td>{{ $displayNumber }}</td>
                                    @if($isHeading)
                                        <td class="description-cell" colspan="5">{{ $item->description }}</td>
                                    @else
                                        <td class="description-cell">{{ $item->description }}</td>
                                        <td class="col-center" style="text-align:center;">{{ $item->unit ?? '' }}</td>
                                        <td class="num col-center" style="text-align:center;">
                                            @if(is_numeric($item->qty))
                                                {{ number_format((float) $item->qty, 2) }}
                                            @else
                                                {{ $item->qty }}
                                            @endif
                                        </td>
                                        <td class="num col-center" style="text-align:center;">
                                            @if(is_numeric($item->unit_price))
                                                {{ number_format((float) $item->unit_price, 2) }}
                                            @else
                                                {{ $item->unit_price }}
                                            @endif
                                        </td>
                                        <td class="num col-center" style="text-align:center;">
                                            @if(is_numeric($item->total))
                                                {{ number_format((float) $item->total, 2) }}
                                            @else
                                                {{ $item->total }}
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @if($showClosing)
                                <tr class="grand-total">
                                    <td colspan="3" style="text-align:center;"><strong>Grand Total</strong></td>
                                    <td colspan="3" class="num col-center" style="text-align:center;"><strong>{{ number_format($quotation->total_amount, 2) }}</strong></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        @php $rowOffset += count($pageItems); @endphp
    @endforeach

    @foreach(($footerPages ?? []) as $footerPage)
        <div class="footer-page">
            <img class="footer-page-bg" src="{{ $footerPage }}" alt="">
        </div>
    @endforeach
</body>
</html>
