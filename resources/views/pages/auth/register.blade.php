<x-layouts::auth :title="__('Register')">
    <div class="grid overflow-hidden lg:grid-cols-[1.06fr_0.94fr]">
        <div class="relative overflow-hidden bg-slate-950 p-8 text-white md:p-10 lg:p-12">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.36),transparent_24%),radial-gradient(circle_at_bottom_right,_rgba(34,211,238,0.28),transparent_34%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(15,23,42,0.12),rgba(15,23,42,0.82))]"></div>

            <div class="relative z-10 flex h-full flex-col justify-between gap-10">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/20 bg-white/10 shadow-lg shadow-cyan-500/10 backdrop-blur-sm">
                        <x-app-logo-icon class="size-6 fill-current text-white" />
                    </span>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.28em] text-slate-300">TP Protocole</p>
                        <p class="text-sm text-slate-300">Accès sécurisé</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <span class="inline-flex items-center rounded-full border border-cyan-400/30 bg-cyan-400/10 px-3 py-1 text-[10px] font-medium uppercase tracking-[0.24em] text-cyan-200">
                        Nouveau compte
                    </span>
                    <h2 class="max-w-md text-3xl font-semibold tracking-tight text-white md:text-4xl">
                        Créez votre espace pour sécuriser vos activités.
                    </h2>
                    <p class="max-w-md text-base leading-7 text-slate-300">
                        Gérez vos accès, vos protocoles et votre sécurité avec une solution moderne, fiable et claire.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 text-xs text-slate-200">
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Sécurité</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Authentification</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Protection</span>
                </div>
            </div>
        </div>

        <div class="auth-surface bg-white p-6 sm:p-8 lg:p-10">
            <div class="flex flex-col gap-6">
                <div class="space-y-2">
                    <p class="text-sm font-medium uppercase tracking-[0.2em] text-sky-700">Bienvenue</p>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Créer un compte</h1>
                    <p class="text-sm text-slate-700">Renseignez vos informations pour accéder à votre espace.</p>
                </div>

                <x-auth-session-status class="text-center" :status="session('status')" />

                <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
                    @csrf

                    <flux:input
                        name="name"
                        :label="__('Name')"
                        :value="old('name')"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                        :placeholder="__('Full name')"
                        class="rounded-xl border-slate-200 bg-slate-50/80 text-slate-900 placeholder:text-slate-400 focus:border-sky-500 focus:ring-sky-500/20"
                    />

                    <flux:input
                        name="email"
                        :label="__('Email address')"
                        :value="old('email')"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="email@example.com"
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

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Type de compte</label>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 transition hover:border-sky-300 hover:bg-sky-50">
                                <input type="radio" name="role" value="user" checked class="h-4 w-4 border-slate-300 text-sky-600 focus:ring-sky-500">
                                <span class="text-sm font-medium text-slate-700">User</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 transition hover:border-violet-300 hover:bg-violet-50">
                                <input type="radio" name="role" value="admin" class="h-4 w-4 border-slate-300 text-violet-600 focus:ring-violet-500">
                                <span class="text-sm font-medium text-slate-700">Admin</span>
                            </label>
                        </div>
                    </div>

                    <flux:button type="submit" variant="primary" class="w-full rounded-xl bg-slate-950 px-4 py-3 text-base font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800" data-test="register-user-button">
                        {{ __('Create account') }}
                    </flux:button>
                </form>

                <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-slate-700">
                    <span>{{ __('Already have an account?') }}</span>
                    <flux:link :href="route('login')" wire:navigate class="font-semibold text-sky-700 hover:text-sky-800">{{ __('Log in') }}</flux:link>
                </div>
            </div>
        </div>
    </div>
</x-layouts::auth>
