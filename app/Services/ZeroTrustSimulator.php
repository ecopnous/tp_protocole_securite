<?php

namespace App\Services;

class ZeroTrustSimulator
{
    /**
     * @return array<string, array{
     *     title: string, summary: string, resource: string, identity_verified: bool,
     *     device_enrolled: bool, device_compliant: bool, mfa_verified: bool,
     *     risk_score: int, session_fresh: bool
     * }>
     */
    public function scenarios(): array
    {
        return [
            'trusted-access' => [
                'title' => 'Accès depuis un poste de confiance',
                'summary' => 'Un membre vérifié demande une ressource courante depuis un appareil géré, conforme et protégé par MFA.',
                'resource' => 'Espace de travail standard',
                'identity_verified' => true, 'device_enrolled' => true, 'device_compliant' => true,
                'mfa_verified' => true, 'risk_score' => 12, 'session_fresh' => true,
            ],
            'unknown-device' => [
                'title' => 'Appareil inconnu',
                'summary' => 'Les identifiants sont valides, mais l’appareil n’est pas enregistré dans l’inventaire de confiance.',
                'resource' => 'Espace de travail standard',
                'identity_verified' => true, 'device_enrolled' => false, 'device_compliant' => false,
                'mfa_verified' => true, 'risk_score' => 24, 'session_fresh' => true,
            ],
            'privileged-step-up' => [
                'title' => 'Action privilégiée sans MFA',
                'summary' => 'Un administrateur demande une action sensible avec un appareil conforme, mais sans authentification multifacteur récente.',
                'resource' => 'Gestion des rôles et des accès',
                'identity_verified' => true, 'device_enrolled' => true, 'device_compliant' => true,
                'mfa_verified' => false, 'risk_score' => 18, 'session_fresh' => true,
            ],
            'high-risk-login' => [
                'title' => 'Connexion à risque élevé',
                'summary' => 'Une connexion présente des signaux inhabituels. Le moteur de politique doit bloquer et signaler la tentative.',
                'resource' => 'Portail de gestion',
                'identity_verified' => true, 'device_enrolled' => true, 'device_compliant' => true,
                'mfa_verified' => true, 'risk_score' => 92, 'session_fresh' => true,
            ],
            'noncompliant-device' => [
                'title' => 'Appareil non conforme',
                'summary' => 'L’appareil est connu, mais ses contrôles de sécurité sont dépassés ou désactivés.',
                'resource' => 'Documents internes',
                'identity_verified' => true, 'device_enrolled' => true, 'device_compliant' => false,
                'mfa_verified' => true, 'risk_score' => 28, 'session_fresh' => true,
            ],
            'stale-session' => [
                'title' => 'Session ancienne après changement de risque',
                'summary' => 'Le niveau de risque a changé depuis l’ouverture de la session. L’accès doit être réévalué avant de continuer.',
                'resource' => 'Données sensibles',
                'identity_verified' => true, 'device_enrolled' => true, 'device_compliant' => true,
                'mfa_verified' => true, 'risk_score' => 42, 'session_fresh' => false,
            ],
        ];
    }

    /**
     * @return array{
     *     scenario: string, title: string, resource: string, decision: string,
     *     allowed: bool, explanation: string, action: string,
     *     checks: list<array{label: string, passed: bool}>
     * }
     */
    public function simulate(string $scenarioId): array
    {
        $scenario = $this->scenarios()[$scenarioId];
        $checks = [
            ['label' => 'Identité authentifiée', 'passed' => $scenario['identity_verified']],
            ['label' => 'Appareil enregistré', 'passed' => $scenario['device_enrolled']],
            ['label' => 'Appareil conforme', 'passed' => $scenario['device_compliant']],
            ['label' => 'MFA vérifiée', 'passed' => $scenario['mfa_verified']],
            ['label' => 'Risque acceptable (score ≤ 60)', 'passed' => $scenario['risk_score'] <= 60],
            ['label' => 'Session récente et réévaluée', 'passed' => $scenario['session_fresh']],
        ];

        $failedCheck = collect($checks)->first(fn (array $check): bool => ! $check['passed']);

        if ($failedCheck === null) {
            return [
                'scenario' => $scenarioId,
                'title' => $scenario['title'],
                'resource' => $scenario['resource'],
                'decision' => 'Accès autorisé',
                'allowed' => true,
                'explanation' => 'Tous les contrôles requis sont satisfaits pour cette demande et ce contexte.',
                'action' => 'Accorder un accès limité à cette ressource, puis réévaluer la confiance pendant la session.',
                'checks' => $checks,
            ];
        }

        $highRisk = $scenario['risk_score'] > 60;
        $staleSession = ! $scenario['session_fresh'];

        return [
            'scenario' => $scenarioId,
            'title' => $scenario['title'],
            'resource' => $scenario['resource'],
            'decision' => $highRisk ? 'Accès bloqué et session révoquée' : ($staleSession ? 'Session révoquée' : 'Accès refusé'),
            'allowed' => false,
            'explanation' => $highRisk
                ? 'Le score de risque dépasse le seuil autorisé. La politique bloque la demande et déclenche une alerte.'
                : ($staleSession
                    ? 'La session n’est plus assez récente pour ce contexte. Une nouvelle authentification est requise.'
                    : 'Le contrôle « '.$failedCheck['label'].' » a échoué. La politique refuse l’accès par défaut.'),
            'action' => $highRisk
                ? 'Révoquer les sessions actives, conserver l’événement d’audit et demander une vérification.'
                : ($staleSession
                    ? 'Forcer une nouvelle authentification et réévaluer tous les signaux avant de reprendre.'
                    : 'Revérification d\émail ou compléter le MFA, puis enrôler ou mettre à jour l’appareil.'),
            'checks' => $checks,
        ];
    }
}
