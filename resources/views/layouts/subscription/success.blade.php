<x-app-layout>

<!doctype html>
<html><body style="font-family:system-ui,Arial;padding:40px;">
  <h1>Payment successful — thank you!</h1>
  <p>Your checkout session: <strong>{{ $sessionId ?? 'N/A' }}</strong></p>
  <p><a href="{{ url('/') }}">Back to home</a></p>
</body></html>
</x-app-layout>
