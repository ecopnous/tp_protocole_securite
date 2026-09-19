<x-layouts::auth :title="__('Confirm password')">
    <div class="grid overflow-hidden lg:grid-cols-[1.05fr_0.95fr]">
        <div class="relative overflow-hidden bg-slate-950 p-8 text-white md:p-10 lg:p-12">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.32),transparent_22%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.24),transparent_30%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(15,23,42,0.12),rgba(15,23,42,0.82))]"></div>

            <div class="relative z-10 flex h-full flex-col justify-between gap-10">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/20 bg-white/10 shadow-lg shadow-cyan-500/10 backdrop-blur-sm">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                    </span>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.28em] text-slate-300">TP Protocole</p>
                        <p class="text-sm text-slate-300">Zone sécurisée</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <span class="inline-flex items-center rounded-full border border-cyan-400/30 bg-cyan-400/10 px-3 py-1 text-[10px] font-medium uppercase tracking-[0.24em] text-cyan-200">
                        Vérification
                    </span>
                    <h2 class="max-w-md text-3xl font-semibold tracking-tight text-white md:text-4xl">
                        Confirmez votre identité avant de continuer.
                    </h2>
                    <p class="max-w-md text-base leading-7 text-slate-300">
                        Cette zone est protégée. Veuillez confirmer votre mot de passe pour continuer votre session en toute sécurité.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 text-xs text-slate-200">
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Sécurité</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Validation</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Protection</span>
                </div>
            </div>
        </div>

        <div class="auth-surface bg-white p-6 sm:p-8 lg:p-10">
            <div class="flex flex-col gap-6">
                <div class="space-y-2">
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-sky-700">Confirmation</p>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Confirmer le mot de passe</h1>
                    <p class="text-sm text-slate-700">Cette zone nécessite une vérification supplémentaire pour garantir votre sécurité.</p>
                </div>

                <x-auth-session-status class="text-center" :status="session('status')" />

                <x-passkey-verify
                    options-route="passkey.confirm-options"
                    submit-route="passkey.confirm"
                    :label="__('Confirm with passkey')"
                    :loading-label="__('Confirming...')"
                    :separator="__('Or confirm with password')"
                />

                <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-5">
                    @csrf

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

                    <flux:button variant="primary" type="submit" class="w-full rounded-xl bg-slate-950 px-4 py-3 text-base font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800" data-test="confirm-password-button">
                        {{ __('Confirm') }}
                    </flux:button>
                </form>
            </div>
        </div>
    </div>
</x-layouts::auth>
