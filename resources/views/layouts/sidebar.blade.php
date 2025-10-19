<!-- Load Google Material Icons -->


<aside class="w-64 bg-white fixed inset-y-0 left-0 z-50 md:relative md:translate-x-0">
    <div class="p-6 border-b bg-emerald-700">
        <h1 class="text-2xl font-bold text-white">RETRACO BARU</h1>
        <p class="mt-1 text-sm italic text-emerald-200">Sistem Stok Gudang</p>
    </div>
    <nav class="p-4 space-y-6">

        <!-- HOME -->
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Home</p>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
               {{ request()->routeIs('dashboard') 
                    ? 'bg-emerald-500 text-white shadow-md' 
                    : 'text-gray-700 hover:bg-emerald-100' }}">
                <span class="material-icons {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-500' }}">
                    dashboard
                </span>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- MASTER DATA -->
        @hasanyrole('admin|staff')
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Master Data</p>
            <a href="{{ route('barang.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
               {{ request()->routeIs('barang.*') 
                    ? 'bg-emerald-500 text-white shadow-md' 
                    : 'text-gray-700 hover:bg-emerald-100' }}">
                <span class="material-icons {{ request()->routeIs('barang.*') ? 'text-white' : 'text-gray-500' }}">
                    inventory_2
                </span>
                <span>Barang</span>
            </a>
        </div>
        {{-- @endhasanyrole --}}

        <!-- TRANSAKSI -->
        {{-- @hasanyrole('admin|staff') --}}
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Transaksi</p>

            <a href="{{ route('barang-masuk.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
               {{ request()->routeIs('barang-masuk.*') 
                    ? 'bg-emerald-500 text-white shadow-md' 
                    : 'text-gray-700 hover:bg-emerald-100' }}">
                <span class="material-icons {{ request()->routeIs('barang-masuk.*') ? 'text-white' : 'text-gray-500' }}">
                    login
                </span>
                <span>Barang Masuk</span>
            </a>

            <a href="{{ route('barang-keluar.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
               {{ request()->routeIs('barang-keluar.*') 
                    ? 'bg-emerald-500 text-white shadow-md' 
                    : 'text-gray-700 hover:bg-emerald-100' }}">
                <span class="material-icons {{ request()->routeIs('barang-keluar.*') ? 'text-white' : 'text-gray-500' }}">
                    logout
                </span>
                <span>Barang Keluar</span>
            </a>
        </div>
        @endhasanyrole

        <!-- LAPORAN -->
        @hasanyrole('admin|staff')
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Laporan</p>
            <a href="{{ route('laporan.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
               {{ request()->routeIs('laporan.*') 
                    ? 'bg-emerald-500 text-white shadow-md' 
                    : 'text-gray-700 hover:bg-emerald-100' }}">
                <span class="material-icons {{ request()->routeIs('laporan.*') ? 'text-white' : 'text-gray-500' }}">
                    description
                </span>
                <span>Laporan</span>
            </a>
        </div>
        @endhasanyrole

        <!-- ADMIN ONLY -->
        @role('admin')
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Admin</p>

            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
               {{ request()->routeIs('users.*') 
                    ? 'bg-emerald-500 text-white shadow-md' 
                    : 'text-gray-700 hover:bg-emerald-100' }}">
                <span class="material-icons {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-500' }}">
                    manage_accounts
                </span>
                <span>Manajemen User</span>
            </a>

            <a href="{{ route('devices.index') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg transition 
               {{ request()->routeIs('devices.*') 
                    ? 'bg-emerald-500 text-white shadow-md' 
                    : 'text-gray-700 hover:bg-emerald-100' }}">
                <span class="material-icons {{ request()->routeIs('devices.*') ? 'text-white' : 'text-gray-500' }}">
                    devices
                </span>
                <span>Device</span>
            </a>
        </div>
        @endrole

    </nav>
</aside>
