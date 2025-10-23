<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('leaderboard');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'coins' => 'nullable|integer|min:0',
            'hearts' => 'nullable|integer|min:0|max:5',
            'hints' => 'nullable|integer|min:0',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'coins' => $request->coins ?? 0,
            'hearts' => $request->hearts ?? 5,
            'hints' => $request->hints ?? 5,
            'current_level' => 1,
            'total_score' => 0,
        ]);

        // Create leaderboard entry
        Leaderboard::create([
            'user_id' => $user->id,
            'total_score' => 0,
            'levels_completed' => 0,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        $user->load(['progress.level', 'leaderboard', 'feedback']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'coins' => 'nullable|integer|min:0',
            'hearts' => 'nullable|integer|min:0|max:5',
            'hints' => 'nullable|integer|min:0',
            'current_level' => 'nullable|integer|min:1',
        ]);

        $user->update($request->only(['name', 'email', 'coins', 'hearts', 'hints', 'current_level']));

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    public function resetProgress(User $user)
    {
        $user->progress()->delete();
        $user->update([
            'current_level' => 1,
            'hearts' => 5,
            'hints' => 5,
            'total_score' => 0,
        ]);

        $user->leaderboard()->update([
            'total_score' => 0,
            'levels_completed' => 0,
        ]);

        return back()->with('success', 'User progress reset successfully');
    }
}