<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/LOGO.png') }}" type="image/png">
    <title>@yield('title') - Retraco Baru</title>

    @vite('resources/css/app.css')

    {{-- Google Icons --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <script src="{{ asset('js/alpine.min.js') }}" defer></script>


    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col md:flex-row">


        <!-- Mobile Sidebar -->
        <div class="md:hidden">
            <aside
                class="fixed inset-y-0 left-0 w-64 bg-white 
                  z-40 transform -translate-x-full 
                  transition-transform duration-300 ease-in-out 
                  border-r border-gray-200"
                id="mobileSidebar">
                @include('layouts.sidebar')
            </aside>
        </div>

        <!-- Desktop Sidebar -->
        <aside
            class="hidden md:block fixed inset-y-0 left-0 w-64 bg-white 
              shadow-lg shadow-gray-300 z-30 
              border-r border-gray-200"
            id="desktopSidebar">
            @include('layouts.sidebar')
        </aside>

        <!-- Overlay (background hitam saat sidebar terbuka di mobile) -->
        <div id="sidebarOverlay"
            class="fixed inset-0 bg-black bg-opacity-40 z-30 hidden md:hidden transition-opacity duration-300"></div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-h-screen md:ml-0">

            <!-- Navbar -->
            <header class="bg-white shadow sticky top-0 z-20">
                <div class="flex justify-between items-center px-4 sm:px-6 py-3 sm:py-4">
                    <!-- Toggle Button (Mobile Only) -->
                    <button id="sidebarToggle"
                        class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none transition">
                        <span class="material-icons text-2xl">menu</span>
                    </button>
                    <h2 class="text-lg sm:text-xl font-semibold text-center text-gray-700 truncate max-w-[60%] sm:max-w-none">
                        @yield('title')
                    </h2>
                    <!-- User Menu -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <span class="hidden sm:inline text-gray-600 text-sm truncate max-w-[100px]">
                            {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="px-3 py-1.5 sm:py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <!-- Main Content Area -->
            <div class="flex-1 md:ml-64 min-h-screen">


                <!-- Main Content -->
                <main class="flex-1 p-4 sm:p-6 overflow-x-hidden">
                    @if (session('success'))
                        <div
                            class="mb-4 sm:mb-6 flex items-center p-3 sm:p-4 text-green-800 bg-green-100 border border-green-200 rounded-lg sm:rounded-xl shadow-sm text-sm sm:text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Sidebar Toggle Script -->
    <script>
        const sidebar = document.getElementById('mobileSidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');

        toggleBtn?.addEventListener('click', () => {
            const isOpen = sidebar.classList.contains('translate-x-0');
            if (isOpen) {
                sidebar.classList.replace('translate-x-0', '-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.replace('-translate-x-full', 'translate-x-0');
                overlay.classList.remove('hidden');
            }
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.replace('translate-x-0', '-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>

    @stack('scripts')

</body>

</html>
