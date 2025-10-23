@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('header', 'Users Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <h3 class="text-lg font-bold text-gray-800">All Users</h3>
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." 
                    class="px-4 py-2 border border-gray-300 rounded-l focus:outline-none focus:border-maroon">
                <button type="submit" class="bg-maroon text-white px-4 py-2 rounded-r hover:bg-red-900">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-gold text-gray-800 px-4 py-2 rounded font-bold hover:bg-yellow-500">
            <i class="fas fa-plus mr-2"></i>Add User
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600 text-sm">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Level</th>
                    <th class="px-6 py-3">Score</th>
                    <th class="px-6 py-3">Coins</th>
                    <th class="px-6 py-3">Hearts</th>
                    <th class="px-6 py-3">Hints</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $user->id }}</td>
                    <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-maroon text-white px-2 py-1 rounded text-xs">{{ $user->current_level }}</span>
                    </td>
                    <td class="px-6 py-4 font-bold text-maroon">{{ $user->total_score }}</td>
                    <td class="px-6 py-4">
                        <span class="text-gold">💰 {{ $user->coins }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-red-500">❤️ {{ $user->hearts }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-yellow-500">💡 {{ $user->hints }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
                                onsubmit="return confirm('Are you sure?')">
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
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
</div>
@endsection