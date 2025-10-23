<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $feedbacks = $query->latest()->paginate(20);

        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function show(Feedback $feedback)
    {
        return view('admin.feedback.show', compact('feedback'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
            'admin_reply' => 'nullable|string|max:1000',
        ]);

        $feedback->update([
            'status' => $request->status,
            'admin_reply' => $request->admin_reply,
            'reviewed_at' => $request->status !== 'pending' ? now() : null,
        ]);

        return back()->with('success', 'Feedback updated successfully');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->route('admin.feedback.index')->with('success', 'Feedback deleted successfully');
    }
}