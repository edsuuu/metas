<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-2 bg-red-600 border border-transparent rounded-xl font-black text-xs text-white uppercase tracking-widest hover:bg-red-700 hover:scale-105 active:scale-95 transition-all shadow-lg shadow-red-500/20 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
