<?php

namespace App\Http\Controllers;

use App\Models\User;
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
                ->map(fn (object $session): array => [
                    'current' => $session->id === $request->session()->getId(),
                    'ip_address' => $session->ip_address ?: 'Adresse inconnue',
                    'user_agent' => Str::limit($session->user_agent ?: 'Navigateur inconnu', 110),
                    'last_activity' => Carbon::createFromTimestamp((int) $session->last_activity)->diffForHumans(),
                ])
            : collect();

        $eventLabels = [
            'protected_access_denied_mfa' => 'Accès protégé refusé · MFA absent',
            'protected_workspace_accessed' => 'Zone protégée consultée',
            'other_sessions_revoked' => 'Autres sessions révoquées',
        ];

        $events = DB::table('zero_trust_events')
            ->where('user_id', $user->getKey())
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(function (object $event) use ($eventLabels): object {
                $event->event_label = $eventLabels[$event->event_type] ?? 'Événement de sécurité';

                return $event;
            });

        return view('zero-trust.index', [
            'user' => $user,
            'mfaEnabled' => $user->hasEnabledTwoFactorAuthentication(),
            'passkeyCount' => $user->passkeys()->count(),
            'canManageSessions' => $canManageSessions,
            'sessions' => $sessions,
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

        $revokedCount = DB::connection($sessionConnection)->table($sessionTable)
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
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
