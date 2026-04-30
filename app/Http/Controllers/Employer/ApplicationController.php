<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function updateStatus(Request $request, string $id) {

        // Validate the request
        $data = $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
            ]);

        // Find the application and update its status
        $application = Application::findOrFail($id);

        if ($application->jobPosting->employer_id !== $request->user()->employer->id) {
            abort(403, 'Unauthorized action.');
        }

        $application->update(['status' => $data['status']]);

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }
}
