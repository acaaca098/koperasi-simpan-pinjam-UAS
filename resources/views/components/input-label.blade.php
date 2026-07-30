@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#0d211d] mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>