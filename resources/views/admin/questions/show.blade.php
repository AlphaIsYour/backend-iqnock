@extends('admin.layouts.app')

@section('title', 'Question Detail')
@section('header', 'Question Detail')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Question Image -->
        <div class="bg-gray-900 p-8">
                    <?php 
                    // Hapus URL domain dan /storage/ dari path gambar
                    $cleanPath = str_replace(url('storage/'), '', $question->image_url);
                    ?>
                    <img src="{{ url('my-storage/' . $cleanPath) }}" alt="Question" class="w-20 h-16 object-cover rounded" alt="Question Image" class="w-full max-w-2xl mx-auto rounded-lg shadow-2xl">
        </div>

        <!-- Question Info -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Level</p>
                        <span class="bg-maroon text-white px-4 py-2 rounded font-bold inline-block">
                            Level {{ $question->level->level_number }} - {{ $question->level->level_name }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Correct Answer</p>
                        <p class="text-3xl font-bold text-maroon">{{ $question->correct_answer }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Points</p>
                        <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded font-bold text-xl inline-block">
                            {{ $question->points }} Points
                        </span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        @if($question->is_active)
                            <span class="bg-green-100 text-green-800 px-4 py-2 rounded font-bold inline-block">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 px-4 py-2 rounded font-bold inline-block">
                                <i class="fas fa-times-circle"></i> Inactive
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Created At</p>
                        <p class="font-medium">{{ $question->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Last Updated</p>
                        <p class="font-medium">{{ $question->updated_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Image URL</p>
                        <p class="text-xs text-gray-500 break-all">{{ $question->image_url }}</p>
                    </div>
                </div>
            </div>

            <!-- Hint Preview -->
            <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <p class="text-sm text-gray-600 mb-2">
                    <i class="fas fa-lightbulb text-yellow-500"></i> Hint Preview (what users will see)
                </p>
                <p class="text-2xl font-mono tracking-wider text-yellow-800">
                    {{ $question->getHint() }}
                </p>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex space-x-2">
                <a href="{{ route('admin.questions.edit', $question) }}" class="flex-1 bg-maroon text-white text-center py-3 rounded font-bold hover:bg-red-900">
                    <i class="fas fa-edit mr-2"></i>Edit Question
                </a>
                <a href="{{ route('admin.questions.index') }}" class="flex-1 bg-gray-500 text-white text-center py-3 rounded font-bold hover:bg-gray-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
                <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="flex-1" 
                    onsubmit="return confirm('Are you sure you want to delete this question?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white py-3 rounded font-bold hover:bg-red-700">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Answer Statistics (Optional - future feature) -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-bar text-maroon mr-2"></i>Statistics
        </h3>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div class="p-4 bg-blue-50 rounded">
                <p class="text-3xl font-bold text-blue-600">0</p>
                <p class="text-sm text-gray-600">Times Answered</p>
            </div>
            <div class="p-4 bg-green-50 rounded">
                <p class="text-3xl font-bold text-green-600">0</p>
                <p class="text-sm text-gray-600">Correct Answers</p>
            </div>
            <div class="p-4 bg-red-50 rounded">
                <p class="text-3xl font-bold text-red-600">0</p>
                <p class="text-sm text-gray-600">Wrong Answers</p>
            </div>
        </div>
        <p class="text-center text-sm text-gray-500 mt-4">
            <i class="fas fa-info-circle"></i> Statistics feature coming soon
        </p>
    </div>
</div>
@endsection