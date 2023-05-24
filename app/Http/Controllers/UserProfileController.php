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

        $user->update([
            'spending_limit' => $request->input('spending_limit')
        ]);

        return redirect()->back();
    }
}
