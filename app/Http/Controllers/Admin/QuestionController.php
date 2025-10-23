<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with('level');

        if ($request->has('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        $questions = $query->latest()->paginate(20);
        $levels = Level::orderBy('level_number')->get();

        return view('admin.questions.index', compact('questions', 'levels'));
    }

    public function create()
    {
        $levels = Level::orderBy('level_number')->get();
        return view('admin.questions.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'correct_answer' => 'required|string|max:255',
            'points' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $imagePath = $request->file('image')->store('questions', 'public');

        Question::create([
            'level_id' => $request->level_id,
            'image_url' => Storage::url($imagePath),
            'correct_answer' => strtoupper($request->correct_answer),
            'points' => $request->points,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Question created successfully');
    }

    public function show(Question $question)
    {
        return view('admin.questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $levels = Level::orderBy('level_number')->get();
        return view('admin.questions.edit', compact('question', 'levels'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'correct_answer' => 'required|string|max:255',
            'points' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'level_id' => $request->level_id,
            'correct_answer' => strtoupper($request->correct_answer),
            'points' => $request->points,
            'is_active' => $request->is_active,
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($question->image_url) {
                $oldPath = str_replace('/storage/', '', $question->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('questions', 'public');
            $data['image_url'] = Storage::url($imagePath);
        }

        $question->update($data);

        return redirect()->route('admin.questions.index')->with('success', 'Question updated successfully');
    }

    public function destroy(Question $question)
    {
        // Delete image
        if ($question->image_url) {
            $imagePath = str_replace('/storage/', '', $question->image_url);
            Storage::disk('public')->delete($imagePath);
        }

        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully');
    }
}