<?php

namespace App\Service;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class WeblogService
{
    public function set($keterangan = '', $user_id='')
    {
        $agent = new Agent();
        $browser = $agent->browser();
        $browser_version = $agent->version($browser);
        $platform = $agent->platform();
        $platform_version = $agent->version($platform);
        $device = $agent->device();
        $device_version = $agent->version($device);

        Log::create([
            'user_id' => Auth::id(),
            'keterangan' => $keterangan,
            'ip' => request()->ip(),
            'url' => url()->current(),
            'browser' => $browser,
            'browser_version' => $browser_version,
            'platform' => $platform,
            'platform_version' => $platform_version,
            'device' => $device,
            'device_tipe' => $device_version,
        ]);
    }
}
