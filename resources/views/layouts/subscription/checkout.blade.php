{{-- <!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Subscribe</title>
  <script src="https://js.stripe.com/v3/"></script>
  <style>
    body { font-family: system-ui, Arial; display:flex; align-items:center; justify-content:center; height:100vh; }
    .card { padding:24px; border:1px solid #e5e7eb; border-radius:8px; text-align:center; width:360px; box-shadow:0 6px 18px rgba(0,0,0,0.04);}
    button { padding:10px 16px; border-radius:6px; border:none; cursor:pointer; background:#6366f1; color:white; font-weight:600; }
    button:disabled { opacity:.6; cursor:not-allowed; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Subscribe — Monthly Plan</h2>
    <p>Price: ₹499 / month (example)</p>

    <button id="checkout-button">Subscribe Now</button>

    <p style="margin-top:12px;font-size:13px;color:#6b7280;">
      Use test card: <code>4242 4242 4242 4242</code>
    </p>
  </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const stripe = Stripe("{{ $stripeKey }}");
  const btn = document.getElementById('checkout-button');

  btn.addEventListener('click', async function () {
    btn.disabled = true;

    try {
      const resp = await fetch("{{ route('checkout.create') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          "Accept": "application/json"
        },
        body: JSON.stringify({ price_id: "{{ $priceId }}" })
      });

      const data = await resp.json();

      if (data.error) {
        alert(data.error || 'Failed to create session');
        btn.disabled = false;
        return;
      }

      const sessionId = data.id;
      const { error } = await stripe.redirectToCheckout({ sessionId });

      if (error) {
        alert(error.message || 'Stripe redirect failed');
        btn.disabled = false;
      }
    } catch (err) {
      alert('Request failed: ' + err.message);
      btn.disabled = false;
    }
  });
});
</script>
</body>
</html> --}}

{{-- resources/views/layouts/subscription/checkout.blade.php --}}
<x-app-layout>
  <div class="max-w-2xl mx-auto py-10">
    <div class="bg-white p-6 rounded shadow text-center">
      <h2>Subscribe — Monthly Plan</h2>
      <p>Price: ₹499 / month</p>

      <form id="checkout-form">
        @csrf
        <input type="hidden" name="price_id" id="price_id" value="{{ $priceId }}">
        <button id="checkout-button" type="button" class="btn btn-primary">Proceed to Checkout</button>
      </form>

      <p class="text-xs mt-3">Test card: 4242 4242 4242 4242</p>
    </div>
  </div>

  <script src="https://js.stripe.com/v3/"></script>
  <script>
  document.getElementById('checkout-button').addEventListener('click', async function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const priceId = document.getElementById('price_id').value;

    const res = await fetch("{{ route('checkout.process') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": token,
        "Accept": "application/json"
      },
      body: JSON.stringify({ price_id: priceId })
    });

    const data = await res.json();
    if (data.error) { alert(data.error); return; }

    const stripe = Stripe("{{ $stripeKey }}");
    const { error } = await stripe.redirectToCheckout({ sessionId: data.id });
    if (error) alert(error.message);
  });
  </script>
</x-app-layout>

