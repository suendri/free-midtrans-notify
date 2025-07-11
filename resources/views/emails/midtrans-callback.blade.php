<h2>Delitekno Notifikasi Callback Midtrans</h2>
<p><strong>Order ID:</strong> {{ $orderId }}</p>
<p><strong>Callback Destination:</strong> {{ $targetUrl ?? '— Tidak ditemukan —' }}</p>

<h4>Payload:</h4>
<pre>{{ json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
