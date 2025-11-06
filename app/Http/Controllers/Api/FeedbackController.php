<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bug,suggestion,question_idea',
            'content' => 'required|string|max:1000',
        ]);

        $feedback = Feedback::create([
            'user_id' => $request->user()->id,
            'type' => $request->type,
            'message' => $request->input('content'),
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully',
            'data' => $feedback
        ], 201);
    }

    public function submitQuestion(Request $request)
{
    $request->validate([
        'type' => 'required|in:kirim_soal,lapor_bug,kirim_masukan',
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'first_word' => 'nullable|string|max:255',
        'second_word' => 'nullable|string|max:255',
        'description' => 'required|string',
    ]);

    // Format message berdasarkan tipe
    $message = '';
    
    if ($request->type === 'kirim_soal') {
        $message = "Kata Pertama: " . ($request->first_word ?? '-') . "\n";
        $message .= "Kata Kedua: " . ($request->second_word ?? '-') . "\n";
        $message .= "Deskripsi: " . $request->description;
    } else {
        $message = $request->description;
    }

    $feedback = Feedback::create([
        'user_id' => $request->user()->id,
        'type' => $request->type,
        'message' => $message,
        'status' => 'pending',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Submission successful',
        'data' => $feedback
    ], 201);
}
    public function myFeedback(Request $request)
    {
        $feedback = Feedback::where('user_id', $request->user()->id)
                           ->orderBy('created_at', 'desc')
                           ->get();

        return response()->json([
            'success' => true,
            'data' => $feedback
        ]);
    }
}