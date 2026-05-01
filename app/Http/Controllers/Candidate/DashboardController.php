<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $baseQuery = Application::where('candidate_id', $user->id);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'accepted' => (clone $baseQuery)->where('status', 'accepted')->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        $recentApplications = (clone $baseQuery)
            ->with(['jobPosting.employer'])
            ->latest()
            ->take(4)
            ->get();

        $profileCompletion = 50;
        if ($user->profile) {
            if ($user->profile->bio) $profileCompletion += 20;
            if ($user->profile->resume_path) $profileCompletion += 15;
            if ($user->profile->linkedin_id) $profileCompletion += 15;
        }

        return Inertia::render('Candidate/Dashboard', [
            'stats' => $stats,
            'recentApplications' => $recentApplications,
            'profileCompletion' => $profileCompletion
        ]);
    }
}
