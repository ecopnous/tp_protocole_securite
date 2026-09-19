<x-layouts::app :title="__('Dashboard')">
    <div class="min-h-screen bg-slate-950 px-4 py-6 text-slate-100 lg:px-6">
        <div class="mx-auto max-w-7xl space-y-6">
            @if (session('error'))
                <div class="rounded-2xl border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm text-rose-200 shadow-lg shadow-rose-900/20">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-wrap items-center gap-3 pb-2">
                <a href="{{ route('dashboard') }}" class="rounded-full border border-sky-500/30 bg-sky-500/10 px-4 py-2 text-sm font-medium text-sky-200 {{ request()->routeIs('dashboard') ? 'shadow-[0_0_18px_rgba(56,189,248,0.18)]' : '' }}">
                    User
                </a>
                <a href="{{ route('admin.dashboard') }}" class="rounded-full border {{ auth()->user()?->isAdmin() ? 'border-violet-500/30 bg-violet-500/10 text-violet-200' : 'border-slate-700 bg-slate-800/70 text-slate-300' }} px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'shadow-[0_0_18px_rgba(167,139,250,0.18)]' : '' }}">
                    Admin
                </a>
            </div>

            <div class="relative overflow-hidden rounded-[28px] border border-sky-500/20 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.18),transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(34,211,238,0.14),transparent_30%),linear-gradient(135deg,#0f172a_0%,#111827_35%,#020617_100%)] p-6 shadow-[0_30px_80px_-30px_rgba(14,165,233,0.35)] sm:p-8">
                <div class="absolute inset-0 opacity-40 [background-image:linear-gradient(rgba(148,163,184,0.07)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.07)_1px,transparent_1px)] [background-size:26px_26px]"></div>

                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-full border border-sky-400/25 bg-sky-500/10 px-3 py-1 text-[10px] font-medium uppercase tracking-[0.22em] text-sky-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_12px_rgba(52,211,153,0.8)]"></span>
                            Sécurité active
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.22em] text-slate-300">TP Protocole</p>
                            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-white md:text-4xl">Tableau de bord</h1>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-white/10">
                            Exporter
                        </button>
                        <button class="rounded-xl bg-sky-500 px-4 py-2 text-sm font-semibold text-slate-950 shadow-lg shadow-sky-500/30 transition hover:bg-sky-400">
                            Nouveau protocole
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <span>Alertes</span>
                        <span class="rounded-full bg-rose-500/10 px-2 py-1 text-[10px] font-medium text-rose-300">+12%</span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold text-white">24</p>
                    <p class="mt-2 text-sm text-slate-400">Événements à traiter</p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <span>Utilisateurs</span>
                        <span class="rounded-full bg-emerald-500/10 px-2 py-1 text-[10px] font-medium text-emerald-300">+8%</span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold text-white">1.8k</p>
                    <p class="mt-2 text-sm text-slate-400">Comptes actifs</p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <span>Authentification</span>
                        <span class="rounded-full bg-sky-500/10 px-2 py-1 text-[10px] font-medium text-sky-300">99.9%</span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold text-white">96%</p>
                    <p class="mt-2 text-sm text-slate-400">Taux de réussite</p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <span>Protection</span>
                        <span class="rounded-full bg-violet-500/10 px-2 py-1 text-[10px] font-medium text-violet-300">Secure</span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold text-white">A+</p>
                    <p class="mt-2 text-sm text-slate-400">Score global</p>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.5fr_0.9fr]">
                <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30 sm:p-6">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Activité</p>
                            <h2 class="mt-2 text-xl font-semibold text-white">Surveillance des protocoles</h2>
                        </div>
                        <span class="rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-300">En ligne</span>
                    </div>

                    <div class="space-y-5">
                        <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-200">Authentification MFA</p>
                                    <p class="mt-1 text-xs text-slate-400">Dernière synchronisation il y a 12 min</p>
                                </div>
                                <span class="text-xs font-medium text-emerald-300">Stable</span>
                            </div>
                            <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-800">
                                <div class="h-full w-[84%] rounded-full bg-gradient-to-r from-emerald-400 via-sky-400 to-cyan-300"></div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-200">Audit des accès</p>
                                    <p class="mt-1 text-xs text-slate-400">3 anomalies détectées ce jour</p>
                                </div>
                                <span class="text-xs font-medium text-amber-300">À surveiller</span>
                            </div>
                            <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-800">
                                <div class="h-full w-[68%] rounded-full bg-gradient-to-r from-amber-400 via-orange-400 to-yellow-300"></div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-200">Protection réseau</p>
                                    <p class="mt-1 text-xs text-slate-400">Aucune intrusion détectée</p>
                                </div>
                                <span class="text-xs font-medium text-sky-300">Régulier</span>
                            </div>
                            <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-slate-800">
                                <div class="h-full w-[92%] rounded-full bg-gradient-to-r from-sky-400 via-cyan-400 to-blue-500"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Système</p>
                        <h3 class="mt-3 text-xl font-semibold text-white">Statut des protocoles</h3>

                        <div class="mt-5 space-y-4">
                            <div class="flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/70 px-3 py-2.5">
                                <span class="text-sm text-slate-200">Firewall</span>
                                <span class="rounded-full bg-emerald-500/10 px-2 py-1 text-[10px] font-medium text-emerald-300">OK</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/70 px-3 py-2.5">
                                <span class="text-sm text-slate-200">MFA</span>
                                <span class="rounded-full bg-emerald-500/10 px-2 py-1 text-[10px] font-medium text-emerald-300">OK</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/70 px-3 py-2.5">
                                <span class="text-sm text-slate-200">Historique</span>
                                <span class="rounded-full bg-amber-500/10 px-2 py-1 text-[10px] font-medium text-amber-300">Audit</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Accès rapide</p>
                        <div class="mt-4 space-y-3">
                            <button class="flex w-full items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/80 px-3 py-3 text-left text-sm text-slate-200 transition hover:border-sky-500/40 hover:bg-slate-900">
                                <span>Gestion des comptes</span>
                                <span class="text-sky-300">→</span>
                            </button>
                            <button class="flex w-full items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/80 px-3 py-3 text-left text-sm text-slate-200 transition hover:border-sky-500/40 hover:bg-slate-900">
                                <span>Rapports de sécurité</span>
                                <span class="text-sky-300">→</span>
                            </button>
                            <button class="flex w-full items-center justify-between rounded-2xl border border-slate-800 bg-slate-950/80 px-3 py-3 text-left text-sm text-slate-200 transition hover:border-sky-500/40 hover:bg-slate-900">
                                <span>Paramètres du système</span>
                                <span class="text-sky-300">→</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
