<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-2 bg-gray-50 border border-[#dbe6e1] rounded-xl font-bold text-xs text-[#111815] uppercase tracking-widest hover:bg-gray-100 transition-colors disabled:opacity-50']) }}>
    {{ $slot }}
</button>
