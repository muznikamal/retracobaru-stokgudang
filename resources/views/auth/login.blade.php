@extends('layouts.guest')

@section('content')
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="floating-element absolute top-10 left-10 text-emerald-200">
            <i class="fas fa-boxes text-3xl" style="animation-delay: 0s;"></i>
        </div>
        <div class="floating-element absolute top-20 right-20 text-emerald-200">
            <i class="fas fa-cube text-2xl" style="animation-delay: 1s;"></i>
        </div>
        <div class="floating-element absolute bottom-40 left-20 text-emerald-200">
            <i class="fas fa-pallet text-3xl" style="animation-delay: 2s;"></i>
        </div>
        <div class="floating-element absolute bottom-20 right-32 text-emerald-200">
            <i class="fas fa-warehouse text-2xl" style="animation-delay: 3s;"></i>
        </div>
        <div class="floating-element absolute top-1/3 left-1/4 text-emerald-200">
            <i class="fas fa-clipboard-list text-2xl" style="animation-delay: 1.5s;"></i>
        </div>
    </div>
    
    <!-- Login Card -->
    <div class="login-card rounded-2xl p-8 w-full max-w-md relative z-10 border-l-4 border-emerald-500">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 rounded-2xl flex items-center justify-center shadow-lg bg-gradient-to-r from-emerald-500 to-emerald-600">
                <i class="fas fa-lock text-3xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Login</h2>
        </div>

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Username Input -->
            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-emerald-500"></i>Username
                </label>
                <div class="relative">
                    <input type="text" name="username" id="username"
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 px-4 py-3 pl-10 input-focus transition"
                        required autofocus placeholder="Masukkan username">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-emerald-400"></i>
                    </div>
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-emerald-500"></i>Password
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 px-4 py-3 pl-10 input-focus transition"
                        required placeholder="Masukkan password">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-key text-emerald-400"></i>
                    </div>
                </div>
            </div>

            <!-- Login Button -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold py-3 px-4 rounded-lg shadow hover:scale-105 transition duration-300 flex items-center justify-center">
                <i class="fas fa-sign-in-alt mr-2"></i>
                <span>Masuk</span>
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-center text-sm text-gray-500">
                © {{ date('Y') }} Website Retraco Baru. Semua hak dilindungi.
            </p>
        </div>
    </div>

    <script>
        // Animasi untuk tombol login
        const loginBtn = document.querySelector('button[type="submit"]');
        loginBtn.addEventListener('mouseover', function() {
            this.classList.add('transform', 'scale-105');
        });
        
        loginBtn.addEventListener('mouseout', function() {
            this.classList.remove('transform', 'scale-105');
        });
        
        // Validasi form
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if(!username || !password) {
                e.preventDefault();
                
                // Error animation
                const inputs = document.querySelectorAll('input');
                inputs.forEach(input => {
                    if(!input.value) {
                        input.classList.add('border-red-400', 'shake');
                        setTimeout(() => {
                            input.classList.remove('shake');
                        }, 500);
                    }
                });
                
                alert('Harap isi username dan password!');
            }
        });

        // Tambah class shake untuk animasi error
        const style = document.createElement('style');
        style.textContent = `
            .shake {
                animation: shake 0.5s ease-in-out;
            }
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
