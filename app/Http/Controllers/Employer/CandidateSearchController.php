<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Skill;
use Inertia\Inertia;

class CandidateSearchController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'skill_id']);

        $candidates = User::role('candidate')
            ->whereHas('profile')
            ->with(['profile.skills'])
            ->when($filters['search'] ?? false, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhereHas('profile', function($q2) use ($search) {
                          $q2->where('bio', 'like', "%{$search}%");
                      });
                });
            })
            ->when($filters['skill_id'] ?? false, function ($query, $skill_id) {
                $query->whereHas('profile.skills', function($q) use ($skill_id) {
                    $q->where('skills.id', $skill_id);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $skills = Skill::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Employer/Candidates/Index', [
            'candidates' => $candidates,
            'filters' => $filters,
            'skills' => $skills
        ]);
    }
}
