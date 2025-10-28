@extends('layouts.app')
@section('title', 'Tambah User')

@section('content')
<div class="min-h-full flex items-center justify-center">
    <div class="w-full max-w-lg bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl p-8 border border-emerald-100">
        <h2 class="text-2xl font-bold text-emerald-700 text-center mb-6">Tambah User Baru</h2>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required autocomplete="off"
                    placeholder="Masukkan nama lengkap"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-150 ease-in-out">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Username --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autocomplete="new-username"
                    placeholder="Masukkan username"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-150 ease-in-out">
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required autocomplete="new-password"
                    placeholder="Masukkan password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition duration-150 ease-in-out">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Role --}}
            <div x-data="{
                open: false,
                selectedValue: '{{ old('role') ?? '' }}',
                selectedLabel: '{{ old('role') ? ucfirst(old('role')) : 'Pilih Role' }}',
                options: [
                    @foreach ($roles as $role)
                        { value: '{{ $role->name }}', label: '{{ ucfirst($role->name) }}' },
                    @endforeach
                ]
            }" class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>

                <!-- Tombol utama dropdown -->
                <div @click="open = !open"
                    class="flex justify-between items-center w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white cursor-pointer 
                    focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 hover:border-emerald-400 transition duration-150 ease-in-out">
                    <span x-text="selectedLabel" class="text-gray-700"></span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <!-- Daftar opsi -->
                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                    <template x-for="option in options" :key="option.value">
                        <div @click="selectedValue = option.value; selectedLabel = option.label; open = false"
                            class="px-4 py-2 cursor-pointer hover:bg-emerald-100 hover:text-emerald-600 transition"
                            :class="selectedValue === option.value ? 'bg-emerald-50 text-emerald-700 font-medium' : ''"
                            x-text="option.label"></div>
                    </template>
                </div>

                <!-- Hidden input -->
                <input type="hidden" name="role" :value="selectedValue" required>

                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('users.index') }}"
                    class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-emerald-600 text-white rounded-lg shadow-md hover:bg-emerald-700 active:scale-95 transition-all duration-150 ease-in-out">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
