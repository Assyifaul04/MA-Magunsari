<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil ditambahkan.'
        ]);
    }

    public function edit(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        // Jika password diisi, tambahkan validasi password
        if ($request->filled('password')) {
            $rules['password'] = 'min:6|confirmed';

            if (!$request->filled('password_confirmation')) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'password_confirmation' => ['Konfirmasi password diperlukan jika password diisi.']
                    ]
                ], 422);
            }
        }

        $request->validate($rules);
        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil diperbarui.'
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil dihapus.'
        ]);
    }
}
