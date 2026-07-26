<x-mail::message>
# Thanks for your order, {{ $order->user->name }}!

Your order **{{ $order->order_number }}** has been placed successfully.

<x-mail::table>
| Product | Qty | Subtotal |
| :------ | :-- | -------: |
@foreach ($order->items as $item)
| {{ $item->product->name ?? 'Product' }} | {{ $item->quantity }} | {{ number_format($item->subtotal, 2) }} |
@endforeach
</x-mail::table>

**Total: {{ number_format($order->total_amount, 2) }}**

Your invoice is attached to this email as a PDF.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
