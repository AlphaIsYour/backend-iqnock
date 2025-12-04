<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Level;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

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

        // Upload ke Cloudinary
        $uploadResult = $this->cloudinary->uploadOptimized(
            $request->file('image'),
            'questions', // folder di Cloudinary
            1200, // max width
            85    // quality
        );

        if (!$uploadResult) {
            return back()->with('error', 'Failed to upload image')->withInput();
        }

        Question::create([
            'level_id' => $request->level_id,
            'image_url' => $uploadResult['url'],
            'cloudinary_public_id' => $uploadResult['public_id'],
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

        // Jika ada gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari Cloudinary
            if ($question->cloudinary_public_id) {
                $this->cloudinary->delete($question->cloudinary_public_id);
            }

            // Upload gambar baru
            $uploadResult = $this->cloudinary->uploadOptimized(
                $request->file('image'),
                'questions',
                1200,
                85
            );

            if (!$uploadResult) {
                return back()->with('error', 'Failed to upload new image')->withInput();
            }

            $data['image_url'] = $uploadResult['url'];
            $data['cloudinary_public_id'] = $uploadResult['public_id'];
        }

        $question->update($data);

        return redirect()->route('admin.questions.index')->with('success', 'Question updated successfully');
    }

    public function destroy(Question $question)
    {
        // Delete image dari Cloudinary
        if ($question->cloudinary_public_id) {
            $this->cloudinary->delete($question->cloudinary_public_id);
        }

        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', 'Question deleted successfully');
    }
}