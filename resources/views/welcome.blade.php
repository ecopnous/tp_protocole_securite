<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        @include('partials.head', ['title' => __('TP Protocole')])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <div class="relative isolate overflow-hidden">
            <div class="absolute inset-0 -z-20 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.18),transparent_20%),radial-gradient(circle_at_bottom_right,_rgba(99,102,241,0.16),transparent_30%),linear-gradient(135deg,#020817_0%,#0f172a_35%,#111827_100%)]"></div>
            <div class="absolute inset-0 -z-10 opacity-30 [background-image:linear-gradient(rgba(148,163,184,0.08)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.08)_1px,transparent_1px)] [background-size:52px_52px]"></div>

            <header class="mx-auto max-w-6xl px-4 pt-6 sm:px-6 lg:px-8">
                <nav class="flex items-center justify-between rounded-full border border-white/10 bg-white/5 px-4 py-3 shadow-[0_20px_60px_-20px_rgba(14,165,233,0.45)] backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-400 via-sky-500 to-indigo-600 shadow-lg shadow-sky-500/30">
                            <span class="text-lg font-bold text-slate-950">TP</span>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-[0.25em] text-sky-300">TP Protocole</p>
                            <p class="text-[10px] text-slate-300">Sécurité informatique</p>
                        </div>
                    </div>

                    @if (Route::has('login'))
                        <div class="flex items-center gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-full border border-sky-400/40 bg-sky-500/10 px-4 py-2 text-sm font-medium text-sky-100 transition hover:bg-sky-500/20">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center rounded-full border border-transparent px-4 py-2 text-sm font-medium text-slate-200 transition hover:border-white/10 hover:bg-white/5">
                                    Connexion
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-white px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-slate-200">
                                        Inscription
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </nav>
            </header>

            <main class="mx-auto max-w-6xl px-4 pb-20 pt-14 sm:px-6 lg:px-8">
                <section class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/30 bg-cyan-400/10 px-3 py-1.5 text-xs font-medium uppercase tracking-[0.22em] text-cyan-200">
                            <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                            Cours de protocole de sécurité
                        </div>

                        <div class="space-y-5">
                            <h1 class="max-w-xl text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl">
                                TP Protocole
                                <span class="block bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-300 bg-clip-text text-transparent">
                                    sécurité et confiance numérique
                                </span>
                            </h1>

                            <p class="max-w-xl text-lg leading-8 text-slate-300">
                                Une plateforme d’apprentissage dédiée à la protection des systèmes, à la gestion des accès et à la sécurisation des échanges numériques.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-cyan-400 to-sky-500 px-6 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/30 transition hover:scale-[1.02]">
                                Accéder au TP
                            </a>
                            <a href="#objectifs" class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                Découvrir les objectifs
                            </a>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                                <p class="text-2xl font-bold text-white">8</p>
                                <p class="mt-1 text-sm text-slate-300">modules clés</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                                <p class="text-2xl font-bold text-white">24/7</p>
                                <p class="mt-1 text-sm text-slate-300">accès sécurisé</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                                <p class="text-2xl font-bold text-white">100%</p>
                                <p class="mt-1 text-sm text-slate-300">protection</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-10 top-10 h-32 w-32 rounded-full bg-cyan-500/20 blur-3xl"></div>
                        <div class="absolute -right-6 bottom-8 h-28 w-28 rounded-full bg-indigo-500/20 blur-3xl"></div>

                        <div class="relative overflow-hidden rounded-[28px] border border-white/10 bg-slate-900/80 p-6 shadow-[0_40px_90px_-25px_rgba(56,189,248,0.4)] backdrop-blur-xl">
                            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Sécurisation</p>
                                    <h2 class="mt-2 text-xl font-semibold text-white">Session active</h2>
                                </div>
                                <span class="rounded-full border border-emerald-400/30 bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-300">
                                    Secure
                                </span>
                            </div>

                            <div class="mt-6 space-y-5">
                                <div class="rounded-2xl border border-sky-400/20 bg-sky-500/10 p-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-sky-200">Authentification</span>
                                        <span class="text-sm font-semibold text-cyan-300">98%</span>
                                    </div>
                                    <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-800">
                                        <div class="h-full w-[98%] rounded-full bg-gradient-to-r from-cyan-400 to-sky-500"></div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                                        <span class="text-sm text-slate-300">TLS / HTTPS</span>
                                        <span class="text-sm font-medium text-emerald-300">OK</span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                                        <span class="text-sm text-slate-300">Contrôle d’accès</span>
                                        <span class="text-sm font-medium text-cyan-300">Actif</span>
                                    </div>
                                    <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                                        <span class="text-sm text-slate-300">Protection des données</span>
                                        <span class="text-sm font-medium text-violet-300">Niveau 3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="objectifs" class="mt-24">
                    <div class="mb-8 max-w-2xl">
                        <p class="text-sm font-medium uppercase tracking-[0.22em] text-cyan-300">Objectifs</p>
                        <h2 class="mt-3 text-3xl font-bold text-white sm:text-4xl">Comprendre les mécanismes de sécurité</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-3">
                        <article class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-lg shadow-slate-950/20">
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500/15 text-xl text-cyan-300">01</div>
                            <h3 class="text-xl font-semibold text-white">Authentification</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-300">
                                Étudier les méthodes de vérification d’identité et les bonnes pratiques pour sécuriser les accès.
                            </p>
                        </article>

                        <article class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-lg shadow-slate-950/20">
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500/15 text-xl text-indigo-300">02</div>
                            <h3 class="text-xl font-semibold text-white">Chiffrement</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-300">
                                Explorer les protocoles de protection des données et les mécanismes de confidentialité numérique.
                            </p>
                        </article>

                        <article class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-lg shadow-slate-950/20">
                            <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-violet-500/15 text-xl text-violet-300">03</div>
                            <h3 class="text-xl font-semibold text-white">Protection</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-300">
                                Mettre en place des contours de défense efficaces pour limiter les risques et les vulnérabilités.
                            </p>
                        </article>
                    </div>
                </section>

                <section class="mt-24 rounded-[32px] border border-white/10 bg-gradient-to-br from-slate-900 via-slate-900 to-sky-950/70 p-8 shadow-[0_40px_80px_-25px_rgba(14,165,233,0.3)]">
                    <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                        <div>
                            <p class="text-sm font-medium uppercase tracking-[0.22em] text-cyan-300">Le projet</p>
                            <h2 class="mt-3 text-3xl font-bold text-white">Un TP structuré pour maîtriser les fondamentaux</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-sm font-medium text-sky-300">1. Analyse des risques</p>
                                <p class="mt-2 text-sm leading-7 text-slate-300">Identifier les vulnérabilités et comprendre les impacts sur les systèmes d’information.</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-sm font-medium text-sky-300">2. Mise en place des protocoles</p>
                                <p class="mt-2 text-sm leading-7 text-slate-300">Appliquer les bonnes pratiques de sécurisation dans un cadre concret et pédagogique.</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-sm font-medium text-sky-300">3. Suivi et validation</p>
                                <p class="mt-2 text-sm leading-7 text-slate-300">Vérifier les résultats, mesurer la sécurité et assurer la conformité des accès.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="border-t border-white/10 bg-slate-950/80">
                <div class="mx-auto flex max-w-6xl flex-col gap-3 px-4 py-8 text-sm text-slate-400 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                    <p>© {{ date('Y') }} TP Protocole</p>
                    <p>Protocoles de sécurité • Authentification • Protection des données</p>
                </div>
            </footer>
        </div>
    </body>
</html>
