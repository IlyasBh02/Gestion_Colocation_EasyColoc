<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-gradient-to-tr from-rose-600 to-red-700 border border-transparent rounded-xl font-bold text-sm text-white tracking-wide hover:from-rose-500 hover:to-red-600 active:scale-95 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg shadow-rose-500/20 transition-all duration-200']) }}>
    {{ $slot }}
</button>
