<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDevice;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Pastikan role sudah tersedia
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'staff']);

        // Buat user admin default
        $admin = User::firstOrCreate(
            ['username' => 'admin'], // pakai username, bukan email
            [
                'name' => 'Administrator',
                'password' => Hash::make('qwe123'),
            ]
        );

        // Tambahkan role admin jika belum ada
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Buat device default agar admin bisa login langsung tanpa menunggu approval
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

        // Tampilkan pesan di console agar jelas
        $this->command->info('✅ Admin user berhasil dibuat!');
        $this->command->info('🧍 Username: admin');
        $this->command->info('🔐 Password: qwe123');
    }
}
