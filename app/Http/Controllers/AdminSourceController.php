<?php

namespace App\Http\Controllers;

use App\Models\ContactSource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AdminSourceController extends Controller
{
    public function index(): View
    {
        return view('admin.sources.index', ['sources' => ContactSource::query()->latest()->get()]);
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
}
