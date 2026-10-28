# TP Protocole — Laboratoire de simulation Zero Trust

## Présentation

Le tableau de bord de démonstration a été remplacé par un laboratoire interactif qui montre comment une politique Zero Trust peut évaluer une demande d’accès. L’utilisateur choisit un scénario, le serveur vérifie les signaux simulés, puis affiche une décision, les contrôles réussis ou échoués et une action recommandée.

Le principe présenté est **« ne jamais faire confiance par défaut, toujours vérifier »**. Une identité authentifiée ne suffit pas à elle seule : l’accès dépend aussi de l’appareil, du MFA, du risque et de l’état de la session. La règle de démonstration est le refus par défaut si un contrôle requis échoue.

> **Limite importante :** ce laboratoire est pédagogique. Il utilise des signaux prédéfinis dans le code. Il ne contacte aucun fournisseur d’identité, agent de gestion d’appareils, service de renseignement sur les risques ou SIEM. Les décisions affichées ne bloquent ni ne révoquent un accès réel et ne constituent pas une protection de production.

## Utiliser le simulateur

1. Connectez-vous à l’application avec un compte vérifié.
2. Ouvrez **Simulation Zero Trust** depuis le tableau de bord.
3. Choisissez l’un des six scénarios et sélectionnez **Simuler ce scénario**.
4. Consultez la décision, le motif, la recommandation et le résultat de chacun des contrôles.
5. Exécutez un autre scénario pour comparer les décisions.

Les scénarios sont accessibles à tout utilisateur connecté et vérifié. Ils n’accordent aucun rôle, privilège ou accès réel.

## Scénarios inclus

| Scénario | Signaux déterminants | Décision attendue | Réponse simulée |
| --- | --- | --- | --- |
| Accès depuis un poste de confiance | Identité, appareil enregistré et conforme, MFA, session récente, risque 12/100 | Accès autorisé | Accorder l’accès limité à la ressource et réévaluer la confiance pendant la session. |
| Appareil inconnu | Identité et MFA valides, appareil non enregistré | Accès refusé | Enrôler et vérifier l’appareil avant de réessayer. |
| Action privilégiée sans MFA | Demande sensible, appareil conforme, MFA absente | Accès refusé | Effectuer une vérification MFA avant l’action sensible. |
| Connexion à risque élevé | Tous les contrôles courants réussis, risque 92/100 | Accès bloqué et session révoquée | Révoquer les sessions actives dans le scénario, conserver l’événement d’audit et demander une vérification. |
| Appareil non conforme | Appareil enregistré et MFA valides, conformité de l’appareil échouée | Accès refusé | Corriger la conformité de l’appareil puis réessayer. |
| Session ancienne après changement de risque | Identité, appareil et MFA valides, session à réévaluer | Session révoquée | Demander une nouvelle authentification et réévaluer tous les signaux. |

Les scores sont des exemples. Le seuil codé pour cette démonstration est **60** : un score supérieur bloque la demande. Les scénarios échouant sur plusieurs signaux présentent le contrôle décisif ou la réponse prioritaire.

## Contrôles évalués

Le moteur de démonstration examine six contrôles dans l’ordre suivant :

1. **Identité authentifiée** — la demande part d’une identité reconnue par le scénario.
2. **Appareil enregistré** — l’appareil est présent dans l’inventaire de confiance.
3. **Appareil conforme** — ses protections requises sont actives et à jour.
4. **MFA vérifiée** — la demande a satisfait la vérification multifacteur.
5. **Risque acceptable** — le score doit être inférieur ou égal à 60.
6. **Session récente et réévaluée** — la session n’est pas devenue obsolète après un changement de contexte.

Si tous les contrôles passent, l’accès est autorisé dans le résultat de simulation. Sinon, l’accès est refusé par défaut. Un risque supérieur au seuil produit une réponse de blocage et de révocation simulée ; une session obsolète demande une réauthentification simulée. Les autres échecs indiquent le contrôle à corriger.

