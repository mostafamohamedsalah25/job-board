<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\Application;
use App\Models\Category;
use Inertia\Inertia;


class JobController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'location', 'type', 'category_id']);

        $jobs = JobPosting::with(['employer', 'category'])
            ->where('status', 'approved')
            ->where('is_active', true)
            ->filter($filters)
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $categories = Category::select('id', 'name')->get();

        return Inertia::render('Candidate/Jobs/Index', [
            'jobs' => $jobs,
            'filters' => $filters,
            'categories' => $categories
        ]);
    }

    public function show(Request $request, string $id)
    {
        $job = JobPosting::with(['employer', 'category', 'comments.user'])
            ->where('status', 'approved')
            ->findOrFail($id);

        $hasApplied = Application::where('job_posting_id', $job->id)
            ->where('candidate_id', $request->user()->id)
            ->exists();

        return Inertia::render('Candidate/Jobs/Show', [
            'job' => $job,
            'hasApplied' => $hasApplied
        ]);
    }

    public function apply(Request $request, string $id)
    {
        $user = $request->user();

        if (!$user->profile || !$user->profile->resume_path) {
            return back()->with('error', 'You must complete your profile and upload a resume before applying.');
        }

        $job = JobPosting::where('status', 'approved')->findOrFail($id);

        Application::firstOrCreate([
            'job_posting_id' => $job->id,
            'candidate_id' => $user->id,
        ]);

        return back()->with('success', 'Application submitted successfully! The employer will review your profile.');
    }
}
