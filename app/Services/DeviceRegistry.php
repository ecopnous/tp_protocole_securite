<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class DeviceRegistry
{
    public const COOKIE = 'trusted_device';

    public function token(Request $request): string
    {
        $token = $request->cookie(self::COOKIE);

        if (! is_string($token) || strlen($token) !== 64) {
            $token = $request->session()->get('security.device_token');
        }

        if (! is_string($token) || strlen($token) !== 64) {
            $token = Str::random(64);
        }

        $request->session()->put('security.device_token', $token);

        return $token;
    }

    public function isKnown(Request $request, User $user): bool
    {
        $token = $request->session()->get('security.device_token');

        return is_string($token) && DB::table('trusted_devices')
            ->where('user_id', $user->getKey())
            ->where('token_hash', hash('sha256', $token))
            ->where('status', 'trusted')
            ->exists();
    }

    public function trust(Request $request, User $user): bool
    {
        $token = $this->token($request);
        $tokenHash = hash('sha256', $token);
        $existing = DB::table('trusted_devices')
            ->where('user_id', $user->getKey())
            ->where('token_hash', $tokenHash)
            ->first();

        if ($existing && $existing->status === 'trusted') {
            $details = app(BrowserDetails::class)->fromUserAgent($request->userAgent());
            DB::table('trusted_devices')->where('id', $existing->id)->update([
                ...$details,
                'user_agent' => $request->userAgent(),
                'last_seen_at' => now(),
                'current_session_id' => $request->session()->getId(),
                'ip_address' => $request->ip(),
                'updated_at' => now(),
            ]);
            $this->recordEvent($user, (int) $existing->id, 'device_login', 'trusted', $request->ip(), $request->userAgent());

            return false;
        }

        $details = app(BrowserDetails::class)->fromUserAgent($request->userAgent());
        DB::table('trusted_devices')->updateOrInsert(
            ['user_id' => $user->getKey(), 'token_hash' => $tokenHash],
            [
                ...$details,
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'status' => 'trusted',
                'revocation_reason' => null,
                'current_session_id' => $request->session()->getId(),
                'last_seen_at' => now(),
                'updated_at' => now(),
                'created_at' => $existing->created_at ?? now(),
            ],
        );
        $device = DB::table('trusted_devices')->where('token_hash', $tokenHash)->first();

        if ($device) {
            $this->recordEvent(
                $user,
                (int) $device->id,
                $existing ? 'device_retrusted' : 'device_trusted',
                'trusted',
                $request->ip(),
                $request->userAgent(),
            );
        }

        return true;
    }

    public function createEmailVerification(Request $request, User $user): string
    {
        return $this->createChallenge($request, $user, null);
    }

    public function createDeviceAlert(Request $request, User $user, object $device): void
    {
        $token = Str::random(64);
        $challengeId = DB::table('device_login_challenges')->insertGetId([
            'user_id' => $user->getKey(),
            'trusted_device_id' => $device->id,
            'token_hash' => hash('sha256', $token),
            'device_token_hash' => $device->token_hash,
            'device_token' => Crypt::encryptString($request->session()->get('security.device_token')),
            'session_id' => $device->current_session_id,
            'ip_address' => $request->ip(),
            'user_agent' => $device->user_agent,
            'status' => 'pending',
            'expires_at' => now()->addHours(24),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->sendMail($user, $device, [
            'confirmUrl' => $this->emailActionUrl($request, 'security.devices.confirm', ['challenge' => $challengeId, 'token' => $token]),
            'denyUrl' => $this->emailActionUrl($request, 'security.devices.deny', ['challenge' => $challengeId, 'token' => $token]),
            'title' => 'Nouvelle connexion à votre compte',
        ]);
    }

    public function resolveChallenge(int $id, string $token): ?object
    {
        $challenge = DB::table('device_login_challenges')->where('id', $id)->first();

        if (! $challenge || $challenge->status !== 'pending' || now()->greaterThan($challenge->expires_at)
            || ! hash_equals($challenge->token_hash, hash('sha256', $token))) {
            return null;
        }

        return $challenge;
    }

    private function createChallenge(Request $request, User $user, ?int $deviceId): string
    {
        $token = Str::random(64);
        $id = DB::table('device_login_challenges')->insertGetId([
            'user_id' => $user->getKey(),
            'trusted_device_id' => $deviceId,
            'token_hash' => hash('sha256', $token),
            'device_token_hash' => hash('sha256', $this->token($request)),
            'device_token' => Crypt::encryptString($request->session()->get('security.device_token')),
            'session_id' => $request->session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'pending',
            'expires_at' => now()->addMinutes(20),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $agent = app(BrowserDetails::class)->fromUserAgent($request->userAgent());
        $this->sendMail($user, $agent + ['ip_address' => $request->ip()], [
            'confirmUrl' => $this->emailActionUrl($request, 'security.devices.confirm', ['challenge' => $id, 'token' => $token]),
            'denyUrl' => $this->emailActionUrl($request, 'security.devices.deny', ['challenge' => $id, 'token' => $token]),
            'title' => 'Vérifiez ce nouvel appareil',
        ]);

        return (string) $id;
    }

    private function recordEvent(User $user, int $deviceId, string $eventType, string $status, ?string $ip, ?string $userAgent): void
    {
        DB::table('trusted_device_events')->insert([
            'user_id' => $user->getKey(),
            'trusted_device_id' => $deviceId,
            'event_type' => $eventType,
            'status' => $status,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function sendMail(User $user, object|array $device, array $links): void
    {
        $device = (object) $device;
        $details = [
            'browser' => $device->browser ?? 'Navigateur non identifié',
            'platform' => $device->platform ?? 'Système non identifié',
            'device' => $device->device ?? 'Appareil non identifié',
            'ip_address' => $device->ip_address ?? 'Adresse inconnue',
            'title' => $links['title'],
            'confirmUrl' => $links['confirmUrl'],
            'denyUrl' => $links['denyUrl'],
        ];

        Mail::send('emails.device-security', $details, function ($message) use ($user, $details): void {
            $message->to($user->email)->subject($details['title']);
        });
    }

    /** @param array<string, int|string> $parameters */
    public function emailActionUrl(Request $request, string $route, array $parameters): string
    {
        $relativeUrl = URL::route($route, $parameters, false);

        return rtrim($request->root(), '/').$relativeUrl;
    }
}