## Changements apportés

### Tableau de bord et interface

- Le tableau de bord présente maintenant le laboratoire Zero Trust, ses principes et les six scénarios.
- Chaque scénario a son propre formulaire protégé par le jeton CSRF de Laravel.
- Le résultat montre la ressource, la décision, l’explication, l’action recommandée et l’état de chaque contrôle.
- L’interface indique clairement que les données sont fictives et qu’aucune session réelle n’est affectée.
- La présentation s’adapte aux petits et grands écrans et conserve le thème sombre de l’application.

### Routes et traitement

- `GET /dashboard` affiche les scénarios et le dernier résultat de la session.
- `POST /dashboard/simulate` valide l’identifiant du scénario, lance le moteur et redirige vers le tableau de bord avec le résultat en session.
- Les deux routes restent protégées par les contrôles `auth` et `verified` déjà utilisés par l’application.
- Les identifiants de scénario sont validés côté serveur ; une valeur inventée est rejetée.

### Moteur de simulation

- `app/Services/ZeroTrustSimulator.php` contient les signaux fictifs des six scénarios et la logique des décisions.
- `app/Http/Controllers/ZeroTrustSimulationController.php` sert la page et reçoit les demandes de simulation.
- Les réponses du moteur comprennent la décision, son explication, une action recommandée et le détail des contrôles.
- Aucune donnée de simulation n’est écrite dans la base de données.

### Nettoyage des tableaux de bord

- L’ancien écran utilisateur contenait des statistiques, alertes, taux et boutons statiques sans données ni actions associées. Il a été remplacé par le simulateur.
- L’écran `/admin` répétait le même tableau de bord statique et n’offrait pas de fonctions d’administration réelles. Sa route, sa vue et le middleware exclusivement utilisé par cette route ont été retirés.
- Les paramètres de profil et de sécurité de l’application ne sont pas supprimés.

## Fichiers concernés

- `routes/web.php` — routes du tableau de bord, du simulateur et du centre de sécurité ; retrait de la route `/admin`.
- `app/Http/Controllers/ZeroTrustSimulationController.php` — affichage et traitement des simulations.
- `app/Services/ZeroTrustSimulator.php` — scénarios, contrôles et décisions.
- `resources/views/dashboard.blade.php` — interface du laboratoire et restitution des résultats.
- `tests/Feature/DashboardTest.php` — accès au laboratoire, couverture des six décisions, validation des scénarios et retrait de l’ancienne route.
- `tests/Feature/ZeroTrustSecurityCenterTest.php` — posture du compte, journal par utilisateur, garde MFA, confirmation du mot de passe et révocation de sessions.
- `README.md` — documentation d’utilisation, de politique et des limites.
- `resources/views/admin/dashboard.blade.php` et `app/Http/Middleware/EnsureUserIsAdmin.php` — supprimés avec la route d’administration inutilisée.

## Vérifications

Les tests ciblés sont exécutables avec :

```powershell
php artisan test --compact tests/Feature/DashboardTest.php tests/Feature/ZeroTrustSecurityCenterTest.php
```

Le build des ressources frontend peut être produit avec :

```powershell
npm run build
```

## Pour passer d’une démonstration à une politique réelle

Une mise en œuvre de production devra remplacer les signaux codés en dur par des sources fiables : fournisseur d’identité et MFA, inventaire et conformité des appareils, signaux de risque, gestion des sessions et journal d’audit. Il faudra aussi définir les ressources et actions par rôle, des politiques d’accès minimales, la protection contre les abus, la supervision, la rétention des journaux et des procédures de récupération. Les réponses telles que la révocation et la mise en quarantaine devront être reliées aux systèmes réels et testées séparément avant activation.

## Centre de sécurité avec contrôles réels

