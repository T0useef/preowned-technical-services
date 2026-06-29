<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->latest()->get();

        return view('users.index', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:255',
            'role' => 'required|in:foreman,driver,labour',
            'status' => 'required|boolean',
            'salary' => 'required|numeric|min:0',
        ]);

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make('12345678'),
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->boolean('status'),
            'salary' => $request->salary,
        ]);

        return response()->json(['message' => 'User created successfully', 'data' => $user], 201);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'role' => 'required|in:foreman,driver,labour',
            'status' => 'required|boolean',
            'salary' => 'required|numeric|min:0',
            'password' => 'nullable|string|min:8',
        ]);

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->boolean('status'),
            'salary' => $request->salary,
        ];

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($request->password);
        }

        $user->update($payload);

        return response()->json(['message' => 'User updated successfully', 'data' => $user]);
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
