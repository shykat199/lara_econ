<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #222; }
        .header { width: 100%; margin-bottom: 24px; }
        .header td { vertical-align: top; }
        .brand { font-size: 20px; font-weight: bold; }
        .muted { color: #777; }
        .text-right { text-align: right; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 16px; }
        table.items th, table.items td { border-bottom: 1px solid #ddd; padding: 8px; text-align: left; }
        table.items th { background-color: #f5f5f5; }
        .totals { width: 100%; margin-top: 12px; }
        .totals td { padding: 6px 8px; }
        .totals .label { text-align: right; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 11px; color: #777; text-align: center; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; background: #eef; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td>
                <div class="brand">{{ config('app.name') }}</div>
                <div class="muted">Invoice</div>
            </td>
            <td class="text-right">
                <div><strong>Invoice #:</strong> {{ $order->order_number }}</div>
                <div><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</div>
                <div><strong>Status:</strong> {{ ucfirst($order->status) }}</div>
            </td>
        </tr>
    </table>

    <table class="header">
        <tr>
            <td>
                <div class="muted">Billed to</div>
                <div><strong>{{ $order->user->name }}</strong></div>
                <div>{{ $order->user->email }}</div>
                @if ($order->user->phone)
                    <div>{{ $order->user->phone }}</div>
                @endif
                @if ($order->user->address)
                    <div>{{ $order->user->address }}</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Product' }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="label">Total</td>
            <td class="text-right" style="width: 120px;">{{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>

    @if ($order->notes)
        <div style="margin-top: 20px;">
            <div class="muted">Notes</div>
            <div>{{ $order->notes }}</div>
        </div>
    @endif

    <div class="footer">
        Thank you for your purchase from {{ config('app.name') }}.
    </div>

</body>
</html>
