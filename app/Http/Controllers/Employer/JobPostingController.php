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
        $jobs = $employer->jobPostings()->latest()->get();

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


    
}
