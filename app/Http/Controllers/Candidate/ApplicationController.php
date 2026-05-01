<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = Application::with('jobPosting.employer')
            ->where('candidate_id', $request->user()->id)
            ->latest()
            ->get();
        return Inertia::render('Candidate/Applications/Index', [
            'applications' => $applications,
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $application = Application::findOrFail($id);

        if ($application->candidate_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($application->status !== 'pending') {
            return back()->with('error', 'You cannot withdraw an application that has already been reviewed.');
        }

        $application->delete();

        return back()->with('success', 'Application withdrawn successfully.');
    }
}
