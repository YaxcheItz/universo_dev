<div class="card p-6 flex flex-col items-start justify-between">
    <p class="text-universo-text-muted text-sm">{{ $title }}</p>
    <p class="text-universo-text text-3xl font-bold mt-2">{{ $value }}</p>
    <div class="ml-auto mt-4">
        <svg class="w-8 h-8 {{ $iconColor ?? 'text-universo-purple' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    </div>
</div>