<?php

namespace App\Auth;

use App\Models\User;
use App\Services\DeviceRegistry;
use Illuminate\Support\Facades\DB;
use Laravel\Passkeys\Contracts\PasskeyLoginResponse as PasskeyLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class PasskeyLoginResponse implements PasskeyLoginResponseContract
{
    public function toResponse($request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $registry = app(DeviceRegistry::class);
        $wasKnown = $registry->isKnown($request, $user);
        $registry->trust($request, $user);

        if (! $wasKnown) {
            $device = DB::table('trusted_devices')
                ->where('user_id', $user->getKey())
                ->where('token_hash', hash('sha256', $request->session()->get('security.device_token')))
                ->first();

            if ($device) {
                $registry->createDeviceAlert($request, $user, $device);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'redirect' => redirect()->intended(config('passkeys.redirect', '/dashboard'))->getTargetUrl(),
            ]);
        }

        return redirect()->intended(config('passkeys.redirect', '/dashboard'));
    }
}
