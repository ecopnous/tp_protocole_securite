<x-layouts::app :title="__('Centre de sécurité')">
    <main class="app-surface min-h-screen bg-slate-950 px-4 py-8 text-slate-100 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">
            <section class="app-hero rounded-[2rem] border border-emerald-400/20 bg-[radial-gradient(circle_at_top_right,_rgba(16,185,129,0.14),transparent_34%),linear-gradient(135deg,#0f172a_0%,#020617_82%)] p-7 shadow-2xl shadow-emerald-950/20 sm:p-10">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-300">Contrôles réels de votre compte</p>
                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-5xl">Centre de sécurité</h1>
                <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base">
                    Consultez la protection active de votre compte, révoquez vos autres sessions et ouvrez une zone protégée
                    après vérification du MFA et confirmation récente de votre mot de passe.
                </p>
                <p class="mt-5 text-sm text-slate-400">Connecté en tant que <span class="font-medium text-slate-200">{{ $user->email }}</span></p>
            </section>

            @if (session('success'))
                <div role="status" class="rounded-2xl border border-emerald-400/25 bg-emerald-400/10 px-5 py-4 text-sm text-emerald-200">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div role="alert" class="rounded-2xl border border-rose-400/25 bg-rose-400/10 px-5 py-4 text-sm text-rose-200">{{ session('error') }}</div>
            @endif

            <section aria-labelledby="posture-heading">
                <div class="mb-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">État du compte</p>
                    <h2 id="posture-heading" class="mt-2 text-2xl font-semibold text-white">Posture de sécurité actuelle</h2>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-2xl border border-slate-800 bg-slate-900/75 p-5">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Identité</p>
                        <p class="mt-3 text-lg font-semibold text-emerald-200">E-mail vérifié</p>
                        <p class="mt-2 text-sm text-slate-400">{{ $user->email }}</p>
                    </article>
                    <article class="rounded-2xl border {{ $mfaEnabled ? 'border-emerald-400/25' : 'border-amber-400/25' }} bg-slate-900/75 p-5">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Authentification</p>
                        <p class="mt-3 text-lg font-semibold {{ $mfaEnabled ? 'text-emerald-200' : 'text-amber-200' }}">{{ $mfaEnabled ? 'MFA activée' : 'MFA à activer' }}</p>
                        <a href="{{ route('security.edit') }}" class="mt-2 inline-flex text-sm text-cyan-300 underline decoration-cyan-300/30 underline-offset-4 hover:text-cyan-200">Gérer MFA et passkeys</a>
                    </article>
                    <article class="rounded-2xl border border-slate-800 bg-slate-900/75 p-5">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Passkeys</p>
                        <p class="mt-3 text-lg font-semibold text-white">{{ $passkeyCount }} enregistrée(s)</p>
                        <p class="mt-2 text-sm text-slate-400">Clés liées à ce compte</p>
                    </article>
                    <article class="rounded-2xl border border-slate-800 bg-slate-900/75 p-5">
                        <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Sessions actives</p>
                        @if ($canManageSessions)
                            <p class="mt-3 text-lg font-semibold text-white">{{ $sessions->count() }} enregistrée(s)</p>
                            <p class="mt-2 text-sm text-slate-400">Stockées par l’application</p>
                        @else
                            <p class="mt-3 text-lg font-semibold text-amber-200">Non disponibles</p>
                            <p class="mt-2 text-sm text-slate-400">Le pilote configuré ne permet pas de les lister ici.</p>
                        @endif
                    </article>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-[1fr_1.25fr]">
                <article class="rounded-3xl border border-cyan-400/20 bg-slate-900/70 p-6 sm:p-7">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-300">Accès contrôlé</p>
                    <h2 class="mt-3 text-xl font-semibold text-white">Zone protégée</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-400">Cette page réelle vérifie que le MFA est confirmé et que vous avez récemment confirmé votre mot de passe. Elle affiche les événements de sécurité de votre compte.</p>
                    @if ($mfaEnabled)
                        <a href="{{ route('zero-trust.workspace') }}" class="mt-5 inline-flex items-center justify-center rounded-xl bg-cyan-400 px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-200 focus:ring-offset-2 focus:ring-offset-slate-900">Vérifier et ouvrir</a>
                    @else
                        <a href="{{ route('security.edit') }}" class="mt-5 inline-flex items-center justify-center rounded-xl border border-amber-400/25 bg-amber-400/10 px-4 py-3 text-sm font-semibold text-amber-100 transition hover:bg-amber-400/15">Activer le MFA d’abord</a>
                    @endif
                </article>

                <article class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 sm:p-7">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Contrôle de session</p>
                            <h2 class="mt-3 text-xl font-semibold text-white">Vos sessions</h2>
                        </div>
                        @if ($canManageSessions && $sessions->contains(fn (array $session): bool => ! $session['current']))
                            <form method="POST" action="{{ route('zero-trust.sessions.revoke-others') }}">
                                @csrf
                                <button type="submit" class="rounded-xl border border-rose-400/25 bg-rose-400/10 px-4 py-2.5 text-sm font-semibold text-rose-200 transition hover:bg-rose-400/15 focus:outline-none focus:ring-2 focus:ring-rose-300">Révoquer les autres sessions</button>
                            </form>
                        @endif
                    </div>

                    @if (! $canManageSessions)
                        <p class="mt-5 rounded-xl border border-amber-400/15 bg-amber-400/[0.04] p-4 text-sm leading-6 text-amber-100/80">La liste et la révocation sont disponibles lorsque l’application utilise le stockage de sessions en base de données.</p>
                    @elseif ($sessions->isEmpty())
                        <p class="mt-5 rounded-xl border border-slate-800 bg-slate-950/60 p-4 text-sm text-slate-400">Aucune session persistée n’a été trouvée pour ce compte.</p>
                    @else
                        <ul class="mt-5 space-y-3">
                            @foreach ($sessions as $session)
                                <li class="rounded-2xl border {{ $session['current'] ? 'border-emerald-400/20 bg-emerald-400/[0.04]' : 'border-slate-800 bg-slate-950/60' }} p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <span class="text-sm font-semibold text-slate-100">{{ $session['current'] ? 'Session actuelle' : 'Autre session' }}</span>
                                        <span class="text-xs text-slate-500">{{ $session['last_activity'] }}</span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-300">{{ $session['device'] }} · {{ $session['browser'] }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $session['platform'] }} · {{ $session['ip_address'] }}</p>
                                    <details class="mt-2">
                                        <summary class="cursor-pointer text-xs text-slate-500">Informations du navigateur reçues</summary>
                                        <p class="mt-1 break-words text-xs leading-5 text-slate-500">{{ $session['user_agent'] }}</p>
                                    </details>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </article>

                <article class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 sm:p-7">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Appareils mémorisés</p>
                    <h2 class="mt-3 text-xl font-semibold text-white">Appareils et historique</h2>
                    @if ($devices->isEmpty())
                        <p class="mt-5 rounded-xl border border-slate-800 bg-slate-950/60 p-4 text-sm text-slate-400">Aucun appareil n’a encore été enregistré.</p>
                    @else
                        <ul class="mt-5 space-y-3">
                            @foreach ($devices as $device)
                                <li class="rounded-2xl border {{ $device->status === 'trusted' ? 'border-emerald-400/20 bg-emerald-400/[0.04]' : 'border-slate-800 bg-slate-950/60' }} p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <span class="text-sm font-semibold text-slate-100">{{ $device->device }} · {{ $device->browser }}</span>
                                        <span class="text-xs font-medium {{ $device->status === 'trusted' ? 'text-emerald-300' : 'text-rose-300' }}">{{ $device->status === 'trusted' ? 'Enregistré' : 'Révoqué' }}</span>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-400">{{ $device->platform }} · {{ $device->ip_address ?: 'Adresse inconnue' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Dernière activité {{ $device->last_seen_at ? \Illuminate\Support\Carbon::parse($device->last_seen_at)->diffForHumans() : 'inconnue' }}</p>
                                    @if ($device->revocation_reason)
                                        <p class="mt-1 text-xs text-rose-300">État : {{ $device->revocation_reason === 'reported_not_me' ? 'signalé comme inconnu' : 'sessions révoquées' }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </article>
            </section>

            <section aria-labelledby="events-heading" class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 sm:p-7">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Journal persistant</p>
                        <h2 id="events-heading" class="mt-2 text-xl font-semibold text-white">Événements de sécurité</h2>
                    </div>
                    <p class="text-xs text-slate-500">Les 10 événements les plus récents de votre compte</p>
                </div>
                @if ($events->isEmpty())
                    <p class="mt-5 rounded-xl border border-slate-800 bg-slate-950/60 p-4 text-sm text-slate-400">Aucun événement pour le moment. Les accès à la zone protégée et les révocations apparaîtront ici.</p>
                @else
                    <ol class="mt-5 divide-y divide-slate-800">
                        @foreach ($events as $event)
                            <li class="flex flex-col gap-2 py-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-200">{{ $event->description }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $event->event_label }} · {{ $event->ip_address ?: 'Adresse inconnue' }}</p>
                                </div>
                                <time class="shrink-0 text-xs text-slate-500" datetime="{{ $event->created_at }}">{{ \Illuminate\Support\Carbon::parse($event->created_at)->diffForHumans() }}</time>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </section>
        </div>
    </main>
</x-layouts::app>
