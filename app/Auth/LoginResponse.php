<?php

namespace App\Auth;

use App\Models\User;
use App\Services\DeviceRegistry;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $registry = app(DeviceRegistry::class);

        if (! $registry->isKnown($request, $user)) {
            $registry->createEmailVerification($request, $user);
            Auth::guard('web')->logout();

            return $request->wantsJson()
                ? response()->json(['device_verification_required' => true], 202)
                : redirect()->route('security.devices.pending');
        }

        $registry->trust($request, $user);

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(Fortify::redirects('login'));
    }
}
