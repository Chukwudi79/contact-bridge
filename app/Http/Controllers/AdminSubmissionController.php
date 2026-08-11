<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminSubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ContactSubmission::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        return view('admin.submissions.index', [
            'submissions' => $query->paginate(15)->withQueryString(),
            'status' => $request->string('status')->toString(),
        ]);
    }

    public function update(Request $request, ContactSubmission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,sent,failed,in_progress,resolved'],
        ]);

        $oldStatus = $submission->status;
        $submission->update(['status' => $validated['status']]);
        Log::info('Contact submission status updated', [
            'submission_id' => $submission->id,
            'from' => $oldStatus,
            'to' => $submission->status,
            'admin_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Submission status updated.');
    }
}
