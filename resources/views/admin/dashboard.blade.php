@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow p-6 ">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Users</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-maroon text-white p-4 rounded-full">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Levels -->
    <div class="bg-white rounded-lg shadow p-6 ">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Levels</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_levels'] }}</p>
            </div>
            <div class="bg-gold text-white p-4 rounded-full">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Questions -->
    <div class="bg-white rounded-lg shadow p-6 ">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Questions</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total_questions'] }}</p>
            </div>
            <div class="bg-blue-500 text-white p-4 rounded-full">
                <i class="fas fa-question-circle text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Pending Feedback -->
    <div class="bg-white rounded-lg shadow p-6 ">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Pending Feedback</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_feedback'] }}</p>
            </div>
            <div class="bg-red-500 text-white p-4 rounded-full">
                <i class="fas fa-comment-dots text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Recent Users</h3>
        </div>
        <div class="p-6">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-600 text-sm">
                        <th class="pb-3">Name</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3">Level</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr class="border-t">
                        <td class="py-3">{{ $user->name }}</td>
                        <td class="py-3 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="py-3">
                            <span class="bg-maroon text-white px-2 py-1 rounded text-xs">{{ $user->current_level }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">No users yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Players -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Top 10 Players</h3>
        </div>
        <div class="p-6">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-600 text-sm">
                        <th class="pb-3">Rank</th>
                        <th class="pb-3">Player</th>
                        <th class="pb-3">Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPlayers as $player)
                    <tr class="border-t">
                        <td class="py-3">
                            @if($player->rank == 1)
                                <span class="text-gold text-xl">🥇</span>
                            @elseif($player->rank == 2)
                                <span class="text-gray-400 text-xl">🥈</span>
                            @elseif($player->rank == 3)
                                <span class="text-orange-600 text-xl">🥉</span>
                            @else
                                <span class="text-gray-600">{{ $player->rank }}</span>
                            @endif
                        </td>
                        <td class="py-3">{{ $player->user->name }}</td>
                        <td class="py-3 font-bold text-maroon">{{ $player->total_score }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">No leaderboard data yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Feedback -->
<div class="bg-white rounded-lg shadow mt-6">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Recent Feedback</h3>
        <a href="{{ route('admin.feedback.index') }}" class="text-maroon hover:text-red-900 text-sm">View All →</a>
    </div>
    <div class="p-6">
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-600 text-sm">
                    <th class="pb-3">User</th>
                    <th class="pb-3">Message</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentFeedback as $feedback)
                <tr class="border-t">
                    <td class="py-3">{{ $feedback->user->name }}</td>
                    <td class="py-3 text-sm text-gray-600">{{ Str::limit($feedback->message, 50) }}</td>
                    <td class="py-3">
                        <span class="px-2 py-1 rounded text-xs 
                            @if($feedback->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($feedback->status == 'reviewed') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($feedback->status) }}
                        </span>
                    </td>
                    <td class="py-3 text-sm text-gray-600">{{ $feedback->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 text-center text-gray-500">No feedback yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection