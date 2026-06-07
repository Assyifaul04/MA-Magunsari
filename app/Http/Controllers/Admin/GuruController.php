<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index()
    {
        // 1. Muat relasi kelas dan user untuk tabel
        $gurus = Guru::with(['kelas', 'user'])->get();

        // 2. Ambil ID user yang sudah terpakai
        $linkedUserIds = Guru::whereNotNull('user_id')->pluck('user_id');
        
        // 3. Ambil user yang BELUM terpakai DAN memiliki role 'guru'
        $availableUsers = User::whereNotIn('id', $linkedUserIds)
                              ->where('role', 'guru') // <-- Tambahan filter role di sini
                              ->get();

        return view('admin.guru.index', compact('gurus', 'availableUsers'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nip'     => 'required|string|unique:gurus,nip',
            'nama'    => 'required|string|max:255',
            'no_hp'   => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id' 
        ];

        if (empty($request->user_id)) {
            $rules['nip'] .= '|unique:users,email';
        }

        $request->validate($rules);

        DB::transaction(function () use ($request) {
            $userId = $request->user_id;

            if (!$userId) {
                $user = User::create([
                    'name'     => $request->nama,
                    'email'    => $request->nip, 
                    'password' => Hash::make('password123'), 
                    'role'     => 'guru',
                ]);
                $userId = $user->id;
            } else {
                $user = User::find($userId);
                $user->update([
                    'name'  => $request->nama,
                    'email' => $request->nip
                ]);
            }

            Guru::create([
                'nip'     => $request->nip,
                'nama'    => $request->nama,
                'no_hp'   => $request->no_hp,
                'user_id' => $userId,
            ]);
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil disimpan dan dikaitkan dengan akun.');
    }

    public function update(Request $request, Guru $guru)
    {
        $rules = [
            'nip'     => 'required|string|unique:gurus,nip,' . $guru->id,
            'nama'    => 'required|string|max:255',
            'no_hp'   => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id'
        ];

        $userIdToCheck = $request->user_id ?: $guru->user_id;
        if ($userIdToCheck) {
            $rules['nip'] .= '|unique:users,email,' . $userIdToCheck;
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $guru) {
            $dataToUpdate = [
                'nip'   => $request->nip,
                'nama'  => $request->nama,
                'no_hp' => $request->no_hp,
            ];

            if ($request->filled('user_id')) {
                $dataToUpdate['user_id'] = $request->user_id;
                
                $user = User::find($request->user_id);
                if ($user) {
                    $user->update(['name' => $request->nama, 'email' => $request->nip]);
                }
            } else {
                if ($guru->user) {
                    $guru->user->update([
                        'name'  => $request->nama,
                        'email' => $request->nip,
                    ]);
                }
            }

            $guru->update($dataToUpdate);
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru dan Akun diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            if ($guru->kelas()->exists()) {
                $guru->kelas()->update(['guru_id' => null]); 
            }

            if ($guru->user) {
                $guru->user->delete();
            }

            $guru->delete();
        });

        if (request()->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Data Guru, Akun terhubung, dan jabatan Wali Kelas berhasil dihapus.'
            ]);
        }

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil dihapus.');
    }
}