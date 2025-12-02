@extends('admin.layouts.app')

@section('title', 'Edit Question')
@section('header', 'Edit Question')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Level *</label>
                <select name="level_id" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('level_id') border-red-500 @enderror">
                    <option value="">Select Level</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ old('level_id', $question->level_id) == $level->id ? 'selected' : '' }}>
                            Level {{ $level->level_number }} - {{ $level->level_name }}
                        </option>
                    @endforeach
                </select>
                @error('level_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Current Image</label>
                    <?php 
                    // Hapus URL domain dan /storage/ dari path gambar
                    $cleanPath = str_replace(url('storage/'), '', $question->image_url);
                    ?>
                    <img src="{{ url('my-storage/' . $cleanPath) }}" alt="Current Question" class="w-full max-w-md h-48 object-cover rounded border-2 border-gray-300 mb-2">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Change Image (leave empty to keep current)</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('image') border-red-500 @enderror">
                <p class="text-sm text-gray-500 mt-1">Max 2MB (JPEG, PNG, JPG, GIF)</p>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Correct Answer *</label>
                <input type="text" name="correct_answer" value="{{ old('correct_answer', $question->correct_answer) }}" required placeholder="e.g., KUCING"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('correct_answer') border-red-500 @enderror">
                <p class="text-sm text-gray-500 mt-1">Answer will be automatically converted to UPPERCASE</p>
                @error('correct_answer')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Points *</label>
                <input type="number" name="points" value="{{ old('points', $question->points) }}" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon @error('points') border-red-500 @enderror">
                @error('points')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Status *</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', $question->is_active) == '1' ? 'checked' : '' }} class="mr-2">
                        <span>Active</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="is_active" value="0" {{ old('is_active', $question->is_active) == '0' ? 'checked' : '' }} class="mr-2">
                        <span>Inactive</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.questions.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="bg-maroon text-white px-6 py-2 rounded hover:bg-red-900">
                    Update Question
                </button>
            </div>
        </form>
    </div>
</div>
@endsection