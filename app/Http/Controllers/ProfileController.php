<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Validasi password lama jika user ingin ganti password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah']);
            }

            $user->password = Hash::make($request->password);
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }
}