La page **Centre de sécurité** (`GET /zero-trust`) complète le laboratoire. Elle lit les informations actuelles du compte et propose des actions qui affectent réellement l’application. Elle est disponible depuis la navigation latérale.

### Fonctionnalités

1. **Posture réelle du compte** — affiche l’adresse vérifiée, l’état du MFA confirmé par Fortify et le nombre de passkeys réellement liées au compte.
2. **Inventaire et révocation des sessions** — lorsque `SESSION_DRIVER=database`, liste les sessions stockées avec leur adresse IP, navigateur et dernière activité. Le bouton révoque en base toutes les autres sessions de l’utilisateur, conserve la session courante et exige une confirmation récente du mot de passe.
3. **Zone protégée appliquée côté serveur** — `GET /zero-trust/workspace` exige une session authentifiée, une adresse e-mail vérifiée, un MFA confirmé et une confirmation récente du mot de passe. Le contrôle MFA est un middleware serveur : masquer un lien dans l’interface ne permet pas de le contourner.
4. **Journal d’événements persistant** — enregistre les accès accordés à la zone, les refus dus au MFA manquant et les révocations de sessions. Les événements incluent l’utilisateur, l’adresse IP, l’agent utilisateur et l’heure, et le centre affiche les événements du compte connecté.

La durée de validité de la confirmation du mot de passe suit le réglage Laravel `auth.password_timeout` (trois heures par défaut). Le middleware `password.confirm` redirige vers le formulaire existant puis renvoie l’utilisateur vers l’action demandée après confirmation.

Le modèle `User` implémente aussi le contrat Laravel `MustVerifyEmail`. Cela active réellement le middleware `verified` déjà présent sur les routes concernées et déclenche l’envoi du courriel de vérification à l’inscription.

### Installation et configuration

Après récupération de ces changements, appliquez la migration :

```powershell
php artisan migrate
```

La révocation des sessions dépend du pilote `database` et du nom de table configuré dans `config/session.php`. C’est le pilote par défaut du projet et la table `sessions` est créée par la migration initiale. Avec un autre pilote, la page le signale et désactive la gestion de sessions au lieu de prétendre afficher ou révoquer des sessions qu’elle ne peut pas atteindre.

Le MFA se configure depuis **Gérer MFA et passkeys**. L’utilisateur doit terminer et confirmer l’enrôlement avant que le middleware l’autorise à ouvrir la zone protégée.

### Portée et limites des contrôles réels

Les contrôles ci-dessus sont appliqués et persistés par l’application, contrairement aux scénarios prédéfinis du laboratoire. Ils ne constituent cependant pas une plateforme Zero Trust complète : aucun fournisseur externe ne fournit ici de risque géographique, de conformité système ou d’attestation matérielle. L’adresse IP et l’agent utilisateur sont des éléments d’audit déclarés par la requête, pas une preuve d’identité ou de confiance de l’appareil. Les connexions MFA doivent rester activées dans Fortify pour les utilisateurs concernés. Les événements ne sont visibles que dans l’application et leur durée de conservation doit être définie selon les exigences du déploiement.

### Fichiers du centre de sécurité

- `app/Http/Controllers/SecurityCenterController.php` — état du compte, inventaire/révocation des sessions, accès à la zone et événements.
- `app/Http/Middleware/EnsureMfaConfigured.php` — refus serveur et événement d’audit si le MFA confirmé manque.
- `app/Models/User.php` — activation effective de la vérification d’adresse e-mail.
- `database/migrations/*_create_zero_trust_events_table.php` — journal de sécurité persistant.
- `resources/views/zero-trust/index.blade.php` — centre de sécurité.
- `resources/views/zero-trust/workspace.blade.php` — ressource réelle protégée par les contrôles.
- `routes/web.php` et `resources/views/layouts/app/sidebar.blade.php` — routes et navigation.
- `tests/Feature/ZeroTrustSecurityCenterTest.php` — couverture des accès, refus MFA et révocations.
