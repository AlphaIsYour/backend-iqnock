<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - IQnock</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: '#8B0000',
                        gold: '#FFD700',
                        'dark-maroon': '#5C0000',
                        'light-gold': '#FFED4E',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-maroon text-white shadow-xl">
            <div class="p-6 border-b border-gray-300">
                <img src="/image/logo/iqnock.png" alt="Logo iQnock" class="w-20 h-20">
                
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-dark-maroon transition {{ request()->routeIs('admin.dashboard') ? 'bg-dark-maroon border-l-4 border-gold' : '' }}">
                    <i class="fas fa-home mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 hover:bg-dark-maroon transition {{ request()->routeIs('admin.users.*') ? 'bg-dark-maroon border-l-4 border-gold' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <a href="{{ route('admin.levels.index') }}" class="flex items-center px-6 py-3 hover:bg-dark-maroon transition {{ request()->routeIs('admin.levels.*') ? 'bg-dark-maroon border-l-4 border-gold' : '' }}">
                    <i class="fas fa-layer-group mr-3"></i>
                    Levels
                </a>
                <a href="{{ route('admin.questions.index') }}" class="flex items-center px-6 py-3 hover:bg-dark-maroon transition {{ request()->routeIs('admin.questions.*') ? 'bg-dark-maroon border-l-4 border-gold' : '' }}">
                    <i class="fas fa-question-circle mr-3"></i>
                    Questions
                </a>
                <a href="{{ route('admin.leaderboard.index') }}" class="flex items-center px-6 py-3 hover:bg-dark-maroon transition {{ request()->routeIs('admin.leaderboard.*') ? 'bg-dark-maroon border-l-4 border-gold' : '' }}">
                    <i class="fas fa-trophy mr-3"></i>
                    Leaderboard
                </a>
                <a href="{{ route('admin.feedback.index') }}" class="flex items-center px-6 py-3 hover:bg-dark-maroon transition {{ request()->routeIs('admin.feedback.*') ? 'bg-dark-maroon border-l-4 border-gold' : '' }}">
                    <i class="fas fa-comment-dots mr-3"></i>
                    Feedback
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-md">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">@yield('header', 'Dashboard')</h2>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ auth('admin')->user()->name }}</span>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-maroon text-white px-4 py-2 rounded hover:bg-dark-maroon transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>