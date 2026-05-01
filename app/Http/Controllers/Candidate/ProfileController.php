<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->profile;

        return Inertia::render('Candidate/Profile/Edit', [
            'profile' => $profile
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'linkedin_id' => 'nullable|url|max:255',
            'resume' => 'nullable|mimes:pdf|max:2048',
        ]);

        $profile = $request->user()->profile;
        $data = $request->only(['phone', 'bio', 'linkedin_id']);

        if ($request->hasFile('resume')) {
            if ($profile->resume_path) {
                Storage::disk('public')->delete($profile->resume_path);
            }

            $path = $request->file('resume')->store('resumes', 'public');
            $data['resume_path'] = $path;
        }

        $profile->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
