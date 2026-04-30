<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\models\Payment;
use Inertia\Inertia;
use Illuminate\Support\Str;

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

        if ($application->jobPosting->employer_id !== $request->user()->employer->id) {
            abort(403);
        }
        if ($application->status !== 'accepted' || $application->is_paid) {
            abort(400, 'Invalid application state for payment.');
        }

        $employer = $request->user()->employer;
        // Simulate payment processing (replace with real payment gateway integration)
        $payment = Payment::create([
            'employer_id' => $employer->id,
            'application_id' => $application->id,
            'amount' => 5.00,
            'status' => 'successful',
            'transaction_id' => 'txn_' . Str::random(14),
            'paid_at' => now(),
        ]);

        // Mark the application as paid
        $application->update(['is_paid' => true]);

        return redirect()->route('employer.jobs.show', $application->jobPosting_id)
                    ->with('success', 'Payment successful. Contact information is now unlocked.');
    }
}
