@extends('admin.layouts.app')

@section('title', 'Create User')
@section('header', 'Create New User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Password *</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Confirm Password *</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Coins</label>
                    <input type="number" name="coins" value="{{ old('coins', 0) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Hearts</label>
                    <input type="number" name="hearts" value="{{ old('hearts', 5) }}" min="0" max="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Hints</label>
                    <input type="number" name="hints" value="{{ old('hints', 5) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="bg-maroon text-white px-6 py-2 rounded hover:bg-red-900">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection