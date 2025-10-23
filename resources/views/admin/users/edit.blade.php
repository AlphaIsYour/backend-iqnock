@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('header', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">New Password (leave blank to keep current)</label>
                <input type="password" name="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Coins</label>
                    <input type="number" name="coins" value="{{ old('coins', $user->coins) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Current Level</label>
                    <input type="number" name="current_level" value="{{ old('current_level', $user->current_level) }}" min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Hearts</label>
                    <input type="number" name="hearts" value="{{ old('hearts', $user->hearts) }}" min="0" max="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Hints</label>
                    <input type="number" name="hints" value="{{ old('hints', $user->hints) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="bg-maroon text-white px-6 py-2 rounded hover:bg-red-900">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection