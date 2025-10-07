<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // ✅ Show all users
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        return view('admin.manage_users', compact('users'));
    }

    // ✅ Delete user
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    // ✅ Update user (without editing user_type)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate input
        $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->username = $request->username;
        $user->email    = $request->email;

        // Only update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
}
