@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Edit User</h2>

            <form action="{{ route('users.update', $user->id) }}" method="POST" x-data="{
                open: false,
                selectedValue: '{{ $user->roles->first()->name ?? '' }}',
                selectedLabel: '{{ ucfirst($user->roles->first()->name ?? 'Pilih Role') }}',
                options: [
                    @foreach ($roles as $role)
                { value: '{{ $role->name }}', label: '{{ ucfirst($role->name) }}' }, @endforeach
                ]
            }" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                {{-- USERNAME --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password (opsional)</label>
                    <input type="password" name="password"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <small class="text-gray-500">Kosongkan jika tidak ingin mengganti password</small>
                </div>

                {{-- ROLE DROPDOWN --}}
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>

                    <div @click="open = !open"
                        class="flex justify-between items-center w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white cursor-pointer 
                   focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 hover:border-emerald-400 transition duration-150 ease-in-out">
                        <span x-text="selectedLabel" class="text-gray-700"></span>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                        <template x-for="option in options" :key="option.value">
                            <div @click="selectedValue = option.value; selectedLabel = option.label; open = false"
                                class="px-4 py-2 cursor-pointer hover:bg-emerald-100 hover:text-emerald-600 transition"
                                :class="selectedValue === option.value ? 'bg-emerald-50 text-emerald-700 font-medium' : ''"
                                x-text="option.label"></div>
                        </template>
                    </div>

                    <input type="hidden" name="role" :value="selectedValue">
                </div>

                {{-- BAGIAN CRUD --}}
                <template x-if="selectedValue === 'staff'">
                    <div class="mt-6 border-t pt-4">
                        <h3 class="text-gray-800 font-semibold mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-600 text-sm">lock_open_right</span>
                            Hak Akses Modul Barang
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            @foreach ([
            'barang.create' => 'Tambah Barang',
            'barang.edit' => 'Edit Barang',
            'barang.delete' => 'Hapus Barang',
        ] as $perm => $label)
                                <label
                                    class="flex items-center gap-2 bg-gray-50 border p-2 rounded-lg hover:bg-gray-100 transition">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                                        {{ $user->hasPermissionTo($perm) ? 'checked' : '' }}
                                        class="rounded text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </template>

                <template x-if="selectedValue === 'admin'">
                    <div class="mt-6 border-t pt-4">
                        <h3 class="text-gray-800 font-semibold mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600 text-sm">verified_user</span>
                            Role Admin — Akses penuh terhadap semua modul
                        </h3>
                        <p class="text-sm text-gray-600">
                            Pengguna dengan role <strong>Admin</strong> memiliki semua hak akses secara otomatis.
                        </p>
                    </div>
                </template>

                {{-- BUTTON --}}
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow hover:scale-105 transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
