<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('devices')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all(); // ambil semua role
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,staff',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('users.index')->with('success','User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,staff', // batasi hanya 2 role
        ]);

        $data = $request->only('name', 'username');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Update data user
        $user->update($data);

        // Update role
        $user->syncRoles([$request->role]);

        // Logika permission berdasarkan role
        if ($request->role === 'staff') {
            // Staff hanya dapat permission yang dicentang
            $user->syncPermissions($request->permissions ?? []);
        } else {
            // Admin otomatis dapat semua permission
            $user->syncPermissions([]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success','User berhasil dihapus');
    }
}
