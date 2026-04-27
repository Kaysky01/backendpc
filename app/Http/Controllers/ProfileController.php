<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('anggota');

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        DB::transaction(function () use ($user, $validated) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'divisi' => ($validated['divisi'] ?? null) ?: null,
                'role_detail' => ($validated['role_detail'] ?? null) ?: null,
            ];

            $user->fill($userData);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            if (! empty($validated['password'])) {
                $user->password = $validated['password'];
            }

            $user->save();

            $user->anggota()->updateOrCreate(
                [],
                [
                    'npm' => ($validated['npm'] ?? null) ?: null,
                    'prodi' => ($validated['prodi'] ?? null) ?: null,
                    'angkatan' => ($validated['angkatan'] ?? null) ?: null,
                ]
            );
        });

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
