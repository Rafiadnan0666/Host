<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.usercard', compact('users'));
    }

    public function create()
    {
        return view('admin.usercard');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'alamat' => 'required',
            'no_telp' => 'required',
            'role' => 'required|in:a,h,p',
            'password' => 'required|min:6',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $path = $request->file('gambar') ? $request->file('gambar')->store('profiles', 'public') : null;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'gambar' => $path,
        ]);

        return redirect()->route('usercontrol.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.usercard', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'alamat' => 'required',
            'no_telp' => 'required',
            'role' => 'required|in:a,h,p',
            'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($request->hasFile('gambar')) {
            if ($user->gambar) {
                Storage::disk('public')->delete($user->gambar);
            }
            $user->gambar = $request->file('gambar')->store('profiles', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'role' => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'gambar' => $user->gambar,
        ]);

        return redirect()->route('usercontrol.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->gambar) {
            Storage::disk('public')->delete($user->gambar);
        }
        $user->delete();

        return redirect()->route('usercontrol.index')->with('success', 'User deleted successfully.');
    }
}

