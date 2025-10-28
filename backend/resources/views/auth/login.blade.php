@extends('layouts.app')

@section('title', 'Login - Prime Studio')

@section('content')
<div class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Prime Studio</h1>
            <p class="text-gray-600">Masuk ke dashboard Anda</p>
        </div>

        <!-- Alert -->
        @if (session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                    placeholder="admin@photostudio.com"
                    required
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                        required
                    >
                    <button 
                        type="button" 
                        onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                    >
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </label>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                    Lupa password?
                </a>
            </div>

            <button 
                type="submit" 
                class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:opacity-90 transition"
            >
                Masuk
            </button>
        </form>

        <!-- Demo Accounts -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm font-semibold text-gray-700 mb-3">
                <i class="fas fa-info-circle mr-2"></i>Akun Demo:
            </p>
            <div class="space-y-2 text-sm text-gray-600">
                <div>
                    <strong>Admin:</strong> admin@photostudio.com / password123
                </div>
                <div>
                    <strong>Customer:</strong> budi@example.com / password123
                </div>
            </div>
        </div>

        <!-- Register Link -->
        <div class="mt-6 text-center text-sm text-gray-600">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Daftar sekarang</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
