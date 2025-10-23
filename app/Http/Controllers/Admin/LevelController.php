<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::withCount('questions')->orderBy('level_number')->paginate(20);
        return view('admin.levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.levels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_number' => 'required|integer|unique:levels',
            'level_name' => 'required|string|max:255',
            'is_premium' => 'required|boolean',
            'coin_price' => 'nullable|integer|min:0',
            'reward_coins' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        Level::create($request->all());

        return redirect()->route('admin.levels.index')->with('success', 'Level created successfully');
    }

    public function show(Level $level)
    {
        $level->load('questions');
        return view('admin.levels.show', compact('level'));
    }

    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'level_number' => 'required|integer|unique:levels,level_number,' . $level->id,
            'level_name' => 'required|string|max:255',
            'is_premium' => 'required|boolean',
            'coin_price' => 'nullable|integer|min:0',
            'reward_coins' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $level->update($request->all());

        return redirect()->route('admin.levels.index')->with('success', 'Level updated successfully');
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return redirect()->route('admin.levels.index')->with('success', 'Level deleted successfully');
    }
}