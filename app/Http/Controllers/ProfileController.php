<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Auction;
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
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function show(Request $request): View
    {

        return view('profile.show', [
            'user' => $request->user(),
            'myauctions' => Auction::where('user_id', Auth::id())->get(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.show')->with('status', 'profile-updated');
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

    /**
     * Validate the profile update request.
     */

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $this->user()->id],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email,' . $this->user()->id],
            'fullname' => ['required', 'string', 'max:100'],
            'nif' => ['required', 'string', 'max:20'],
        ];
    }

    public function settings(Request $request): View
    {
        // Implemente conforme necessário
        return view('profile.settings', [
            'user' => $request->user(),
        ]);
    }
}
