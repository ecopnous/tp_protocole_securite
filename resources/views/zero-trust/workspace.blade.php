<x-layouts::app :title="__('Zone protégée')">
    <main class="min-h-screen bg-slate-950 px-4 py-8 text-slate-100 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl space-y-6">
            <a href="{{ route('zero-trust.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-cyan-300 hover:text-cyan-200">← Retour au centre de sécurité</a>

            <section class="rounded-[2rem] border border-emerald-400/25 bg-[radial-gradient(circle_at_top_right,_rgba(16,185,129,0.13),transparent_34%),linear-gradient(135deg,#0f172a_0%,#020617_82%)] p-7 sm:p-10">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-300">Accès accordé après vérification</p>
                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-4xl">Zone protégée</h1>
                <p class="mt-4 max-w-3xl text-sm leading-7 text-slate-300">Votre compte possède un MFA confirmé et votre mot de passe a été récemment confirmé. Cette route a également enregistré l’accès dans le journal de sécurité.</p>
            </section>

            <section class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
                    <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Identité vérifiée</p>
                    <p class="mt-3 text-lg font-semibold text-white">{{ $user->name }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $user->email }}</p>
                </article>
                <article class="rounded-2xl border border-emerald-400/20 bg-emerald-400/[0.04] p-5">
                    <p class="text-xs uppercase tracking-[0.16em] text-slate-500">Contrôles satisfaits</p>
                    <ul class="mt-3 space-y-2 text-sm text-emerald-200">
                        <li>✓ Session authentifiée et adresse e-mail vérifiée</li>
                        <li>✓ Authentification multifacteur activée</li>
                        <li>✓ Mot de passe confirmé récemment</li>
                    </ul>
                </article>
            </section>

            <section class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 sm:p-7">
                <h2 class="text-xl font-semibold text-white">Activité récente de ce compte</h2>
                <ol class="mt-4 divide-y divide-slate-800">
                    @foreach ($recentEvents as $event)
                        <li class="py-3">
                            <p class="text-sm text-slate-200">{{ $event->description }}</p>
                            <time class="mt-1 block text-xs text-slate-500" datetime="{{ $event->created_at }}">{{ \Illuminate\Support\Carbon::parse($event->created_at)->diffForHumans() }}</time>
                        </li>
                    @endforeach
                </ol>
            </section>
        </div>
    </main>
</x-layouts::app>
