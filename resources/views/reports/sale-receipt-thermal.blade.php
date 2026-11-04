<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>58mm Invoice</title>
    <style>
        /* Thermal printer settings */
        body {
            width: 58mm;
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 8px 0;
            margin-left: auto;
            margin-right: auto;
        }

        .no-print {
            margin-bottom: 8px;
            text-align: right;
        }

        .back-btn {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #000;
            text-decoration: none;
            color: #000;
            font-size: 10px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }


        .invoice {
            padding: 2mm;
        }

        h2, h3 {
            text-align: center;
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 2px 0;
        }

        th, td {
            text-align: left;
            padding: 2px 0;
        }

        th {
            border-bottom: 1px dashed #000;
        }

        .totals td {
            border-top: 1px dashed #000;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .small {
            font-size: 8px;
        }
    </style>

</head>
<body>
<div class="no-print">
    <a href="{{ route('sales.create') }}" class="back-btn">Back To Sale</a>
</div>

<div class="invoice">
    <h2>{{ $appSetting->business_title ?? 'POS System' }}</h2>
    <p class="small">Date: {{ $sale->created_at->format('d/m/Y | H:i') }}<br>
        Invoice #: {{ $sale->invoice_no }}<br>
        Customer: {{ $sale->customer->name ?? "-" }}<br>
        @if($appSetting?->mobile_number)
            Mobile: {{ $appSetting->mobile_number }}<br>
        @endif
        @if($appSetting?->email)
            Email: {{ $appSetting->email }}
        @endif
    </p>
    <table>
        <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Dis%</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @php
            $discountTotal=0;
            $lineTotal=0;
        @endphp
        @if(isset($sale->items))
            @foreach($sale->items as $item)
                <tr>
                <td>{{$item->book->title ?? "-"}}</td>
                <td >{{ $item->quantityLabel() }}</td>
                <td >{{number_format($item->unit_price,2)}}</td>
                <td >{{$item->discount}}%</td>
                <td >{{number_format($item->total,2)}}</td>
                @php
                    $discount = $item->line_total - $item->total;
                    $discountTotal += $discount;
                    $lineTotal += $item->line_total;
                @endphp
                </tr>
            @endforeach
        @endif


        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="right">{{number_format($lineTotal,2)}}</td>
        </tr>
        <tr>
            <td>Discount</td>
            <td class="right">{{number_format($discountTotal,2)}}</td>
        </tr>
        <tr>
            <td>Tax</td>
            <td class="right">{{ $appSetting && $appSetting->tax_included ? 'Included' : 'Not Included' }}</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td class="right"><strong>{{number_format($sale->total_amount,2)}}</strong></td>
        </tr>
    </table>

    <p style="text-align:center; margin-top:10px;">
        {{ $appSetting->business_title ?? 'Thank you for your purchase' }}
    </p>
    <p style="text-align:center;">
        <strong>Address</strong>: {{ $appSetting->address ?? '-' }}
    </p>
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
