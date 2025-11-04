<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function showCheckout()
    {
        // Put the actual Price ID you copied from Stripe here:
        $priceId = 'price_1SNIjsKkDjEbQ7rrA4AAVgDw'; // <<< REPLACE with real Price ID

        return view('layouts.subscription.checkout', [
            'stripeKey' => config('services.stripe.key') ?? env('STRIPE_KEY'),
            'priceId'   => $priceId,
        ]);
    }
public function checkout(Request $request)
{
    $user = $request->user();
    if (! $user) {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    // Accept price_id from POST, otherwise fallback to configured price
    $priceId = $request->input('price_id') ?? env('STRIPE_PRICE_ID', null);

    if (! $priceId) {
        return response()->json(['error' => 'Price ID required.'], 422);
    }

    // ensure STRIPE_SECRET exists
    $secret = env('STRIPE_SECRET');
    if (! $secret) {
        return response()->json(['error' => 'STRIPE_SECRET not set in .env'], 500);
    }

    // \Stripe\Stripe::setApiKey($secret);

    // try {
    //     $session = \Stripe\Checkout\Session::create([
    //         'payment_method_types' => ['card'],
    // 'mode' => 'payment', // <- for one-time payments
    // 'line_items' => [[ 'price' => $priceId, 'quantity' => 1 ]],
    //         'customer_email' => $user->email,
    //         'client_reference_id' => $user->id,
    //         'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
    //         'cancel_url'  => route('checkout.cancel'),
    //     ]);

    //     return response()->json(['id' => $session->id]);
    // } catch (\Throwable $e) {
    //     // Log full exception
    //     \Log::error('Stripe checkout error: '.$e->getMessage(), [
    //         'exception' => $e,
    //         'user_id' => $user->id ?? null,
    //         'price' => $priceId,
    //     ]);
    // inside your SubscriptionController::checkout(Request $request)
\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

$priceId = $request->input('price_id') ?? env('STRIPE_PRICE_ID');

try {
    // retrieve price object to inspect it
    $price = \Stripe\Price::retrieve($priceId);

    $isRecurring = isset($price->recurring) && $price->recurring !== null;

    $mode = $isRecurring ? 'subscription' : 'payment';

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'mode' => $mode,
        'line_items' => [[ 'price' => $priceId, 'quantity' => 1 ]],
        'customer_email' => $user->email,
        'client_reference_id' => $user->id,
            'success_url' => route('layouts.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('layouts.subscription.cancel'),
    ]);

    return response()->json(['id' => $session->id]);
} catch (\Throwable $e) {
    \Log::error('Stripe checkout error: '.$e->getMessage(), ['price' => $priceId]);
    return response()->json(['error' => 'Stripe error: '.$e->getMessage()], 500);
}


        // Return full error message for local debugging only
    //     return response()->json([
    //         'error' => 'Stripe error: ' . $e->getMessage(),
    //         'type'  => get_class($e),
    //     ], 500);
    // }
}

    // public function checkout(Request $request)
    // {
    //     $user = $request->user();
    //     if (! $user) return response()->json(['error' => 'Unauthenticated.'], 401);

    //     // Accept price_id from POST, otherwise fallback to a known price (from view)
    //     $priceId = $request->input('price_id') ?? 'price_1N2aBcEXAMPLE'; // <<< same replacement

    //     if (! $priceId || ! str_starts_with($priceId, 'price_')) {
    //         return response()->json(['error' => 'Price ID required.'], 422);
    //     }

    //     \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    //     try {
    //         $session = \Stripe\Checkout\Session::create([
    //             'payment_method_types' => ['card'],
    //             'mode' => 'subscription',
    //             'line_items' => [[ 'price' => $priceId, 'quantity' => 1 ]],
    //             'customer_email' => $user->email,
    //             'client_reference_id' => $user->id,
    //             'metadata' => [ 'user_id' => $user->id ],
    //             'success_url' => route('layouts.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
    //             'cancel_url'  => route('layouts.subscription.cancel'),
    //         ]);

    //         return response()->json(['id' => $session->id]);
    //     } catch (\Exception $e) {
    //         Log::error('Stripe checkout session creation failed: ' . $e->getMessage());
    //         return response()->json(['error' => 'Unable to create checkout session.'], 500);
    //     }
    // }

    public function success(Request $request)
    {
        return view('layouts.subscription.success', ['sessionId' => $request->query('session_id')]);
    }

    public function cancel()
    {
        return view('layouts.subscription.cancel');
    }
}
