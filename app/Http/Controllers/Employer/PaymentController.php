<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\models\Payment;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function checkout(Request $request, string $applicationId)
    {
        $application = Application::with('candidate')->findOrFail($applicationId);

        if ($application->jobPosting->employer_id !== $request->user()->employer->id) {
            abort(403);
        }
        if ($application->status !== 'accepted' || $application->is_paid) {
            abort(400, 'Invalid application state for payment.');
        }

        return Inertia::render('Employer/Payments/Checkout', [
            'application' => $application,
            'fee' => 5.00
        ]);
    }

    public function processPayment(Request $request, string $applicationId)
    {
        $application = Application::with('candidate')->findOrFail($applicationId);

        Stripe::setApiKey(config('services.stripe.secret'));

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => "Contact Info for {$application->candidate->name}",
                        'description' => 'Payment to view candidate contact details.',
                    ],
                    'unit_amount' => 500, // $5.00 in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('employer.payments.success', $application->id).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('employer.payments.checkout', $application->id),
        ]);

        return Inertia::location($checkoutSession->url);
    }

    public function success(Request $request, string $applicationId)
    {
        $application = Application::findOrFail($applicationId);

        $employer = $request->user()->employer;

        $sessionId = $request->get('session_id');

        $payment = Payment::create([
            'employer_id' => $employer->id,
            'application_id' => $application->id,
            'amount' => 5.00,
            'transaction_id' => $sessionId,
            'status' => 'successful',
            'paid_at' => now(),
        ]);

        $application->update(['is_paid' => true]);

        return redirect()->route('employer.jobs.show', $application->job_posting_id)
                         ->with('success', 'Payment successful! Contact information is now unlocked.');

    }
}
