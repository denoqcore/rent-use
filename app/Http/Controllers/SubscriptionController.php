<?php
// app/Http/Controllers/SubscriptionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPayment;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Webhook;

class SubscriptionController extends Controller
{
    private array $plans = [
        'pro' => [
            'label'    => 'PRO',
            'price'    => 199,
            'currency' => 'MDL',
            'duration' => 30,
            'features' => [
                'plan-feature-12-ads',
                'plan-feature-6-photos',
                'plan-feature-1-boost',
                'plan-feature-priority-search',
            ],
        ],
        'premium' => [
            'label'    => 'PREMIUM',
            'price'    => 349,
            'currency' => 'MDL',
            'duration' => 30,
            'features' => [
                'plan-feature-20-ads',
                'plan-feature-8-photos',
                'plan-feature-3-boosts',
                'plan-feature-highest-priority',
            ],
        ],
    ];

    public function index()
    {
        $user = auth()->user();
        return view('subscription.index', [
            'plans'       => $this->plans,
            'currentPlan' => $user?->plan,
            'expiresAt'   => $user?->plan_expires_at,
        ]);
    }

    public function checkout(Request $request, string $plan)
{
     $user = auth()->user();

    if ($user->isActivePlan() && $user->plan !== 'starter') {
        return redirect()->route('profile')
            ->withFragment('subscription')
            ->with('error', __('messages.subscription-already-active'));
    }

    if (!array_key_exists($plan, $this->plans)) {
        abort(404);
    }

    $planData = $this->plans[$plan];

    Stripe::setApiKey(config('services.stripe.secret'));

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency'     => $planData['currency'],
                'unit_amount'  => $planData['price'] * 100,
                'product_data' => [
                    'name' => 'Subscription ' . $planData['label'] . ' — 30 days',
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('subscription.success'),
        'cancel_url' => route('profile'),
        'metadata' => [
            'user_id' => auth()->id(),
            'plan' => $plan,
        ],
    ]);

    return redirect($session->url);
}

     public function success(Request $request)
    {
        return redirect()->route('profile')
            ->withFragment('subscription')
            ->with('success', __('messages.subscription-payment-success'));
    }

    public function mockPay(Request $request, SubscriptionPayment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('subscription.index');
        }

        $duration = match($payment->plan) {
            'pro'     => 30,
            'premium' => 30,
            default   => 30,
        };

        $expiresAt = now()->addDays($duration);

        $payment->update([
            'status'     => 'paid',
            'payment_id' => 'mock_' . strtoupper(\Str::random(12)),
            'paid_at'    => now(),
            'expires_at' => $expiresAt,
        ]);

        $user = auth()->user();
        $user->forceFill([
            'plan'            => $payment->plan,
            'plan_expires_at' => $expiresAt,
        ])->save();

        return redirect()->route('subscription.index')
            ->with('success', __('messages.subscription-activated'));
    }

public function webhook(Request $request)
{
    $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');

    try {
        $event = Webhook::constructEvent(
            $payload,
            $sigHeader,
            config('services.stripe.webhook_secret')
        );
    } catch (\Exception $e) {
        \Log::error('Stripe webhook error', ['error' => $e->getMessage()]);
        return response('bad signature', 400);
    }

    \Log::info('WEBHOOK EVENT', ['type' => $event->type]);

    if ($event->type === 'checkout.session.completed') {

        $session = $event->data->object;
        $metadata = $session->metadata;

        $user = \App\Models\User::find($metadata->user_id);

        if (!$user) {
            \Log::error('USER NOT FOUND', ['user_id' => $metadata->user_id]);
            return response('user not found', 400);
        }

        $expiresAt = now()->addDays(30);

        $payment = \App\Models\SubscriptionPayment::create([
            'user_id'    => $user->id,
            'plan'       => $metadata->plan,
            'amount'     => $session->amount_total / 100,
            'currency'   => strtoupper($session->currency),
            'status'     => 'paid',
            'payment_id' => $session->payment_intent,
            'paid_at'    => now(),
            'expires_at' => $expiresAt,
    ]);

        $user->forceFill([
            'plan' => $metadata->plan,
            'plan_expires_at' => $expiresAt,
        ])->save();

        \Log::info('SUBSCRIPTION ACTIVATED', [
            'user_id' => $user->id,
            'plan' => $metadata->plan,
        ]);
    }

    return response('ok');
}

public function cancel(Request $request)
    {
        $user = auth()->user();

        if ($user->plan === 'starter') {
            return back()->with('error', __('messages.subscription-not-active'));
        }

        $user->forceFill([
            'plan'            => 'starter',
            'plan_expires_at' => null,
        ])->save();

        return redirect()->route('profile')
            ->withFragment('subscription')
            ->with('success', __('messages.subscription-cancelled'));
    }
}
