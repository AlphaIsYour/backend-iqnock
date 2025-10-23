@extends('admin.layouts.app')

@section('title', 'Levels Management')
@section('header', 'Levels Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">All Levels</h3>
        <a href="{{ route('admin.levels.create') }}" class="bg-gold text-gray-800 px-4 py-2 rounded font-bold hover:bg-yellow-500">
            <i class="fas fa-plus mr-2"></i>Add Level
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600 text-sm">
                    <th class="px-6 py-3">Level #</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Coin Price</th>
                    <th class="px-6 py-3">Reward</th>
                    <th class="px-6 py-3">Questions</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($levels as $level)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="bg-maroon text-white px-3 py-1 rounded font-bold">{{ $level->level_number }}</span>
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $level->level_name }}</td>
                    <td class="px-6 py-4">
                        @if($level->is_premium)
                            <span class="bg-gold text-gray-800 px-2 py-1 rounded text-xs font-bold">Premium</span>
                        @else
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Free</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($level->coin_price > 0)
                            <span class="text-gold font-bold">💰 {{ $level->coin_price }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($level->reward_coins > 0)
                            <span class="text-green-600 font-bold">🎁 {{ $level->reward_coins }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ $level->questions_count }} Questions</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($level->is_active)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Active</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times-circle"></i> Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.levels.show', $level) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.levels.edit', $level) }}" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.levels.destroy', $level) }}" method="POST" class="inline" 
                                onsubmit="return confirm('Delete this level?')">
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
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">No levels found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $levels->links() }}
    </div>
</div>
@endsection