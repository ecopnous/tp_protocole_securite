<x-layouts::app :title="__('Simulation Zero Trust')">
    <main class="app-surface min-h-screen bg-slate-950 px-4 py-8 text-slate-100 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">
            <section class="app-hero relative overflow-hidden rounded-[2rem] border border-cyan-400/20 bg-[radial-gradient(circle_at_top_right,_rgba(34,211,238,0.15),transparent_32%),linear-gradient(135deg,#0f172a_0%,#020617_80%)] p-7 shadow-2xl shadow-cyan-950/20 sm:p-10">
                <div class="relative z-10 max-w-3xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-300">TP Protocole · laboratoire de sécurité</p>
                    <h1 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-5xl">Simulation Zero Trust</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300">
                        N’accordez jamais la confiance par défaut. Évaluez chaque demande selon l’identité, l’appareil,
                        l’authentification, le risque et l’état de la session.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3 text-xs font-medium">
                        <span class="rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1.5 text-cyan-200">6 scénarios interactifs</span>
                        <span class="rounded-full border border-slate-700 bg-slate-900/70 px-3 py-1.5 text-slate-300">Décision par défaut : refuser</span>
                        <span class="rounded-full border border-slate-700 bg-slate-900/70 px-3 py-1.5 text-slate-300">Réévaluation continue</span>
                    </div>
                </div>
            </section>

            @if ($errors->any())
                <div role="alert" class="rounded-2xl border border-rose-500/30 bg-rose-500/10 px-5 py-4 text-sm text-rose-200">
                    {{ $errors->first() }}
                </div>
            @endif

            @if ($simulationResult)
                <section id="simulation-result" aria-live="polite" class="rounded-3xl border {{ $simulationResult['allowed'] ? 'border-emerald-400/30 bg-emerald-400/[0.06]' : 'border-rose-400/30 bg-rose-400/[0.06]' }} p-6 shadow-xl sm:p-8">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Résultat de la simulation · {{ $simulationResult['resource'] }}</p>
                            <h2 class="mt-2 text-2xl font-semibold text-white">{{ $simulationResult['title'] }}</h2>
                        </div>
                        <span class="rounded-full px-3 py-1.5 text-sm font-semibold {{ $simulationResult['allowed'] ? 'bg-emerald-400/10 text-emerald-300' : 'bg-rose-400/10 text-rose-300' }}">
                            {{ $simulationResult['decision'] }}
                        </span>
                    </div>
                    <p class="mt-4 max-w-3xl leading-7 text-slate-300">{{ $simulationResult['explanation'] }}</p>
                    <div class="mt-5 rounded-2xl border border-white/10 bg-slate-950/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-300">Action de politique recommandée</p>
                        <p class="mt-2 text-sm leading-6 text-slate-200">{{ $simulationResult['action'] }}</p>
                    </div>
                    <ul class="mt-5 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($simulationResult['checks'] as $check)
                            <li class="flex items-center gap-2 rounded-xl border border-white/5 bg-slate-950/40 px-3 py-2 text-sm {{ $check['passed'] ? 'text-emerald-200' : 'text-rose-200' }}">
                                <span aria-hidden="true">{{ $check['passed'] ? '✓' : '×' }}</span>
                                {{ $check['label'] }}
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <section aria-labelledby="scenario-heading">
                <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Choisissez une situation</p>
                        <h2 id="scenario-heading" class="mt-2 text-2xl font-semibold text-white">Scénarios de contrôle d’accès</h2>
                    </div>
                    <p class="max-w-xl text-sm leading-6 text-slate-400">Chaque exécution évalue les mêmes contrôles et explique la décision, le signal déterminant et la réponse recommandée.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($scenarios as $scenarioId => $scenario)
                        <article class="flex h-full flex-col rounded-3xl border border-slate-800 bg-slate-900/70 p-5 shadow-lg shadow-slate-950/30 transition hover:border-cyan-400/30">
                            <div class="flex items-start justify-between gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl border border-cyan-400/20 bg-cyan-400/10 text-sm font-semibold text-cyan-200">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="rounded-full border border-slate-700 bg-slate-950/70 px-2.5 py-1 text-[11px] text-slate-400">{{ $scenario['resource'] }}</span>
                            </div>
                            <h3 class="mt-5 text-lg font-semibold text-white">{{ $scenario['title'] }}</h3>
                            <p class="mt-2 flex-1 text-sm leading-6 text-slate-400">{{ $scenario['summary'] }}</p>
                            <form method="POST" action="{{ route('dashboard.simulate') }}" class="mt-5">
                                @csrf
                                <input type="hidden" name="scenario" value="{{ $scenarioId }}">
                                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-cyan-400/20 bg-cyan-400/10 px-4 py-3 text-sm font-semibold text-cyan-100 transition hover:border-cyan-300/40 hover:bg-cyan-400/15 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2 focus:ring-offset-slate-900">
                                    Simuler ce scénario <span aria-hidden="true">→</span>
                                </button>
                            </form>
                        </article>
                    @endforeach
                </div>
            </section>

            <p class="rounded-2xl border border-amber-400/15 bg-amber-400/[0.04] px-5 py-4 text-sm leading-6 text-amber-100/80">
                <strong class="font-semibold text-amber-200">Mode pédagogique :</strong>
                les signaux sont des données fictives. Cette page n’interroge ni appareil, ni fournisseur d’identité, ni réseau,
                et ne bloque ou ne révoque aucune session réelle.
            </p>
        </div>
    </main>
</x-layouts::app>
