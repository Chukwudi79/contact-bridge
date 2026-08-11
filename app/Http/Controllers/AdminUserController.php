<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->latest()->paginate(15),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_admin' => $request->boolean('is_admin'),
            'email_verified_at' => now(),
        ]);

        Log::info('Admin user created', ['user_id' => $user->id, 'created_by' => $request->user()->id]);

        return back()->with('success', 'User account created.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        $isAdmin = $request->boolean('is_admin');
        if ($user->is_admin && ! $isAdmin && User::query()->where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'At least one administrator must retain dashboard access.');
        }

        $changes = ['name' => $validated['name'], 'email' => $validated['email'], 'is_admin' => $isAdmin];
        if (filled($validated['password'] ?? null)) {
            $changes['password'] = $validated['password'];
        }
        $user->update($changes);

        Log::info('Admin user updated', ['user_id' => $user->id, 'updated_by' => $request->user()->id]);

        return back()->with('success', 'User account updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->is_admin && User::query()->where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'At least one administrator must retain dashboard access.');
        }

        $userId = $user->id;
        $user->delete();
        Log::warning('Admin user deleted', ['user_id' => $userId, 'deleted_by' => $request->user()->id]);

        return back()->with('success', 'User account deleted.');
    }
}
