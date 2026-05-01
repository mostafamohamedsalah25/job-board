<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Exception;

class LinkedInController extends Controller
{
    public function redirectToLinkedIn()
    {
        return Socialite::driver('linkedin-openid')->redirect();
    }

    public function handleLinkedInCallback(Request $request)
    {
        try {
            $linkedinUser = Socialite::driver('linkedin-openid')->user();

            $profile = $request->user()->profile;
            $profile->update([
                    'linkedin_id' => $linkedinUser->id,
                    // يمكننا أيضاً سحب الـ bio لو كان فارغاً (اختياري)
                    // 'bio' => $profile->bio ?? $linkedinUser->user['headline'] ?? null,
            ]);

            // Here you would typically find or create a user in your database
            // and log them in. For example:
            // $user = User::firstOrCreate([...]);
            // Auth::login($user);

            return redirect()->route('candidate.profile.edit')->with('success', 'LinkedIn account connected successfully!');
        } catch (Exception $e) {
            return redirect()->route('candidate.profile.edit')->with('error', 'Failed to connect LinkedIn account. Please try again. ' . $e->getMessage());
        }
    }
}
