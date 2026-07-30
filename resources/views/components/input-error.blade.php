@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-[#a64b33] space-y-1 mt-1.5']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-start gap-1.5">
                <span aria-hidden="true">&middot;</span>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif