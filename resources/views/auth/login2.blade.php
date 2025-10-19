@extends('layouts.guest')

@section('content')
    <!-- Background Elements dengan tema bangunan hijau -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <!-- Tools floating around -->
        <div class="floating-element absolute top-10 left-10 text-green-200">
            <i class="fas fa-hammer text-3xl" style="animation-delay: 0s;"></i>
        </div>
        <div class="floating-element absolute top-20 right-20 text-green-200">
            <i class="fas fa-toolbox text-2xl" style="animation-delay: 1s;"></i>
        </div>
        <div class="floating-element absolute bottom-40 left-20 text-green-200">
            <i class="fas fa-ruler-combined text-3xl" style="animation-delay: 2s;"></i>
        </div>
        <div class="floating-element absolute bottom-20 right-32 text-green-200">
            <i class="fas fa-wrench text-2xl" style="animation-delay: 3s;"></i>
        </div>
        <div class="floating-element absolute top-1/3 left-1/4 text-green-200">
            <i class="fas fa-paint-roller text-2xl" style="animation-delay: 1.5s;"></i>
        </div>

        <!-- Natural elements untuk tema hijau -->
        <div class="floating-element absolute bottom-10 left-10 text-green-200">
            <i class="fas fa-leaf text-3xl" style="animation-delay: 2.5s;"></i>
        </div>
        <div class="floating-element absolute top-10 right-1/3 text-green-200">
            <i class="fas fa-tree text-2xl" style="animation-delay: 0.8s;"></i>
        </div>

        <!-- Bricks pattern hijau -->
        <div class="absolute bottom-0 left-0 w-full opacity-10">
            <div class="flex flex-wrap">
                <div class="w-16 h-8 bg-green-300 m-1 rounded"></div>
                <div class="w-16 h-8 bg-green-400 m-1 rounded"></div>
                <div class="w-16 h-8 bg-green-300 m-1 rounded"></div>
                <div class="w-16 h-8 bg-green-400 m-1 rounded"></div>
                <div class="w-16 h-8 bg-green-300 m-1 rounded"></div>
                <div class="w-16 h-8 bg-green-400 m-1 rounded"></div>
            </div>
        </div>
    </div>
    <!-- Login Card -->
    <div class="login-card rounded-2xl p-8 w-full max-w-md relative z-10 border-l-4 ">
        <!-- Header dengan logo toko bangunan -->
        <div class="text-center mb-8">
            <div class="construction-icon w-20 h-20 mx-auto mb-4 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-hard-hat text-3xl text-white"></i>
            </div>
        </div>
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="username" class="block text-sm font-semibold text-gray-700 mb-2"> <i
                        class="fas fa-user-hard-hat text-green-500 mr-2"></i>Username</label>
                <div class="relative">
                    <input type="text" name="username" id="username" placeholder="Masukkan username"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition input-construction text-gray-800 placeholder-gray-500 pl-10"
                        required autofocus>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-green-500"></i>
                    </div>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2"><i
                        class="fas fa-lock text-green-500 mr-2"></i>Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="Masukkan password"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition input-construction text-gray-800 placeholder-gray-500 pl-10"
                        required>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-key text-green-500"></i>
                    </div>
                </div>
            </div>
            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" type="checkbox"
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>
            </div>
            <button type="submit"
                class="w-full btn-construction text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                <i class="fas fa-sign-in-alt mr-2"></i>Masuk
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            © {{ date('Y') }} WebStockBarang. Semua hak dilindungi.
        </p>
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

        // Animasi untuk quick access buttons
        const quickAccessBtns = document.querySelectorAll('.quick-access-btn');
        quickAccessBtns.forEach(btn => {
            btn.addEventListener('mouseover', function() {
                this.classList.add('transform', 'scale-105');
            });

            btn.addEventListener('mouseout', function() {
                this.classList.remove('transform', 'scale-105');
            });
        });

        // Validasi form
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (username && password) {
                // Simulasi loading
                const button = document.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-tools animate-spin mr-2"></i> Memproses...';
                button.disabled = true;

                // Tambah efek loading pada card
                const card = document.querySelector('.login-card');
                card.classList.add('opacity-90');

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    card.classList.remove('opacity-90');

                    // Success animation
                    button.innerHTML = '<i class="fas fa-check mr-2"></i> Berhasil Masuk!';
                    button.classList.remove('btn-construction');
                    button.classList.add('bg-green-500', 'text-white');

                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.classList.remove('bg-green-500', 'text-white');
                        button.classList.add('btn-construction');
                        alert('Login berhasil! Mengarahkan ke dashboard...');
                    }, 2000);

                }, 1500);
            } else {
                // Error animation
                const inputs = document.querySelectorAll('input');
                inputs.forEach(input => {
                    if (!input.value) {
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
            
            .construction-icon {
                position: relative;
                overflow: hidden;
            }
            
            .construction-icon::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
                transform: rotate(45deg);
                animation: shine 3s infinite;
            }
            
            @keyframes shine {
                0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
                100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
