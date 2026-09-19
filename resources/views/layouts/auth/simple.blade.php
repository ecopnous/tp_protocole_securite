<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#edf4ff] antialiased text-slate-900">
        <div class="relative isolate flex min-h-svh items-center justify-center overflow-hidden px-4 py-8 sm:px-6 lg:px-8">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.18),transparent_25%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.16),transparent_30%),linear-gradient(135deg,#f8fbff_0%,#edf4ff_38%,#eef2ff_100%)]"></div>
            <div class="absolute inset-0 opacity-35 [background-image:linear-gradient(rgba(148,163,184,0.08)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.08)_1px,transparent_1px)] [background-size:36px_36px]"></div>

            <div class="relative z-10 w-full max-w-5xl overflow-hidden rounded-[32px] border border-slate-200/80 bg-white/80 shadow-[0_35px_90px_-24px_rgba(15,23,42,0.35)] backdrop-blur-xl">
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
