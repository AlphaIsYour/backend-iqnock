@extends('admin.layouts.app')

@section('title', 'Leaderboard Management')
@section('header', 'Leaderboard Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <h3 class="text-lg font-bold text-gray-800">Leaderboard Rankings</h3>
            <form action="{{ route('admin.leaderboard.index') }}" method="GET" class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search player..." 
                    class="px-4 py-2 border border-gray-300 rounded-l focus:outline-none focus:border-maroon">
                <button type="submit" class="bg-maroon text-white px-4 py-2 rounded-r hover:bg-red-900">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <form action="{{ route('admin.leaderboard.update-rankings') }}" method="POST">
            @csrf
            <button type="submit" class="bg-gold text-gray-800 px-4 py-2 rounded font-bold hover:bg-yellow-500">
                <i class="fas fa-sync mr-2"></i>Update Rankings
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600 text-sm">
                    <th class="px-6 py-3">Rank</th>
                    <th class="px-6 py-3">Player</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Total Score</th>
                    <th class="px-6 py-3">Levels Completed</th>
                    <th class="px-6 py-3">Current Level</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($leaderboards as $entry)
                <tr class="hover:bg-gray-50 {{ $entry->rank <= 3 ? 'bg-yellow-50' : '' }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($entry->rank == 1)
                                <span class="text-4xl mr-2">🥇</span>
                            @elseif($entry->rank == 2)
                                <span class="text-4xl mr-2">🥈</span>
                            @elseif($entry->rank == 3)
                                <span class="text-4xl mr-2">🥉</span>
                            @else
                                <span class="text-2xl font-bold text-gray-600 mr-2">{{ $entry->rank }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-800">{{ $entry->user->name }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $entry->user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="text-2xl font-bold text-maroon">{{ number_format($entry->total_score) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded font-bold">
                            {{ $entry->levels_completed }} / 20
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-maroon text-white px-3 py-1 rounded font-bold">
                            Level {{ $entry->user->current_level }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.show', $entry->user) }}" class="text-blue-600 hover:text-blue-800" title="View User">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.leaderboard.reset', $entry) }}" method="POST" class="inline" 
                                onsubmit="return confirm('Reset this player\'s leaderboard stats?')">
                                @csrf
                                <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="Reset Stats">
                                    <i class="fas fa-redo"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No leaderboard data yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $leaderboards->links() }}
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total Players</p>
                <p class="text-3xl font-bold text-maroon">{{ \App\Models\Leaderboard::count() }}</p>
            </div>
            <div class="bg-maroon text-white p-4 rounded-full">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Highest Score</p>
                <p class="text-3xl font-bold text-gold">{{ number_format(\App\Models\Leaderboard::max('total_score') ?? 0) }}</p>
            </div>
            <div class="bg-gold text-gray-800 p-4 rounded-full">
                <i class="fas fa-trophy text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Average Score</p>
                <p class="text-3xl font-bold text-blue-600">{{ number_format(\App\Models\Leaderboard::avg('total_score') ?? 0) }}</p>
            </div>
            <div class="bg-blue-500 text-white p-4 rounded-full">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Completed All</p>
                <p class="text-3xl font-bold text-green-600">{{ \App\Models\Leaderboard::where('levels_completed', 20)->count() }}</p>
            </div>
            <div class="bg-green-500 text-white p-4 rounded-full">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
</div>
@endsection