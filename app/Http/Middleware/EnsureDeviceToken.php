<?php

namespace App\Http\Middleware;

use App\Services\DeviceRegistry;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDeviceToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $cookieToken = $request->cookie(DeviceRegistry::COOKIE);
        $token = app(DeviceRegistry::class)->token($request);
        $response = $next($request);
        $responseToken = $request->session()->get('security.device_token', $token);
        $hasExplicitDeviceCookie = collect($response->headers->getCookies())
            ->contains(fn ($cookie): bool => $cookie->getName() === DeviceRegistry::COOKIE);

        if (! $hasExplicitDeviceCookie && (! is_string($cookieToken) || strlen($cookieToken) !== 64)
            && is_string($responseToken) && strlen($responseToken) === 64) {
            $response->headers->setCookie(cookie(
                DeviceRegistry::COOKIE,
                $responseToken,
                60 * 24 * 365,
                '/',
                null,
                $request->isSecure(),
                true,
                false,
                'lax',
            ));
        }

        return $response;
    }
}
