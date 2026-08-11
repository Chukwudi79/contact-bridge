<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmission;
use App\Models\ContactSubmission as ContactSubmissionRecord;
use App\Models\ContactSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactSubmissionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:254'],
            'product' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:10000'],
            'recipient' => ['required', 'email:rfc', 'max:254'],
        ]);

        $origin = rtrim(trim((string) $request->header('Origin')), '/');
        /** @var ContactSource $source */
        $source = $request->attributes->get('contact_source');

        if (! hash_equals(strtolower($source->recipient), strtolower($validated['recipient']))) {
            Log::warning('Rejected contact submission for unregistered recipient', [
                'origin' => $origin,
                'recipient' => $validated['recipient'],
            ]);

            return response()->json(['message' => 'This recipient is not registered for the submitting website.'], 403);
        }

        Log::info('Contact submission received', ['origin' => $origin, 'recipient' => $validated['recipient']]);
        $submission = [
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'product' => $validated['product'] ?: 'General inquiry',
            'message' => $validated['message'],
            'website_origin' => $origin,
            'submitted_at' => now()->toIso8601String(),
            'recipient' => $validated['recipient'],
        ];

        $record = ContactSubmissionRecord::create([
            'website_origin' => $submission['website_origin'],
            'recipient' => $submission['recipient'],
            'first_name' => $submission['first_name'],
            'last_name' => $submission['last_name'],
            'email' => $submission['email'],
            'product' => $submission['product'],
            'message' => $submission['message'],
            'status' => 'pending',
        ]);

        try {
            Mail::to($submission['recipient'])
                ->send((new ContactSubmission($submission))->replyTo($submission['email']));
            $record->update(['status' => 'sent', 'sent_at' => now()]);
            Log::info('Contact submission delivered', ['submission_id' => $record->id, 'origin' => $submission['website_origin'], 'recipient' => $submission['recipient']]);
        } catch (\Throwable $exception) {
            $record->update(['status' => 'failed', 'failure_reason' => $exception->getMessage()]);
            Log::error('Contact submission delivery failed', ['submission_id' => $record->id, 'origin' => $submission['website_origin'], 'recipient' => $submission['recipient'], 'exception' => $exception]);
            return response()->json(['message' => 'We could not send your message right now.'], 503);
        }

        return response()->json([
            'message' => 'Your message has been sent successfully.',
        ], 202);
    }
}
