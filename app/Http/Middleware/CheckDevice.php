<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserDevice;

class CheckDevice
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Jika admin → skip pemeriksaan
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        $userAgent = $request->userAgent();
        // $ip = $request->ip();
        // $deviceToken = hash('sha256', $userAgent . $ip);
        $deviceToken = $request->cookie('device_token');

        if (!$deviceToken) {
            $deviceToken = hash('sha256', $request->userAgent() . \Illuminate\Support\Str::uuid());
        }


        // Cek atau buat device baru
        $device = UserDevice::firstOrCreate(
            [
                'user_id' => $user->id,
                'device_token' => $deviceToken,
            ],
            [
                'device_name'   => substr($userAgent, 0, 50),
                'user_agent'    => $userAgent,
                'ip_address'    => $request->ip(),
                'is_approved'   => false,
                'last_login_at' => now(),
            ]
        );


        // Jika belum disetujui
        if (!$device->is_approved) {
            auth()->logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors(['access' => 'Device ini belum disetujui oleh admin.'])
                ->withCookie(cookie()->forever('device_token', $deviceToken));
        }

        // Update status login terakhir
        $device->update([
            'last_login_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        return $next($request)
            ->withCookie(cookie()->forever('device_token', $deviceToken));
    }
}
