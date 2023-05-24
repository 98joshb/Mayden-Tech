<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'spending_limit' => 'nullable|numeric|min:0',
    ]);

    $user->spending_limit = $request->input('spending_limit');
    $user->save();

    return redirect()->back()->with('success', 'Spending limit updated successfully.');
}
}
