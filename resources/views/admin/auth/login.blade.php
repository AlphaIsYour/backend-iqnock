<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - IQnock</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maroon: '#8B0000',
                        gold: '#FFD700',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-maroon to-[#5b0808] min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md">
        <div class="justify-center flex mb-8">
            <img src="/image/logo/iqnock.png" alt="Logo iQnock" class="w-20 h-20">
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-maroon">
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600">Remember Me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-maroon text-white py-3 rounded font-bold hover:bg-red-900 transition">
                Login
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Default: admin@iqnock.com / password</p>
        </div>
    </div>
</body>
</html>