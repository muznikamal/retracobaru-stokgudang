<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDevice;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Pastikan role tersedia
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);

        // Daftar permission dasar
        $permissions = [
            // Barang
            'barang.view',
            'barang.create',
            'barang.edit',
            'barang.delete',

            // Laporan
            'laporan.view',
            'laporan.export',

            // User & Device (opsional, kalau kamu punya fitur ini)
            'user.manage',
            'device.approve',
        ];

        // Buat permission jika belum ada
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Beri semua izin ke admin
        $adminRole->syncPermissions(Permission::all());

        // Staff hanya boleh lihat & ekspor laporan
        $staffRole->syncPermissions([
            'barang.view',
            'laporan.view',
            'laporan.export',
        ]);

        // Buat user admin default
        $admin = User::firstOrCreate(
            ['username' => 'admin'], // gunakan username
            [
                'name' => 'Administrator',
                'password' => Hash::make('qwe123'),
            ]
        );

        // Tambahkan role admin
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        // Buat device default agar admin bisa langsung login
        UserDevice::firstOrCreate(
            [
                'user_id' => $admin->id,
                'device_token' => 'default-admin-device-token',
            ],
            [
                'device_name' => 'Admin PC',
                'user_agent' => 'Seeder Default',
                'ip_address' => '127.0.0.1',
                'is_approved' => true,
                'last_login_at' => now(),
            ]
        );

        // Pesan console
        $this->command->info('✅ Admin user dan permission berhasil disetup!');
        $this->command->info('🧍 Username: admin');
        $this->command->info('🔐 Password: qwe123');
    }
}
