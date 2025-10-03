<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
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
        $user = $request->user();
        
        // Get user's anime data
        $watchlistStats = [
            'watching' => $user->watchlists()->where('status', 'watching')->count(),
            'completed' => $user->watchlists()->where('status', 'completed')->count(),
            'planned' => $user->watchlists()->where('status', 'planned')->count(),
            'on_hold' => $user->watchlists()->where('status', 'on_hold')->count(),
            'dropped' => $user->watchlists()->where('status', 'dropped')->count(),
        ];
        
        $reviewCount = $user->reviews()->count();
        $recentWatched = $user->watchlists()
            ->where('status', 'completed')
            ->with('anime')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('profile.edit', [
            'user' => $user,
            'watchlistStats' => $watchlistStats,
            'reviewCount' => $reviewCount,
            'recentWatched' => $recentWatched,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

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
