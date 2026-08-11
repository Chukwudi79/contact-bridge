<?php

namespace App\Http\Controllers;

use App\Mail\AdminSubmissionReply;
use App\Mail\ContactSubmission as ContactSubmissionMail;
use App\Models\ContactSource;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    public function show(ContactSubmission $submission): View
    {
        return view('admin.submissions.show', [
            'submission' => $submission,
            'events' => $submission->events()->with('user')->get(),
        ]);
    }

    public function update(Request $request, ContactSubmission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,sent,failed,in_progress,resolved'],
        ]);

        $oldStatus = $submission->status;
        $submission->update(['status' => $validated['status']]);
        $this->recordEvent($submission, 'status_updated', $submission->status, 'Submission status updated by an administrator.', $request->user()->id, ['from' => $oldStatus, 'to' => $submission->status]);

        Log::info('Contact submission status updated', ['submission_id' => $submission->id, 'from' => $oldStatus, 'to' => $submission->status, 'admin_id' => $request->user()->id]);

        return back()->with('success', 'Submission status updated.');
    }

    public function resend(Request $request, ContactSubmission $submission): RedirectResponse
    {
        if ($submission->status !== 'failed') {
            return back()->with('error', 'Only failed deliveries can be resent.');
        }

        $this->recordEvent($submission, 'delivery_resend_requested', 'pending', 'An administrator requested a resend.', $request->user()->id);
        $submission->update(['status' => 'pending', 'failure_reason' => null]);

        try {
            Mail::to($submission->recipient)->send(
                (new ContactSubmissionMail($this->payload($submission), $this->sourceFor($submission)))->replyTo($submission->email)
            );
            $submission->update(['status' => 'sent', 'sent_at' => now()]);
            $this->recordEvent($submission, 'delivery_resent', 'sent', 'The failed delivery was resent successfully.', $request->user()->id);
            Log::info('Contact submission resent', ['submission_id' => $submission->id, 'admin_id' => $request->user()->id]);

            return back()->with('success', 'Submission resent successfully.');
        } catch (\Throwable $exception) {
            $submission->update(['status' => 'failed', 'failure_reason' => $exception->getMessage()]);
            $this->recordEvent($submission, 'delivery_resend_failed', 'failed', 'The resend attempt failed: '.$exception->getMessage(), $request->user()->id);
            Log::error('Contact submission resend failed', ['submission_id' => $submission->id, 'admin_id' => $request->user()->id, 'exception' => $exception]);

            return back()->with('error', 'Resend failed. Review the delivery timeline for the SMTP error.');
        }
    }

    public function reply(Request $request, ContactSubmission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $this->recordEvent($submission, 'reply_attempted', 'pending', 'An administrator started a direct reply.', $request->user()->id, ['subject' => $validated['subject']]);

        try {
            Mail::to($submission->email)->send(new AdminSubmissionReply($submission, $validated['body'], $validated['subject']));
            $this->recordEvent($submission, 'reply_sent', 'sent', 'A direct reply was sent to '.$submission->email.'.', $request->user()->id, ['subject' => $validated['subject'], 'body' => $validated['body']]);
            Log::info('Admin reply sent for contact submission', ['submission_id' => $submission->id, 'admin_id' => $request->user()->id]);

            return back()->with('success', 'Reply sent to '.$submission->email.'.');
        } catch (\Throwable $exception) {
            $this->recordEvent($submission, 'reply_failed', 'failed', 'The direct reply failed: '.$exception->getMessage(), $request->user()->id, ['subject' => $validated['subject']]);
            Log::error('Admin reply failed for contact submission', ['submission_id' => $submission->id, 'admin_id' => $request->user()->id, 'exception' => $exception]);

            return back()->with('error', 'Reply failed. Review the delivery timeline for the SMTP error.');
        }
    }

    private function payload(ContactSubmission $submission): array
    {
        return [
            'first_name' => $submission->first_name,
            'last_name' => $submission->last_name,
            'email' => $submission->email,
            'product' => $submission->product,
            'message' => $submission->message,
            'website_origin' => $submission->website_origin,
            'submitted_at' => $submission->created_at->toIso8601String(),
            'recipient' => $submission->recipient,
        ];
    }

    private function sourceFor(ContactSubmission $submission): ContactSource
    {
        return ContactSource::query()->where('origin', $submission->website_origin)->first()
            ?? new ContactSource(['origin' => $submission->website_origin, 'recipient' => $submission->recipient]);
    }

    private function recordEvent(ContactSubmission $submission, string $event, ?string $status, string $message, ?int $userId = null, array $context = []): void
    {
        $submission->events()->create([
            'user_id' => $userId,
            'event' => $event,
            'status' => $status,
            'message' => $message,
            'context' => $context ?: null,
        ]);
    }
}
