<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Notifications\JobStatusNotification;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // جلب الوظائف مع بيانات الشركة والمستخدم المرتبط بها (لنتمكن من إرسال الإشعار له)
        $jobs = JobPosting::with(['employer.user'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Dashboard', [
            'jobs' => $jobs
        ]);
    }

    public function updateJobStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending'
        ]);

        $job = JobPosting::with('employer.user')->findOrFail($id);

        $job->update(['status' => $validated['status'], 'is_active' => $validated['status'] === 'approved']);

        if ($job->employer && $job->employer->user) {
            $job->employer->user->notify(new JobStatusNotification($job));
        }

        return back()->with('success', "Job status updated to {$validated['status']} and employer notified.");
    }
}
