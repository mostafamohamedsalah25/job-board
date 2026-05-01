<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Skill;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile()->with('skills')->first();
        $skills = Skill::orderBy('name')->get();

        return Inertia::render('Candidate/Profile/Edit', [
            'profile' => $profile,
            'skills' => $skills
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'linkedin_id' => 'nullable|url|max:255',
            'resume' => 'nullable|mimes:pdf|max:2048',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        $profile = $request->user()->profile()->firstOrCreate(['user_id' => $user->id]);

        if ($request->hasFile('resume')) {
            if ($profile->resume_path) {
                Storage::disk('public')->delete($profile->resume_path);
            }

            $path = $request->file('resume')->store('resumes', 'public');
            $data['resume_path'] = $path;
        }

        $profile->update([
            'phone' => $validated['phone'] ?? $profile->phone,
            'bio' => $validated['bio'] ?? $profile->bio,
            'linkedin_id' => $validated['linkedin_id'] ?? $profile->linkedin_id,
        ]);

        if (isset($validated['skills'])) {
            $profile->skills()->sync($validated['skills']);
        } else {
            $profile->skills()->detach(); // إذا مسح كل المهارات
        }

        return back()->with('success', 'Profile updated successfully.');
    }
}
