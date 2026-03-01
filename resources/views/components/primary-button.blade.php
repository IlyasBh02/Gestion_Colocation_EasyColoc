<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-gradient-to-tr from-indigo-600 to-purple-700 border border-transparent rounded-xl font-bold text-sm text-white tracking-wide hover:from-indigo-500 hover:to-purple-600 active:scale-95 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-lg shadow-indigo-500/20 transition-all duration-200']) }}>
    {{ $slot }}
</button>
