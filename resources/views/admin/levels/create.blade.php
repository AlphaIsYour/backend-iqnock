@extends('admin.layouts.app')

@section('title', 'Create Level')
@section('header', 'Create New Level')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.levels.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Level Number *</label>
                <input type="number" name="level_number" value="{{ old('level_number') }}" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('level_number') border-red-500 @enderror">
                @error('level_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Level Name *</label>
                <input type="text" name="level_name" value="{{ old('level_name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('level_name') border-red-500 @enderror">
                @error('level_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Type *</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="is_premium" value="0" {{ old('is_premium', '0') == '0' ? 'checked' : '' }} class="mr-2">
                        <span>Free</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="is_premium" value="1" {{ old('is_premium') == '1' ? 'checked' : '' }} class="mr-2">
                        <span>Premium</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Coin Price (for premium)</label>
                    <input type="number" name="coin_price" value="{{ old('coin_price', 0) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Reward Coins</label>
                    <input type="number" name="reward_coins" value="{{ old('reward_coins', 0) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Status *</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="mr-2">
                        <span>Active</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} class="mr-2">
                        <span>Inactive</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.levels.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="bg-maroon text-white px-6 py-2 rounded hover:bg-red-900">
                    Create Level
                </button>
            </div>
        </form>
    </div>
</div>
@endsection