<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class   CreditController extends Controller
{
    // Show the form to add credits
    public function showAddCreditsForm()
    {
        return view('credits.add');  // You need to create this view
    }

    // Handle adding credits to the user
    public function addCredits(Request $request)
    {
        $request->validate([
            'credits' => 'required|numeric|min:0.01',  // Validation for positive credit values
        ]);

        $user = Auth::user();
        $user->credits += $request->credits;  // Add the specified credits to the current balance
        $user->save();

        return redirect()->route('profile.show')->with('status', 'Credits added successfully!');
    }
}