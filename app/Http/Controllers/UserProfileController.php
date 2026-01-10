<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('profile.edit')->withErrors(['error' => 'User not found']);
        }

        $gambarPath = $user->gambar;

        if ($request->hasFile('gambar')) {
            if ($user->gambar) {
                Storage::disk('public')->delete($user->gambar);
            }
            $gambarPath = $request->file('gambar')->store('user_images', 'public');
        }

        $user->nama = $request->input('nama');
        $user->gambar = $gambarPath;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile berhasil diperbarui!');
    }
}
