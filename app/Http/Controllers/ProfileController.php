<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\ProfilePicture;
use Symfony\Component\Mime\Part\File;

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

    public function showProfilePicture($userId)
    {
        // Define the path to the user's profile picture
        $imagePath = public_path('images/users/' . $userId . '.jpg');

        // Check if the image exists in the public folder
        if (file_exists($imagePath)) {
            // Get the file modification time to force the browser to bypass the cache
            $timestamp = filemtime($imagePath);

            return response()->file($imagePath, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Content-Disposition' => 'inline; filename="' . $userId . '.jpg?v=' . $timestamp . '"',
            ]);
        }

        // If the image doesn't exist, return a default image
        return response()->file(public_path('images/users/default.jpg'));
    }


    public function storeProfilePicture(Request $request)
    {
        // Validate that the uploaded file is an image
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::id(); // Get the logged-in user

        // Get the uploaded file
        $image = $request->file('profile_picture');

        // Create a unique file name using the user ID and save it as a .jpg or .png
        $imageName = $user . '.jpg';

        // Store the image in the public/images/users directory
        $image->move(public_path('images/users'), $imageName);

        return back()->with('status', 'Profile picture updated successfully!');
    }



    public function settings(Request $request): View
    {
        // Implemente conforme necessário
        return view('profile.settings', [
            'user' => $request->user(),
        ]);
    }
    public function orders() {
        $user = Auth::user();
        // Através do relacionamento orders() no User
        $orders = $user->orders()->with('transaction.auction')->paginate(10);
        return view('profile.orders', compact('orders', 'user'));
    }

    public function ratings() {
        $user = Auth::user();
        $ratings = $user->receivedRatings()->orderBy('rating_time','desc')->paginate(10);
        return view('profile.ratings', compact('ratings', 'user'));
    }

    public function myAuctions() {
        $user = Auth::user();
        $myauctions = $user->auctions()->paginate(10);
        return view('profile.myauctions', compact('myauctions', 'user'));
    }

    public function soldAuctions() {
        $user = Auth::user();
        // sold auctions = status = 'Closed' (ajuste conforme sua lógica)
        $soldAuctions = $user->auctions()->where('status','Closed')->paginate(10);
        return view('profile.soldauctions', compact('soldAuctions', 'user'));
    }

}
