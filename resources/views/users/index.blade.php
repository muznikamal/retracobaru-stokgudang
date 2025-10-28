@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-2xl p-6 transition-all">
        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-500">manage_accounts</span>
                    Manajemen User
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola akun pengguna dan perangkat yang terdaftar</p>
            </div>

            <a href="{{ route('users.create') }}"
                class="flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 shadow-sm transition">
                <span class="material-symbols-outlined text-sm">add</span>
                Tambah User
            </a>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto border rounded-xl">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-emerald-50 text-emerald-700 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-center font-semibold">Nama</th>
                        <th class="px-4 py-3 text-center font-semibold">Username</th>
                        <th class="px-4 py-3 text-center font-semibold">Role</th>
                        <th class="px-4 py-3 text-center font-semibold">Device</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->username }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($user->hasRole('admin'))
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                        <span class="material-symbols-outlined text-xs">shield_person</span> Admin
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                        <span class="material-symbols-outlined text-xs">person</span>
                                        Staff
                                        @if (
                                            $user->hasPermissionTo('barang.create') ||
                                                $user->hasPermissionTo('barang.edit') ||
                                                $user->hasPermissionTo('barang.delete'))
                                            <span class="w-2.5 h-2.5 bg-green-500 rounded-full inline-block"></span>
                                        @else
                                            <span class="w-2.5 h-2.5 bg-red-500 rounded-full inline-block"></span>
                                        @endif
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 space-y-1">
                                @forelse ($user->devices as $d)
                                    <div class="text-xs flex items-center gap-1 text-gray-600">
                                        <span class="material-symbols-outlined text-sm text-gray-500">devices</span>
                                        {{ $d->device_name }}
                                        <span class="material-symbols-outlined text-green-500">
                                            {{ $d->is_approved ? 'check_circle' : 'cancel' }}
                                        </span>
                                    </div>
                                @empty
                                    <span class="text-gray-400 text-xs italic">Belum ada device</span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <a href="{{ route('users.edit', $user) }}"
                                    class="p-1.5 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition inline-flex items-center gap-1 font-medium">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Yakin ingin menghapus user ini?')"
                                        class="p-1.5 bg-red-500 text-white rounded-md hover:bg-red-600 transition inline-flex items-center gap-1 font-medium">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-6 flex justify-center">
            {{ $users->links() }}
        </div>
    </div>
@endsection
