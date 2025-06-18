<x-dynamic-component :component="'layouts.' . config('theme.app_layout')" :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-dynamic-component>
