<?php

namespace App\Auth;

use App\Models\User;
use App\Services\DeviceRegistry;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $wasKnown = app(DeviceRegistry::class)->isKnown($request, $user);
        app(DeviceRegistry::class)->trust($request, $user);

        if (! $wasKnown) {
            $device = DB::table('trusted_devices')
                ->where('user_id', $user->getKey())
                ->where('token_hash', hash('sha256', $request->session()->get('security.device_token')))
                ->first();

            if ($device) {
                app(DeviceRegistry::class)->createDeviceAlert($request, $user, $device);
            }
        }

        return $request->wantsJson()
            ? response()->noContent()
            : redirect()->intended(Fortify::redirects('login'));
    }
}
