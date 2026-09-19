<x-layouts::auth :title="__('Log in')">
    <div class="grid overflow-hidden lg:grid-cols-[1.05fr_0.95fr]">
        <div class="relative overflow-hidden bg-slate-950 p-8 text-white md:p-10 lg:p-12">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.42),transparent_24%),radial-gradient(circle_at_bottom_right,_rgba(34,211,238,0.35),transparent_30%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(15,23,42,0.25),rgba(15,23,42,0.8))]"></div>

            <div class="relative z-10 flex h-full flex-col justify-between gap-10">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/20 bg-white/10 shadow-lg shadow-cyan-500/10 backdrop-blur-sm">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                    </span>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.28em] text-slate-300">TP Protocole</p>
                        <p class="text-sm text-slate-300">Plateforme sécurisée</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <span class="inline-flex items-center rounded-full border border-cyan-400/30 bg-cyan-400/10 px-3 py-1 text-xs font-medium tracking-[0.2em] text-cyan-200 uppercase">
                        Accès sécurisé
                    </span>
                    <h2 class="max-w-md text-3xl font-semibold tracking-tight text-white md:text-4xl">
                        Un espace moderne pour piloter vos protocoles.
                    </h2>
                    <p class="max-w-md text-base leading-7 text-slate-300">
                        Gérez vos opérations, vos équipes et vos performances depuis une interface claire, rapide et élégante.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 text-xs text-slate-200">
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Sécurité</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Suivi</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Collaboration</span>
                </div>
            </div>
        </div>

        <div class="auth-surface bg-white p-6 sm:p-8 lg:p-10">
            <div class="flex flex-col gap-6">
                <div class="space-y-2">
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-sky-700">Bienvenue</p>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Connexion</h1>
                    <p class="text-sm text-slate-700">Entrez vos identifiants pour accéder à votre espace.</p>
                </div>

                <x-auth-session-status class="text-center" :status="session('status')" />

                <x-passkey-verify />

                <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
                    @csrf

                    <flux:input
                        name="email"
                        :label="__('Email address')"
                        :value="old('email')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="email@example.com"
                        class="rounded-xl border-slate-200 bg-slate-50/80 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-sky-500/20"
                    />

                    <div class="space-y-2">
                        <flux:input
                            name="password"
                            :label="__('Password')"
                            type="password"
                            required
                            autocomplete="current-password"
                            :placeholder="__('Password')"
                            viewable
                            class="rounded-xl border-slate-200 bg-slate-50/80 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-sky-500/20"
                        />

                        @if (Route::has('password.request'))
                            <div class="flex justify-end">
                                <flux:link class="text-sm font-medium text-sky-700 hover:text-sky-800" :href="route('password.request')" wire:navigate>
                                    {{ __('Forgot your password?') }}
                                </flux:link>
                            </div>
                        @endif
                    </div>

                    <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" class="text-slate-800" />

                    <flux:button variant="primary" type="submit" class="w-full rounded-xl bg-slate-950 px-4 py-3 text-base font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800" data-test="login-button">
                        {{ __('Log in') }}
                    </flux:button>
                </form>

                <div class="space-x-1 text-center text-sm text-slate-700 rtl:space-x-reverse">
                    <span>{{ __('Don\'t have an account?') }}</span>
                    <flux:link :href="route('register')" wire:navigate class="font-semibold text-sky-700 hover:text-sky-800">
                        {{ __('Sign up') }}
                    </flux:link>
                </div>
            </div>
        </div>
    </div>
</x-layouts::auth>
