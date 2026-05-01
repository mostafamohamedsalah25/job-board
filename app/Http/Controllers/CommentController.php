<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\JobPosting;

class CommentController extends Controller
{
    public function store(Request $request, JobPosting $jobPosting)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $jobPosting->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->input('body'),
        ]);

        return back()->with('success', 'Comment added successfully.');
    }

    public function toggleHidden(Request $request, Comment $comment)
    {
        if (!$request->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $comment->update([
            'is_hidden' => !$comment->is_hidden
        ]);

        $status = $comment->is_hidden ? 'hidden' : 'visible';
        return back()->with('success', "Comment is now {$status}.");
    }
}
