<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
    $response->assertSee('Simulation Zero Trust');
    $response->assertSee('Scénarios de contrôle d’accès');
    $response->assertSee('trusted-access', false);
    $response->assertSee('unknown-device', false);
    $response->assertSee('privileged-step-up', false);
    $response->assertSee('high-risk-login', false);
    $response->assertSee('noncompliant-device', false);
    $response->assertSee('stale-session', false);
});

test('authenticated users can run all six zero trust simulations', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $expectedDecisions = [
        'trusted-access' => 'Accès autorisé',
        'unknown-device' => 'Accès refusé',
        'privileged-step-up' => 'Accès refusé',
        'high-risk-login' => 'Accès bloqué et session révoquée',
        'noncompliant-device' => 'Accès refusé',
        'stale-session' => 'Session révoquée',
    ];

    foreach ($expectedDecisions as $scenario => $decision) {
        $this->post(route('dashboard.simulate'), ['scenario' => $scenario])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('simulation_result.scenario', $scenario)
            ->assertSessionHas('simulation_result.decision', $decision);
    }
});

test('simulation rejects an unknown scenario', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('dashboard'))
        ->post(route('dashboard.simulate'), ['scenario' => 'invented-scenario'])
        ->assertRedirect(route('dashboard'))
        ->assertSessionHasErrors('scenario');
});

test('the obsolete admin dashboard route is no longer available', function () {
    $this->get('/admin')->assertNotFound();
});
