<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
    <head>
        @include('partials.head')
    </head>
    <body>
        <flux:header container class="[&>div]:max-w-2xl! min-h-18">
            <flux:brand href="route('dashboard')" name="Flowpilot" class="[&>div]:first:hidden" />

            <flux:spacer />

            <div class="flex gap-2">
                @guest
                    <flux:button :href="route('login')" variant="ghost" size="sm">{{ __('Sign in') }}</flux:button>
                    <flux:button :href="route('register')" size="sm">{{ __('Get Started') }}</flux:button>
                @else
                    <flux:button :href="route('dashboard')" size="sm">{{ __('Dashboard') }}</flux:button>
                @endguest
            </div>
        </flux:header>

        <flux:main class="[:where(&)]:max-w-2xl!" container>
            <flux:heading level="1" size="xl" class="font-serif">Share Links Instantly—Shorten Any URL</flux:heading>

            <flux:text variant="strong" size="lg" class="mt-6">
                Create short, easy-to-share links from any destination URL. Simplify sharing and make your links memorable—all in seconds.
            </flux:text>

            <flux:button :href="route('register')" variant="primary" class="mt-6">{{ __('Get Started') }}</flux:button>

            <div class="mt-64 flex items-center justify-between">
                <flux:text variant="subtle" class="flex items-center gap-2">
                    <x-app-logo-icon class="size-4" />
                    <span><strong>flowpilot</strong>.com</span>
                </flux:text>
                <flux:text variant="subtle">by <strong>Oliver Servín</strong></flux:text>
            </div>
        </flux:main>
    </body>
</html>
