@extends('admin.layouts.app')

@section('title', 'Feedback Management')
@section('header', 'Feedback Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <h3 class="text-lg font-bold text-gray-800">User Feedback</h3>
            <form action="{{ route('admin.feedback.index') }}" method="GET" class="flex">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-l focus:outline-none focus:border-maroon">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
                <button type="submit" class="bg-maroon text-white px-4 py-2 rounded-r hover:bg-red-900">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
        </div>
        <div class="flex space-x-2">
            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded text-sm font-bold">
                {{ \App\Models\Feedback::where('status', 'pending')->count() }} Pending
            </span>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600 text-sm">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Message</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($feedbacks as $feedback)
                <tr class="hover:bg-gray-50 {{ $feedback->status == 'pending' ? 'bg-yellow-50' : '' }}">
                    <td class="px-6 py-4">{{ $feedback->id }}</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium">{{ $feedback->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $feedback->user->email }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm">{{ Str::limit($feedback->message, 80) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded text-xs font-bold
                            @if($feedback->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($feedback->status == 'reviewed') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($feedback->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm">{{ $feedback->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $feedback->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.feedback.show', $feedback) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" class="inline" 
                                onsubmit="return confirm('Delete this feedback?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No feedback found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $feedbacks->links() }}
    </div>
</div>
@endsection