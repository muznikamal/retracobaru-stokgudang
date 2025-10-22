<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CheckDevice
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin dilewatkan
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        $userAgent = $request->userAgent();
        $deviceToken = $request->cookie('device_token');

        // Jika belum ada cookie device, buat baru
        if (!$deviceToken) {
            $deviceToken = hash('sha256', $userAgent . Str::uuid());
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

            return redirect()
                ->route('login')
                ->withErrors(['access' => 'Device ini belum disetujui oleh admin.'])
                ->withCookie(cookie()->forever('device_token', $deviceToken));
        }

        // Update aktivitas device
        $device->update([
            'last_login_at' => now(),
            'ip_address'    => $request->ip(),
        ]);

        $response = $next($request);

        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        return $response->withCookie(cookie()->forever('device_token', $deviceToken));
    }
}
