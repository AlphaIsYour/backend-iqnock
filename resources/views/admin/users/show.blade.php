@extends('admin.layouts.app')

@section('title', 'User Detail')
@section('header', 'User Detail')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- User Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">User Information</h3>
        
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-600">Name</p>
                <p class="font-bold">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Email</p>
                <p class="font-bold">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Current Level</p>
                <p class="font-bold text-maroon">Level {{ $user->current_level }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Score</p>
                <p class="font-bold text-maroon">{{ $user->total_score }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Coins</p>
                <p class="font-bold text-gold">💰 {{ $user->coins }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Hearts</p>
                <p class="font-bold text-red-500">❤️ {{ $user->hearts }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Hints</p>
                <p class="font-bold text-yellow-500">💡 {{ $user->hints }}</p>
            </div>
        </div>

        <div class="mt-6 space-y-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="block w-full bg-maroon text-white text-center py-2 rounded hover:bg-red-900">
                Edit User
            </a>
            <form action="{{ route('admin.users.reset-progress', $user) }}" method="POST" onsubmit="return confirm('Reset user progress?')">
                @csrf
                <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600">
                    Reset Progress
                </button>
            </form>
        </div>
    </div>

    <!-- Progress & Leaderboard -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Leaderboard -->
        @if($user->leaderboard)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Leaderboard Position</h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-3xl font-bold text-gold">{{ $user->leaderboard->rank ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">Rank</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-maroon">{{ $user->leaderboard->total_score }}</p>
                    <p class="text-sm text-gray-600">Total Score</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $user->leaderboard->levels_completed }}</p>
                    <p class="text-sm text-gray-600">Levels Completed</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Progress -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Level Progress</h3>
            <div class="space-y-2">
                @forelse($user->progress as $progress)
                <div class="flex items-center justify-between p-3 border rounded">
                    <div>
                        <span class="font-bold">{{ $progress->level->level_name }}</span>
                        @if($progress->level->is_premium)
                            <span class="ml-2 text-xs bg-gold text-gray-800 px-2 py-1 rounded">Premium</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($progress->is_completed)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Completed</span>
                        @elseif($progress->is_unlocked)
                            <span class="text-blue-600"><i class="fas fa-unlock"></i> Unlocked</span>
                        @else
                            <span class="text-gray-400"><i class="fas fa-lock"></i> Locked</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">No progress yet</p>
                @endforelse
            </div>
        </div>

        <!-- Feedback -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">User Feedback</h3>
            <div class="space-y-3">
                @forelse($user->feedback as $feedback)
                <div class="border-l-4 border-maroon p-3 bg-gray-50 rounded">
                    <p class="text-sm text-gray-600 mb-2">{{ $feedback->created_at->format('d M Y H:i') }}</p>
                    <p>{{ $feedback->message }}</p>
                    <span class="inline-block mt-2 px-2 py-1 rounded text-xs 
                        @if($feedback->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($feedback->status == 'reviewed') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ ucfirst($feedback->status) }}
                    </span>
                </div>
                @empty
                <p class="text-center text-gray-500 py-4">No feedback submitted</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection