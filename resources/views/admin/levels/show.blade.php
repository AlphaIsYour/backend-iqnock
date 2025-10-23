@extends('admin.layouts.app')

@section('title', 'Level Detail')
@section('header', 'Level Detail')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Level Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Level Information</h3>
        
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-600">Level Number</p>
                <p class="text-3xl font-bold text-maroon">{{ $level->level_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Name</p>
                <p class="font-bold">{{ $level->level_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Type</p>
                @if($level->is_premium)
                    <span class="bg-gold text-gray-800 px-3 py-1 rounded font-bold">Premium</span>
                @else
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-bold">Free</span>
                @endif
            </div>
            @if($level->coin_price > 0)
            <div>
                <p class="text-sm text-gray-600">Coin Price</p>
                <p class="font-bold text-gold">💰 {{ $level->coin_price }}</p>
            </div>
            @endif
            @if($level->reward_coins > 0)
            <div>
                <p class="text-sm text-gray-600">Reward Coins</p>
                <p class="font-bold text-green-600">🎁 {{ $level->reward_coins }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-600">Status</p>
                @if($level->is_active)
                    <span class="text-green-600"><i class="fas fa-check-circle"></i> Active</span>
                @else
                    <span class="text-red-600"><i class="fas fa-times-circle"></i> Inactive</span>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.levels.edit', $level) }}" class="block w-full bg-maroon text-white text-center py-2 rounded hover:bg-red-900">
                Edit Level
            </a>
        </div>
    </div>

    <!-- Questions -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Questions ({{ $level->questions->count() }})</h3>
                <a href="{{ route('admin.questions.create', ['level_id' => $level->id]) }}" class="bg-gold text-gray-800 px-4 py-2 rounded font-bold hover:bg-yellow-500 text-sm">
                    <i class="fas fa-plus mr-2"></i>Add Question
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($level->questions as $question)
                <div class="border rounded p-4 hover:bg-gray-50">
                    <div class="flex items-start space-x-4">
                        <img src="{{ $question->image_url }}" alt="Question" class="w-32 h-24 object-cover rounded">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-lg">{{ $question->correct_answer }}</p>
                                    <p class="text-sm text-gray-600">Points: {{ $question->points }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($question->is_active)
                                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Active</span>
                                        @else
                                            <span class="text-red-600"><i class="fas fa-times-circle"></i> Inactive</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline" 
                                        onsubmit="return confirm('Delete this question?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>No questions yet. Add one!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection