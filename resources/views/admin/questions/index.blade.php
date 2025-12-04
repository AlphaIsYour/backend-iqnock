@extends('admin.layouts.app')

@section('title', 'Questions Management')
@section('header', 'Questions Management')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <h3 class="text-lg font-bold text-gray-800">All Questions</h3>
            <form action="{{ route('admin.questions.index') }}" method="GET" class="flex">
                <select name="level_id" class="px-4 py-2 border border-gray-300 rounded-l focus:outline-none focus:border-maroon">
                    <option value="">All Levels</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                            Level {{ $level->level_number }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-maroon text-white px-4 py-2 rounded-r hover:bg-red-900">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
        </div>
        <a href="{{ route('admin.questions.create') }}" class="bg-gold text-gray-800 px-4 py-2 rounded font-bold hover:bg-yellow-500">
            <i class="fas fa-plus mr-2"></i>Add Question
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-gray-600 text-sm">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Image</th>
                    <th class="px-6 py-3">Level</th>
                    <th class="px-6 py-3">Answer</th>
                    <th class="px-6 py-3">Points</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($questions as $question)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $question->id }}</td>
                    <?php 
                    // Hapus URL domain dan /storage/ dari path gambar
                    $cleanPath = str_replace(url('storage/'), '', $question->image_url);
                    ?>
                    <td class="px-6 py-4">
                    <img src="{{ url($cleanPath) }}" alt="Question" class="w-20 h-16 object-cover rounded">
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-maroon text-white px-2 py-1 rounded text-xs">
                            Level {{ $question->level->level_number }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold">{{ $question->correct_answer }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">{{ $question->points }} pts</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($question->is_active)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Active</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times-circle"></i> Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.questions.show', $question) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
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
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No questions found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $questions->links() }}
    </div>
</div>
@endsection