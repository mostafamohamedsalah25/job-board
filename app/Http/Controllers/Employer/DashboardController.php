<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $employer = $request->user()->employer;

        $totalJobs = $employer->jobPostings()->count();

        $totalApplications = $employer->jobPostings()
            ->withCount('applications')
            ->get()
            ->sum('applications_count');

        $jobsForChart = $employer->jobPostings()
            ->withCount('applications')
            ->latest()
            ->take(5)
            ->get(['title', 'applications_count']);

        return Inertia::render('Employer/Dashboard', [
            'stats' => [
                'totalJobs' => $totalJobs,
                'totalApplications' => $totalApplications,
            ],
            'chartData' => $jobsForChart
        ]);
    }
}
