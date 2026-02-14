<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2 bg-primary border border-transparent rounded-xl font-black text-xs text-gray-900 uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-primary/20 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
