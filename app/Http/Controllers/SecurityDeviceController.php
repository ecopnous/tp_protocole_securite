<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BrowserDetails;
use App\Services\DeviceRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SecurityDeviceController extends Controller
{
    public function pending(): View
    {
        return view('auth.device-verification-pending');
    }

    public function confirmation(Request $request, int $challenge, string $token): View|RedirectResponse
    {
        $record = app(DeviceRegistry::class)->resolveChallenge($challenge, $token);

        if (! $record) {
            return to_route('login')->withErrors(['email' => 'Ce lien de sécurité est invalide ou expiré.']);
        }

        return view('auth.device-confirmation', [
            'challenge' => $challenge,
            'token' => $token,
            'intent' => $request->routeIs('security.devices.deny') ? 'deny' : 'confirm',
            'confirmUrl' => URL::route('security.devices.confirm', ['challenge' => $challenge, 'token' => $token], false),
            'denyUrl' => URL::route('security.devices.deny', ['challenge' => $challenge, 'token' => $token], false),
        ]);
    }

    public function confirm(Request $request, int $challenge, string $token): Response
    {
        $record = app(DeviceRegistry::class)->resolveChallenge($challenge, $token);

        if (! $record) {
            return to_route('login')->withErrors(['email' => 'Ce lien de sécurité est invalide ou expiré.']);
        }

        $deviceToken = Crypt::decryptString($record->device_token);
        /** @var User $user */
        $user = User::query()->findOrFail($record->user_id);
        $request->session()->put('security.device_token', $deviceToken);
        DB::table('device_login_challenges')->where('id', $record->id)->update([
            'status' => 'confirmed',
            'resolved_at' => now(),
            'updated_at' => now(),
        ]);

        if ($record->trusted_device_id) {
            DB::table('trusted_devices')->where('id', $record->trusted_device_id)->update([
                'status' => 'trusted',
                'revocation_reason' => null,
                'last_seen_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('trusted_device_events')->insert([
                'user_id' => $record->user_id,
                'trusted_device_id' => $record->trusted_device_id,
                'event_type' => 'device_confirmed_by_email',
                'status' => 'trusted',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $details = app(BrowserDetails::class)->fromUserAgent($record->user_agent);
            DB::table('trusted_devices')->updateOrInsert(
                ['user_id' => $record->user_id, 'token_hash' => $record->device_token_hash],
                [
                    ...$details,
                    'user_agent' => $record->user_agent,
                    'ip_address' => $record->ip_address,
                    'status' => 'trusted',
                    'current_session_id' => $request->session()->getId(),
                    'last_seen_at' => now(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ],
            );
            $device = DB::table('trusted_devices')->where('token_hash', $record->device_token_hash)->first();
            DB::table('trusted_device_events')->insert([
                'user_id' => $record->user_id,
                'trusted_device_id' => $device?->id,
                'event_type' => 'device_trusted',
                'status' => 'trusted',
                'ip_address' => $record->ip_address,
                'user_agent' => $record->user_agent,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            Auth::guard('web')->login($user);
            $request->session()->regenerate();
        }

        return redirect()->intended(route('dashboard'))->withCookie(cookie(
            DeviceRegistry::COOKIE,
            $deviceToken,
            60 * 24 * 365,
            '/',
            null,
            $request->isSecure(),
            true,
            false,
            'lax',
        ));
    }

    public function deny(Request $request, int $challenge, string $token): RedirectResponse
    {
        $record = app(DeviceRegistry::class)->resolveChallenge($challenge, $token);

        if (! $record) {
            return to_route('login')->withErrors(['email' => 'Ce lien de sécurité est invalide ou expiré.']);
        }

        $sameBrowser = is_string($request->cookie(DeviceRegistry::COOKIE))
            && hash_equals($record->device_token_hash, hash('sha256', $request->cookie(DeviceRegistry::COOKIE)));
        $sameSession = $request->session()->getId() === $record->session_id;

        DB::transaction(function () use ($record): void {
            DB::table('device_login_challenges')->where('id', $record->id)->update([
                'status' => 'denied',
                'resolved_at' => now(),
                'updated_at' => now(),
            ]);

            if ($record->trusted_device_id) {
                DB::table('trusted_devices')->where('id', $record->trusted_device_id)->update([
                    'status' => 'revoked',
                    'revocation_reason' => 'reported_not_me',
                    'updated_at' => now(),
                ]);
            } else {
                $details = app(BrowserDetails::class)->fromUserAgent($record->user_agent);
                DB::table('trusted_devices')->updateOrInsert(
                    ['user_id' => $record->user_id, 'token_hash' => $record->device_token_hash],
                    [
                        ...$details,
                        'user_agent' => $record->user_agent,
                        'ip_address' => $record->ip_address,
                        'status' => 'revoked',
                        'revocation_reason' => 'reported_not_me',
                        'last_seen_at' => now(),
                        'updated_at' => now(),
                        'created_at' => now(),
                    ],
                );
            }

            $device = DB::table('trusted_devices')->where('token_hash', $record->device_token_hash)->first();
            DB::table('trusted_device_events')->insert([
                'user_id' => $record->user_id,
                'trusted_device_id' => $device?->id,
                'event_type' => 'device_reported_not_me',
                'status' => 'revoked',
                'ip_address' => $record->ip_address,
                'user_agent' => $record->user_agent,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $sessionTable = (string) config('session.table');
            $sessionConnection = config('session.connection');

            if (config('session.driver') === 'database' && DB::connection($sessionConnection)->getSchemaBuilder()->hasTable($sessionTable)) {
                DB::connection($sessionConnection)->table($sessionTable)
                    ->where('id', $record->session_id)
                    ->delete();
            }
        });

        if ($sameSession) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
        }

        $response = to_route('login')->with('status', 'Cet appareil a été révoqué et oublié.');

        return $sameBrowser ? $response->withCookie(cookie()->forget(DeviceRegistry::COOKIE)) : $response;
    }
}
