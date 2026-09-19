<x-layouts::app :title="__('Admin dashboard')">
    <div class="min-h-screen bg-slate-950 px-4 py-6 text-slate-100 lg:px-6">
        <div class="mx-auto max-w-7xl space-y-6">
            <div class="flex flex-wrap items-center gap-3 pb-2">
                <a href="{{ route('dashboard') }}" class="rounded-full border border-sky-500/30 bg-sky-500/10 px-4 py-2 text-sm font-medium text-sky-200">
                    User
                </a>
                <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-violet-500/30 bg-violet-500/10 px-4 py-2 text-sm font-medium text-violet-200 shadow-[0_0_18px_rgba(167,139,250,0.18)]">
                    Admin
                </a>
            </div>

            <div class="relative overflow-hidden rounded-[28px] border border-violet-500/20 bg-[radial-gradient(circle_at_top_left,_rgba(168,85,247,0.18),transparent_25%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.12),transparent_30%),linear-gradient(135deg,#0f172a_0%,#111827_35%,#020617_100%)] p-6 shadow-[0_30px_80px_-30px_rgba(168,85,247,0.35)] sm:p-8">
                <div class="absolute inset-0 opacity-40 [background-image:linear-gradient(rgba(148,163,184,0.07)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.07)_1px,transparent_1px)] [background-size:26px_26px]"></div>

                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-full border border-violet-400/25 bg-violet-500/10 px-3 py-1 text-[10px] font-medium uppercase tracking-[0.22em] text-violet-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_12px_rgba(52,211,153,0.8)]"></span>
                            Accès admin
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.22em] text-slate-300">Zone sécurisée</p>
                            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-white md:text-4xl">Admin dashboard</h1>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-white/10">
                            Audit
                        </button>
                        <button class="rounded-xl bg-violet-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-violet-500/30 transition hover:bg-violet-400">
                            Actions critiques
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <span>Sessions</span>
                        <span class="rounded-full bg-violet-500/10 px-2 py-1 text-[10px] font-medium text-violet-300">+9%</span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold text-white">482</p>
                    <p class="mt-2 text-sm text-slate-400">Connexions actives</p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <span>Rôles</span>
                        <span class="rounded-full bg-emerald-500/10 px-2 py-1 text-[10px] font-medium text-emerald-300">OK</span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold text-white">12</p>
                    <p class="mt-2 text-sm text-slate-400">Permissions gérées</p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <span>Logs</span>
                        <span class="rounded-full bg-sky-500/10 px-2 py-1 text-[10px] font-medium text-sky-300">2.1k</span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold text-white">1.6k</p>
                    <p class="mt-2 text-sm text-slate-400">Événements enregistrés</p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30">
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <span>Risques</span>
                        <span class="rounded-full bg-rose-500/10 px-2 py-1 text-[10px] font-medium text-rose-300">3</span>
                    </div>
                    <p class="mt-4 text-3xl font-semibold text-white">03</p>
                    <p class="mt-2 text-sm text-slate-400">Alertes critiques</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-5 shadow-lg shadow-slate-950/30 sm:p-6">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-slate-400">Sécurité</p>
                        <h2 class="mt-2 text-xl font-semibold text-white">Contrôle d’administration</h2>
                    </div>
                    <span class="rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-300">Sécurisé</span>
                </div>

                <div class="grid gap-4 lg:grid-cols-3">
                    <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                        <p class="text-sm text-slate-300">Mises à jour</p>
                        <p class="mt-3 text-2xl font-semibold text-white">12</p>
                        <p class="mt-2 text-xs text-slate-400">À valider cette semaine</p>
                    </div>
                    <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                        <p class="text-sm text-slate-300">Utilisateurs bloqués</p>
                        <p class="mt-3 text-2xl font-semibold text-white">08</p>
                        <p class="mt-2 text-xs text-slate-400">Sessions non conformes</p>
                    </div>
                    <div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
                        <p class="text-sm text-slate-300">Policy score</p>
                        <p class="mt-3 text-2xl font-semibold text-white">A+</p>
                        <p class="mt-2 text-xs text-slate-400">Conformité globale</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
