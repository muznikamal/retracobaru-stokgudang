@extends('layouts.app')
@section('title', 'Manajemen Device')
@section('content')
<div class="max-w-7xl mx-auto bg-white shadow-lg rounded-2xl p-6">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-500">devices</span>
                Manajemen Device
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola perangkat yang terdaftar untuk setiap user</p>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="overflow-x-auto border border-gray-200 rounded-xl">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-emerald-50 text-emerald-700 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-center font-semibold">User</th>
                    <th class="px-4 py-3 text-center font-semibold">Device Name</th>
                    <th class="px-4 py-3 text-center font-semibold">IP Address</th>
                    <th class="px-4 py-3 text-left font-semibold">Last Login</th>
                    <th class="px-4 py-3 text-center font-semibold">Status</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devices as $device)
                    <tr class="border-t hover:bg-gray-50 transition">
                        {{-- USER --}}
                        <td class="px-4 py-3 font-medium text-gray-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500 text-base">person</span>
                            {{ $device->user->name }}
                        </td>

                        {{-- DEVICE NAME --}}
                        <td class="px-4 py-3 ">{{ $device->device_name ?? '-' }}</td>

                        {{-- IP --}}
                        <td class="px-4 py-3 text-center text-gray-600">{{ $device->ip_address ?? '-' }}</td>

                        {{-- LAST LOGIN --}}
                        <td class="px-4 py-3 text-center text-gray-500">
                            {{ $device->last_login_at ? $device->last_login_at->diffForHumans() : '-' }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3 text-center">
                            @if($device->is_approved)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                    <span class="material-symbols-outlined text-xs">check_circle</span> Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">
                                    <span class="material-symbols-outlined text-xs">cancel</span> Belum
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                @if(!$device->is_approved)
                                    {{-- SETUJUI --}}
                                    <form action="{{ route('devices.approve', $device->id) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" title="Setujui Device"
                                            class="p-2 bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition">
                                            <span class="material-symbols-outlined text-base">check</span>
                                        </button>
                                    </form>
                                    {{-- TOLAK --}}
                                    <form action="{{ route('devices.reject', $device->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Tolak Device"
                                            class="p-2 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition">
                                            <span class="material-symbols-outlined text-base">close</span>
                                        </button>
                                    </form>
                                @else
                                    {{-- HAPUS --}}
                                    <form action="{{ route('devices.reject', $device->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus Device"
                                            class="p-2 bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            <span class="material-symbols-outlined align-middle text-gray-400 text-xl">info</span>
                            Tidak ada device yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-6 flex justify-center">
        {{ $devices->links() }}
    </div>
</div>
@endsection
