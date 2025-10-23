@extends('admin.layouts.app')

@section('title', 'Feedback Detail')
@section('header', 'Feedback Detail')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">User Information</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Name</p>
                    <p class="font-bold">{{ $feedback->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium text-sm">{{ $feedback->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Current Level</p>
                    <span class="bg-maroon text-white px-2 py-1 rounded text-sm">{{ $feedback->user->current_level }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Score</p>
                    <p class="font-bold text-maroon">{{ $feedback->user->total_score }}</p>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.users.show', $feedback->user) }}" class="block w-full bg-maroon text-white text-center py-2 rounded hover:bg-red-900">
                    View User Profile
                </a>
            </div>
        </div>

        <!-- Feedback Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Feedback Message -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Feedback Message</h3>
                    <span class="px-3 py-1 rounded text-xs font-bold
                        @if($feedback->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($feedback->status == 'reviewed') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ ucfirst($feedback->status) }}
                    </span>
                </div>

                <div class="bg-gray-50 rounded p-4 mb-4">
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $feedback->message }}</p>
                </div>

                <div class="flex justify-between text-sm text-gray-500">
                    <span>
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $feedback->created_at->format('d M Y, H:i') }}
                    </span>
                    @if($feedback->reviewed_at)
                    <span>
                        <i class="fas fa-check mr-1"></i>
                        Reviewed: {{ $feedback->reviewed_at->format('d M Y, H:i') }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Admin Reply Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Admin Response</h3>
                
                <form action="{{ route('admin.feedback.update', $feedback) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Status *</label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                            <option value="pending" {{ $feedback->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ $feedback->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="resolved" {{ $feedback->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Admin Reply</label>
                        <textarea name="admin_reply" rows="5" placeholder="Write your response to the user..."
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">{{ old('admin_reply', $feedback->admin_reply) }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">This reply will be visible to the user</p>
                    </div>

                    @if($feedback->admin_reply)
                    <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                        <p class="text-sm text-gray-600 mb-2">Current Reply:</p>
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $feedback->admin_reply }}</p>
                    </div>
                    @endif

                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-maroon text-white py-3 rounded font-bold hover:bg-red-900">
                            <i class="fas fa-save mr-2"></i>Update Feedback
                        </button>
                        <a href="{{ route('admin.feedback.index') }}" class="flex-1 bg-gray-500 text-white text-center py-3 rounded font-bold hover:bg-gray-600">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>
                    </div>
                </form>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <form action="{{ route('admin.feedback.update', $feedback) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="reviewed">
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                            <i class="fas fa-check mr-2"></i>Mark as Reviewed
                        </button>
                    </form>

                    <form action="{{ route('admin.feedback.update', $feedback) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="resolved">
                        <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">
                            <i class="fas fa-check-double mr-2"></i>Mark as Resolved
                        </button>
                    </form>

                    <form action="{{ route('admin.feedback.update', $feedback) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="pending">
                        <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600">
                            <i class="fas fa-undo mr-2"></i>Mark as Pending
                        </button>
                    </form>

                    <form action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" 
                        onsubmit="return confirm('Delete this feedback permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                            <i class="fas fa-trash mr-2"></i>Delete Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection