<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\Category;
use Inertia\Inertia;

class JobPostingController extends Controller
{
    public function index(Request $request) {
        // List all job postings for the employer
        $employer = $request->user()->employer;
        $jobs = $employer->jobPostings()
        ->with('category:id,name')
        ->withCount('applications')
        ->latest()->get();

        return Inertia::render('Employer/JobPostings/Index', [
            'jobs' => $jobs,
        ]);
    }


    public function create() {
        // Show form to create a new job posting
        $categories = Category::all();

        return Inertia::render('Employer/JobPostings/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request) {
        // Validate and store the new job posting
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
            'type' => 'required|in:remote,on-site,hybrid',
            'category_id' => 'required|exists:categories,id',
        ]);

        // $data['employer_id'] = $request->user()->employer->id;

        // JobPosting::create($data);
        $request->user()->employer->jobPostings()->create($data);

        return redirect()->route('employer.jobs.index')->with('success', 'Job posted successfully and is pending approval.');
    }

    public function show(Request $request, string $id) {
        $job = JobPosting::with('category:id,name')
            ->withCount('applications')
            ->findOrFail($id);

        if ($job->employer_id !== $request->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }

        return Inertia::render('Employer/JobPostings/Show', [
            'job' => $job,
        ]);
    }


    public function edit(Request $request, string $id) {
        // Show form to edit an existing job posting
        $job = JobPosting::findOrFail($id);

        if ($job->employer_id !== $request->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }
        $categories = Category::all();

        return Inertia::render('Employer/JobPostings/Edit', [
            'job' => $job,
            'categories' => $categories,
        ]);
    }


    public function update(Request $request, string $id) {
        // Validate and update the existing job posting
        $job = JobPosting::findOrFail($id);

        if ($job->employer_id !== $request->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
            'type' => 'required|in:remote,on-site,hybrid',
            'category_id' => 'required|exists:categories,id',
        ]);

        $job->update($data);

        return redirect()->route('employer.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Request $request, string $id) {
        // Delete a job posting
        $job = JobPosting::findOrFail($id);

        if ($job->employer_id !== $request->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }

        $job->delete();

        return redirect()->route('employer.jobs.index')->with('success', 'Job deleted successfully.');
    }


}
