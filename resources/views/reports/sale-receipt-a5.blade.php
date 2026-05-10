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

<div class="receipt-wrapper">

    <img src="{{ asset('assets/images/logo/logo_receipt.png') }}" class="logo">

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
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price,2) }}</td>
                <td>{{ $item->discount }}%</td>
                <td>{{ number_format($item->total,2) }}</td>
            </tr>

            @php
                $discount = $item->line_total - $item->total;
                $discountTotal += $discount;
                $lineTotal += $item->line_total;
                $totalQuantity += $item->quantity;
            @endphp
        @endforeach

        <tr><td colspan="1"><strong>Total Quantity</strong></td><td> <strong> {{ $totalQuantity }}<strong></td></tr>
        <tr><td colspan="4"><strong>Total</strong></td><td>{{ number_format($lineTotal,2) }}</td></tr>
        <tr><td colspan="4"><strong>Discount</strong></td><td>{{ number_format($discountTotal,2) }}</td></tr>
        <tr><td colspan="4"><strong>Grand Total</strong></td><td>{{ number_format($sale->total_amount,2) }}</td></tr>
        </tbody>
    </table>

    <div class="footer">
        The Project of <strong>Raah Welfare Foundation Trust</strong><br>
        <strong>Address:</strong> Shama Plaza, Kharadar, Karachi
    </div>

</div>

<script>
    window.print();
</script>

</body>
</html>
