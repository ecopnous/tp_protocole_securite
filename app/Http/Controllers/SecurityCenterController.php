<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BrowserDetails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SecurityCenterController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $sessionTable = (string) config('session.table');
        $sessionConnection = config('session.connection');
        $canManageSessions = config('session.driver') === 'database'
            && Schema::connection($sessionConnection)->hasTable($sessionTable);

        $sessions = $canManageSessions
            ? DB::connection($sessionConnection)->table($sessionTable)
                ->where('user_id', $user->getKey())
                ->orderByDesc('last_activity')
                ->get()
                ->map(function (object $session) use ($request): array {
                    $details = app(BrowserDetails::class)->fromUserAgent($session->user_agent);

                    return [
                        'current' => $session->id === $request->session()->getId(),
                        'ip_address' => $session->ip_address ?: 'Adresse inconnue',
                        'user_agent' => Str::limit($session->user_agent ?: 'Navigateur inconnu', 180),
                        'browser' => $details['browser'],
                        'platform' => $details['platform'],
                        'device' => $details['device'],
                        'last_activity' => Carbon::createFromTimestamp((int) $session->last_activity)->diffForHumans(),
                    ];
                })
            : collect();

        $eventLabels = [
            'protected_access_denied_mfa' => 'Accès protégé refusé · MFA absent',
            'protected_workspace_accessed' => 'Zone protégée consultée',
            'other_sessions_revoked' => 'Autres sessions révoquées',
            'device_trusted' => 'Nouvel appareil enregistré',
            'device_retrusted' => 'Appareil réenregistré après vérification',
            'device_login' => 'Connexion depuis un appareil enregistré',
            'device_revoked' => 'Appareil révoqué',
            'device_reported_not_me' => 'Connexion signalée comme inconnue',
            'device_confirmed_by_email' => 'Nouvel appareil confirmé par email',
        ];

        $events = DB::table('zero_trust_events')
            ->where('user_id', $user->getKey())
            ->latest('created_at')
            ->get()
            ->map(function (object $event) use ($eventLabels): object {
                $event->event_label = $eventLabels[$event->event_type] ?? 'Événement de sécurité';

                return $event;
            })
            ->merge(DB::table('trusted_device_events')
                ->where('user_id', $user->getKey())
                ->latest('created_at')
                ->get()
                ->map(function (object $event) use ($eventLabels): object {
                    $event->event_label = $eventLabels[$event->event_type] ?? 'Événement sur un appareil';
                    $event->description = $event->event_label;

                    return $event;
                }))
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        return view('zero-trust.index', [
            'user' => $user,
            'mfaEnabled' => $user->hasEnabledTwoFactorAuthentication(),
            'passkeyCount' => $user->passkeys()->count(),
            'canManageSessions' => $canManageSessions,
            'sessions' => $sessions,
            'devices' => DB::table('trusted_devices')
                ->where('user_id', $user->getKey())
                ->orderByDesc('last_seen_at')
                ->get(),
            'events' => $events,
        ]);
    }

    public function revokeOtherSessions(Request $request): RedirectResponse
    {
        $sessionTable = (string) config('session.table');
        $sessionConnection = config('session.connection');

        if (config('session.driver') !== 'database' || ! Schema::connection($sessionConnection)->hasTable($sessionTable)) {
            return to_route('zero-trust.index')->with('error', 'La gestion des sessions exige le pilote de session database.');
        }

        $otherSessionIds = DB::connection($sessionConnection)->table($sessionTable)
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->pluck('id');

        $revokedDevices = DB::table('trusted_devices')
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->whereIn('current_session_id', $otherSessionIds)
            ->get();

        foreach ($revokedDevices as $device) {
            DB::table('trusted_devices')->where('id', $device->id)->update([
                'status' => 'revoked',
                'revocation_reason' => 'other_sessions_revoked',
                'updated_at' => now(),
            ]);
            DB::table('trusted_device_events')->insert([
                'user_id' => $request->user()->getAuthIdentifier(),
                'trusted_device_id' => $device->id,
                'event_type' => 'device_revoked',
                'status' => 'revoked',
                'ip_address' => $device->ip_address,
                'user_agent' => $device->user_agent,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $revokedCount = DB::connection($sessionConnection)->table($sessionTable)
            ->whereIn('id', $otherSessionIds)
            ->delete();

        $this->recordEvent(
            $request,
            'other_sessions_revoked',
            $revokedCount.' autre(s) session(s) révoquée(s) depuis le centre de sécurité.',
        );

        return to_route('zero-trust.index')->with('success', $revokedCount > 0
            ? $revokedCount.' autre(s) session(s) ont été révoquées.'
            : 'Aucune autre session active n’a été trouvée.');
    }

    public function workspace(Request $request): View
    {
        $this->recordEvent($request, 'protected_workspace_accessed', 'Zone protégée consultée après validation des contrôles MFA et de confirmation du mot de passe.');

        return view('zero-trust.workspace', [
            'user' => $request->user(),
            'recentEvents' => DB::table('zero_trust_events')
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->latest('created_at')
                ->limit(5)
                ->get(),
        ]);
    }

    private function recordEvent(Request $request, string $eventType, string $description): void
    {
        DB::table('zero_trust_events')->insert([
            'user_id' => $request->user()->getAuthIdentifier(),
            'event_type' => $eventType,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
    }
}
