<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureMfaConfigured
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->hasEnabledTwoFactorAuthentication()) {
            DB::table('zero_trust_events')->insert([
                'user_id' => $user?->getAuthIdentifier(),
                'event_type' => 'protected_access_denied_mfa',
                'description' => 'Accès à la zone protégée refusé : le MFA n’est pas activé.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            return to_route('zero-trust.index')->with('error', 'Activez et confirmez le MFA avant d’ouvrir la zone protégée.');
        }

        return $next($request);
    }
}
