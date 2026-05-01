<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Employer\JobPostingController;
use App\Http\Controllers\Employer\DashboardController;
use App\Http\Controllers\Employer\ApplicationController;
use App\Http\Controllers\Employer\PaymentController;
use App\Http\Controllers\Candidate\ProfileController as CandidateProfileController;
use App\Http\Controllers\Candidate\JobController as CandidateJobController;
use App\Http\Controllers\Candidate\ApplicationController as CandidateAppController;
use App\Http\Controllers\Candidate\LinkedInController;
use App\Http\Controllers\Employer\CandidateSearchController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('employer')) {
        return redirect()->route('employer.dashboard');
    } else {
        return redirect()->route('candidate.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::patch('/jobs/{id}/status', [AdminDashboardController::class, 'updateJobStatus'])->name('jobs.status');
});

Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('jobs', JobPostingController::class);
    Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');

    Route::get('/applications/{applicationId}/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/applications/{applicationId}/pay', [PaymentController::class,'processPayment'])->name('payments.processPayment');
    Route::get('/applications/{applicationId}/payment/success', [PaymentController::class, 'success'])->name('payments.success');

    Route::get('/candidates/search', [CandidateSearchController::class, 'index'])->name('candidates.search');

});

Route::middleware(['auth', 'role:candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Candidate/Dashboard');
    })->name('dashboard');

    Route::get('/profile', [CandidateProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [CandidateProfileController::class, 'update'])->name('profile.update');

    Route::get('/jobs', [CandidateJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [CandidateJobController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{job}/apply', [CandidateJobController::class, 'apply'])->name('jobs.apply');

    Route::get('/applications', [CandidateAppController::class, 'index'])->name('applications.index');
    Route::delete('/applications/{application}', [CandidateAppController::class, 'destroy'])->name('applications.destroy');

    // LinkedIn OAuth Routes
    Route::get('/linkedin/redirect', [LinkedInController::class, 'redirectToLinkedIn'])->name('linkedin.redirect');
    Route::get('/linkedin/callback', [LinkedInController::class, 'handleLinkedInCallback'])->name('linkedin.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
