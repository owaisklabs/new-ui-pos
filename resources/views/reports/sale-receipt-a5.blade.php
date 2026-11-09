<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>A5 Height Invoice</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
        }

        .no-print {
            padding: 10px;
            text-align: right;
        }

        .back-btn {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 4px;
            border: 1px solid #000;
            text-decoration: none;
            color: #000;
            font-size: 13px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .receipt-wrapper {
            width: 100%;
            height: 148mm; /* Half A4 height */
            border: 1px dashed #000; /* optional – cut line */
            padding: 10px;
            box-sizing: border-box;
        }

        img.logo {
            max-height: 60px;
            display: block;
            margin: 0 auto 10px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        td, th {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        .no-border td {
            border: none !important;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="no-print">
    <a href="{{ route('sales.create') }}" class="back-btn">Back To Sale</a>
</div>

<div class="receipt-wrapper">

    @if($appSetting && $appSetting->logo)
        <img src="{{ $appSetting->logoUrl() }}" class="logo" alt="Logo">
    @endif

    <table class="no-border">
        <tr>
            <td><strong>Invoice:</strong> #{{ $sale->invoice_no }}</td>
            <td><strong>Date:</strong> {{ $sale->created_at->format('d/m/Y | H:i') }}</td>
            <td><strong>Customer:</strong> {{ $sale->customer->name ?? "-" }}</td>
        </tr>
    </table>

    <table>
        <thead>
        <tr>
            <th>Product</th><th>Qty</th><th>Price</th><th>Discount %</th><th>Total</th>
        </tr>
        </thead>

        <tbody>
        @php
            $discountTotal = 0;
            $lineTotal = 0;
            $totalQuantity = 0;
        @endphp
        @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->book->title ?? "-" }}</td>
                <td>{{ $item->quantityLabel() }}</td>
                <td>{{ $item->priceLabel() }}</td>
                <td>{{ $item->discount }}%</td>
                <td>{{ number_format($item->total,2) }}</td>
            </tr>

            @php
                $discount = $item->line_total - $item->total;
                $discountTotal += $discount;
                $lineTotal += $item->line_total;
                $totalQuantity += $item->isDualUnit() ? 0 : $item->quantity;
            @endphp
        @endforeach

        @if($totalQuantity > 0)
            <tr><td colspan="1"><strong>Total Quantity</strong></td><td> <strong> {{ $totalQuantity }}<strong></td></tr>
        @endif
        <tr><td colspan="4"><strong>Total</strong></td><td>{{ number_format($lineTotal,2) }}</td></tr>
        <tr><td colspan="4"><strong>Discount</strong></td><td>{{ number_format($discountTotal,2) }}</td></tr>
        <tr><td colspan="4"><strong>Tax</strong></td><td>{{ $appSetting && $appSetting->tax_included ? 'Included' : 'Not Included' }}</td></tr>
        <tr><td colspan="4"><strong>Grand Total</strong></td><td>{{ number_format($sale->total_amount,2) }}</td></tr>
        </tbody>
    </table>

    <div class="footer">
        <strong>{{ $appSetting->business_title ?? 'POS System' }}</strong><br>
        @if($appSetting?->mobile_number)
            <strong>Mobile:</strong> {{ $appSetting->mobile_number }}<br>
        @endif
        @if($appSetting?->email)
            <strong>Email:</strong> {{ $appSetting->email }}<br>
        @endif
        <strong>Address:</strong> {{ $appSetting->address ?? '-' }}
    </div>

</div>

<script>
    window.addEventListener('load', function () {
        setTimeout(function () {
            window.print();
        }, 150);
    });
</script>

</body>
</html>
