<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate incoming request data
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Attempt to reset the user's password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                // Update the user's password_hash field (since you're using password_hash instead of password)
                $user->forceFill([
                    'password_hash' => Hash::make($request->password),  // Make sure to hash the password and store it in password_hash column
                ])->save();

                // Trigger password reset event
                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Your password has been successfully reset.');
        } else {
            // If there's an error, return to the previous page with errors
            return back()
                ->withInput($request->only('email'))  // Keep the email field in the form
                ->withErrors(['email' => 'There was an error resetting your password. Please try again.']);
        }
    }
}
