<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

test('guests must sign in before opening the security center', function () {
    $this->get(route('zero-trust.index'))
        ->assertRedirect(route('login'));
});

test('verified users can see their real account security posture', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('zero-trust.index'))
        ->assertOk()
        ->assertSee('Centre de sécurité')
        ->assertSee($user->email)
        ->assertSee('MFA à activer')
        ->assertSee('Zone protégée');
});

test('the security center shows only the signed in user’s audit events', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    DB::table('zero_trust_events')->insert([
        'user_id' => $otherUser->id,
        'event_type' => 'private_event',
        'description' => 'Événement privé d’un autre compte',
        'created_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('zero-trust.index'))
        ->assertOk()
        ->assertDontSee('Événement privé d’un autre compte');
});

test('unverified users cannot open the security center', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('zero-trust.index'))
        ->assertRedirect(route('verification.notice'));
});

test('protected workspace refuses users without confirmed multi-factor authentication', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('zero-trust.workspace'))
        ->assertRedirect(route('zero-trust.index'))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('zero_trust_events', [
        'user_id' => $user->id,
        'event_type' => 'protected_access_denied_mfa',
    ]);
});

test('protected workspace requires a recent password confirmation after MFA is enabled', function () {
    $user = User::factory()->withTwoFactor()->create();

    $this->actingAs($user)
        ->get(route('zero-trust.workspace'))
        ->assertRedirect(route('password.confirm'));
});

test('protected workspace opens and records access after MFA and password confirmation', function () {
    $user = User::factory()->withTwoFactor()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => now()->timestamp])
        ->get(route('zero-trust.workspace'))
        ->assertOk()
        ->assertSee('Zone protégée')
        ->assertSee($user->email);

    $this->assertDatabaseHas('zero_trust_events', [
        'user_id' => $user->id,
        'event_type' => 'protected_workspace_accessed',
    ]);
});

test('users can revoke other database sessions after confirming their password', function () {
    config(['session.driver' => 'database']);

    $user = User::factory()->create();

    DB::table('sessions')->insert([
        'id' => 'revocable-session',
        'user_id' => $user->id,
        'ip_address' => '192.0.2.15',
        'user_agent' => 'Test browser',
        'payload' => '{}',
        'last_activity' => now()->timestamp,
    ]);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => now()->timestamp])
        ->post(route('zero-trust.sessions.revoke-others'))
        ->assertRedirect(route('zero-trust.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('sessions', ['id' => 'revocable-session']);
    $this->assertDatabaseHas('zero_trust_events', [
        'user_id' => $user->id,
        'event_type' => 'other_sessions_revoked',
    ]);
});
