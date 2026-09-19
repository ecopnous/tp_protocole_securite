<x-layouts::auth :title="__('Reset password')">
    <div class="grid overflow-hidden lg:grid-cols-[1.04fr_0.96fr]">
        <div class="relative overflow-hidden bg-slate-950 p-8 text-white md:p-10 lg:p-12">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.32),transparent_24%),radial-gradient(circle_at_bottom_right,_rgba(99,102,241,0.22),transparent_32%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(15,23,42,0.12),rgba(15,23,42,0.82))]"></div>

            <div class="relative z-10 flex h-full flex-col justify-between gap-10">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/20 bg-white/10 shadow-lg shadow-cyan-500/10 backdrop-blur-sm">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                    </span>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.28em] text-slate-300">TP Protocole</p>
                        <p class="text-sm text-slate-300">Nouvelle sécurité</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <span class="inline-flex items-center rounded-full border border-cyan-400/30 bg-cyan-400/10 px-3 py-1 text-[10px] font-medium uppercase tracking-[0.24em] text-cyan-200">
                        Mot de passe
                    </span>
                    <h2 class="max-w-md text-3xl font-semibold tracking-tight text-white md:text-4xl">
                        Définissez un mot de passe robuste pour sécuriser votre compte.
                    </h2>
                    <p class="max-w-md text-base leading-7 text-slate-300">
                        Choisissez une combinaison forte pour renforcer la protection de vos accès et de vos données.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 text-xs text-slate-200">
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Renforcement</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Confidentialité</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Fiabilité</span>
                </div>
            </div>
        </div>

        <div class="auth-surface bg-white p-6 sm:p-8 lg:p-10">
            <div class="flex flex-col gap-6">
                <div class="space-y-2">
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-sky-700">Sécurité</p>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Nouveau mot de passe</h1>
                    <p class="text-sm text-slate-700">Merci de renseigner le nouveau mot de passe ci-dessous.</p>
                </div>

                <x-auth-session-status class="text-center" :status="session('status')" />

                <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">

                    <flux:input
                        name="email"
                        value="{{ request('email') }}"
                        :label="__('Email')"
                        type="email"
                        required
                        autocomplete="email"
                        class="rounded-xl border-slate-200 bg-slate-50/80 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-sky-500/20"
                    />

                    <flux:input
                        name="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="new-password"
                        :placeholder="__('Password')"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                        viewable
                        class="rounded-xl border-slate-200 bg-slate-50/80 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-sky-500/20"
                    />

                    <flux:input
                        name="password_confirmation"
                        :label="__('Confirm password')"
                        type="password"
                        required
                        autocomplete="new-password"
                        :placeholder="__('Confirm password')"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                        viewable
                        class="rounded-xl border-slate-200 bg-slate-50/80 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-sky-500/20"
                    />

                    <flux:button type="submit" variant="primary" class="w-full rounded-xl bg-slate-950 px-4 py-3 text-base font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800" data-test="reset-password-button">
                        {{ __('Reset password') }}
                    </flux:button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::auth>
