@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full rounded-lg border-[#d7e4df] bg-white text-[#0d211d] placeholder:text-[#1f4a42]/40 focus:border-[#163832] focus:ring-[#163832] text-sm py-2.5 px-3.5 shadow-sm']) }}>