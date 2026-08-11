<?php

namespace App\Http\Controllers;

use App\Models\ContactSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminSourceController extends Controller
{
    public function index(): View
    {
        return view('admin.sources.index', [
            'sources' => ContactSource::query()->latest()->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'origin' => ['required', 'url:http,https', 'max:255'],
            'recipient' => ['required', 'email:rfc', 'max:254'],
        ]);

        $origin = rtrim(trim($validated['origin']), '/');
        ContactSource::updateOrCreate(
            ['origin' => $origin],
            ['recipient' => $validated['recipient'], 'is_active' => true],
        );

        Log::info('Contact source registered', ['origin' => $origin, 'recipient' => $validated['recipient'], 'admin_id' => $request->user()->id]);

        return back()->with('success', 'Website source registered.');
    }

    public function update(Request $request, ContactSource $source): RedirectResponse
    {
        $validated = $request->validate([
            'recipient' => ['required', 'email:rfc', 'max:254'],
            'is_active' => ['required', 'boolean'],
        ]);

        $source->update($validated);
        Log::info('Contact source updated', ['source_id' => $source->id, 'admin_id' => $request->user()->id]);

        return back()->with('success', 'Website source updated.');
    }

    public function edit(ContactSource $source): View
    {
        return view('admin.sources.edit', ['source' => $source]);
    }

    public function updateDetails(Request $request, ContactSource $source): RedirectResponse
    {
        $validated = $request->validate([
            'origin' => ['required', 'url:http,https', 'max:191', Rule::unique('contact_sources', 'origin')->ignore($source)],
            'recipient' => ['required', 'email:rfc', 'max:254'],
            'is_active' => ['required', 'boolean'],
        ]);

        $validated['origin'] = rtrim(trim($validated['origin']), '/');
        $source->update($validated);
        Log::info('Contact source details updated', ['source_id' => $source->id, 'origin' => $source->origin, 'admin_id' => $request->user()->id]);

        return redirect()->route('admin.sources.index')->with('success', 'Website source details updated.');
    }

    public function editTemplate(ContactSource $source): View
    {
        return view('admin.sources.template', ['source' => $source]);
    }

    public function previewTemplate(ContactSource $source): View
    {
        return view('admin.sources.template-preview', ['source' => $source]);
    }

    public function previewEmail(ContactSource $source): View
    {
        return view('emails.contact-submission', [
            'source' => $source,
            'preview' => true,
            'submission' => [
                'first_name' => 'Adeleke',
                'last_name' => 'Igwe',
                'email' => 'adeleke@example.com',
                'product' => 'BothSign',
                'message' => "Hello team,\n\nI would like to learn more about BothSign and arrange a product demo for my team.",
                'website_origin' => $source->origin,
                'submitted_at' => now()->toIso8601String(),
                'recipient' => $source->recipient,
            ],
        ]);
    }

    public function updateTemplate(Request $request, ContactSource $source): RedirectResponse
    {
        $validated = $request->validate([
            'email_subject' => ['nullable', 'string', 'max:255'],
            'email_eyebrow' => ['nullable', 'string', 'max:100'],
            'email_heading' => ['nullable', 'string', 'max:255'],
            'email_intro' => ['nullable', 'string', 'max:2000'],
            'email_footer' => ['nullable', 'string', 'max:255'],
            'email_header_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'email_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'email_header_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'email_accent_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'email_background_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'remove_header_image' => ['nullable', 'boolean'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('remove_header_image') && $source->email_header_image_path) {
            Storage::disk('public')->delete($source->email_header_image_path);
            $validated['email_header_image_path'] = null;
        }

        if ($request->hasFile('email_header_image')) {
            if ($source->email_header_image_path) {
                Storage::disk('public')->delete($source->email_header_image_path);
            }

            $validated['email_header_image_path'] = $request->file('email_header_image')->store('contact-email-headers', 'public');
        }

        if ($request->boolean('remove_logo') && $source->email_logo_path) {
            Storage::disk('public')->delete($source->email_logo_path);
            $validated['email_logo_path'] = null;
        }

        if ($request->hasFile('email_logo')) {
            if ($source->email_logo_path) {
                Storage::disk('public')->delete($source->email_logo_path);
            }

            $validated['email_logo_path'] = $request->file('email_logo')->store('contact-email-logos', 'public');
        }

        unset($validated['email_header_image'], $validated['remove_header_image'], $validated['email_logo'], $validated['remove_logo']);
        $source->update($validated);

        Log::info('Contact source email template updated', [
            'source_id' => $source->id,
            'origin' => $source->origin,
            'admin_id' => $request->user()->id,
            'has_header_image' => filled($source->email_header_image_path),
        ]);

        return redirect()->route('admin.sources.template.edit', $source)->with('success', 'Email template saved for this website.');
    }
}
