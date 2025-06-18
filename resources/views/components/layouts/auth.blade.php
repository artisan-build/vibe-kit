<x-dynamic-component :component="'layouts.' . config('theme.auth_layout')" :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-dynamic-component>
