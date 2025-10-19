<?php

namespace App\Http\Controllers;

use App\Models\UserDevice;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // Admin: Index semua device
    public function index()
    {
        $devices = UserDevice::with('user')->latest()->paginate(10);
        return view('devices.index', compact('devices'));
    }

    // Admin: Show form create device
    public function create()
    {
        return view('devices.create');
    }

    // Admin: Store device
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_name' => 'required|string|max:255',
        ]);

        UserDevice::create([
            'user_id' => $request->user_id,
            'device_name' => $request->device_name,
            'device_token' => uniqid(), // token unik untuk device
            'is_approved' => true,       // langsung approved
        ]);

        return redirect()->route('devices.index')->with('success','Device berhasil dibuat');
    }

    // Admin: Edit device
    public function edit(UserDevice $device)
    {
        return view('devices.edit', compact('device'));
    }

    // Admin: Update device
    public function update(Request $request, UserDevice $device)
    {
        $request->validate([
            'device_name' => 'required|string|max:255',
        ]);

        $device->update($request->only('device_name'));
        return redirect()->route('devices.index')->with('success','Device berhasil diperbarui');
    }

    // Admin: Delete device
    public function destroy(UserDevice $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success','Device berhasil dihapus');
    }

    // Admin: Approve device staff
    public function approve(UserDevice $device)
    {
        $device->update(['is_approved' => true]);
        return redirect()->route('devices.index')->with('success','Device disetujui');
    }

    // Admin: Reject / hapus device staff
    public function reject(UserDevice $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success','Device ditolak dan dihapus');
    }

    // Staff: Lihat device sendiri (optional)
    public function myDevice()
    {
        $device = auth()->user()->devices()->latest()->first();
        return view('devices.my', compact('device'));
    }
}
