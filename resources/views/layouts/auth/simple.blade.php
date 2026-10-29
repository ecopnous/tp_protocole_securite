<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="auth-theme min-h-screen antialiased">
        <div class="auth-shell relative isolate flex min-h-svh items-center justify-center overflow-hidden px-4 py-8 sm:px-6 lg:px-8">
            <div class="auth-backdrop absolute inset-0"></div>
            <div class="auth-grid absolute inset-0 opacity-35"></div>
            <button
                type="button"
                class="auth-theme-toggle absolute right-4 top-4 z-20 inline-flex items-center gap-2 rounded-full border px-3 py-2 text-xs font-semibold shadow-sm transition sm:right-6 sm:top-6"
                aria-label="Changer le thème"
                x-data
                x-on:click="$flux.appearance = $flux.appearance === 'dark' ? 'light' : 'dark'"
            >
                <span aria-hidden="true" x-text="$flux.appearance === 'dark' ? '☀' : '☾'"></span>
                <span x-text="$flux.appearance === 'dark' ? 'Mode clair' : 'Mode sombre'"></span>
            </button>

            <div class="auth-card relative z-10 w-full max-w-5xl overflow-hidden rounded-[32px] backdrop-blur-xl">
                {{ $slot }}
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
