<?php

use App\Models\User;
use App\Services\DeviceRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

test('a user can confirm a new device from the email verification link', function () {
    $user = User::factory()->create();
    $deviceToken = Str::random(64);
    $challengeToken = Str::random(64);
    $challenge = DB::table('device_login_challenges')->insertGetId([
        'user_id' => $user->id,
        'token_hash' => hash('sha256', $challengeToken),
        'device_token_hash' => hash('sha256', $deviceToken),
        'device_token' => Crypt::encryptString($deviceToken),
        'session_id' => 'pending-login-session',
        'ip_address' => '192.0.2.20',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/156.0.0.0',
        'status' => 'pending',
        'expires_at' => now()->addMinutes(20),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $url = app(DeviceRegistry::class)->emailActionUrl(
        Request::create('http://app.example.test/login'),
        'security.devices.confirm',
        ['challenge' => $challenge, 'token' => $challengeToken],
    );
    $url = str_replace('app.example.test', 'mail-client-domain.example', $url);
    expect($url)->toStartWith('http://mail-client-domain.example/')
        ->and($url)->not->toContain('signature=');

    $this->get($url)
        ->assertOk()
        ->assertSee('Oui, c’était moi');
    $this->post($url)->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseHas('trusted_devices', [
        'user_id' => $user->id,
        'token_hash' => hash('sha256', $deviceToken),
        'status' => 'trusted',
        'browser' => 'Google Chrome 156.0.0.0',
    ]);
    $this->assertDatabaseHas('device_login_challenges', [
        'id' => $challenge,
        'status' => 'confirmed',
    ]);
});

test('a user can reject and forget a reported new device', function () {
    $user = User::factory()->create();
    $deviceToken = Str::random(64);
    $challengeToken = Str::random(64);
    $challenge = DB::table('device_login_challenges')->insertGetId([
        'user_id' => $user->id,
        'token_hash' => hash('sha256', $challengeToken),
        'device_token_hash' => hash('sha256', $deviceToken),
        'device_token' => Crypt::encryptString($deviceToken),
        'session_id' => 'pending-login-session',
        'ip_address' => '192.0.2.21',
        'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) Firefox/132.0',
        'status' => 'pending',
        'expires_at' => now()->addMinutes(20),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $url = app(DeviceRegistry::class)->emailActionUrl(
        Request::create('http://app.example.test/login'),
        'security.devices.deny',
        ['challenge' => $challenge, 'token' => $challengeToken],
    );

    $this->post($url)->assertRedirect(route('login'));

    $this->assertDatabaseHas('trusted_devices', [
        'user_id' => $user->id,
        'token_hash' => hash('sha256', $deviceToken),
        'status' => 'revoked',
        'revocation_reason' => 'reported_not_me',
    ]);
    $this->assertDatabaseHas('trusted_device_events', [
        'user_id' => $user->id,
        'event_type' => 'device_reported_not_me',
        'status' => 'revoked',
    ]);
    $this->assertDatabaseHas('device_login_challenges', [
        'id' => $challenge,
        'status' => 'denied',
    ]);
});
