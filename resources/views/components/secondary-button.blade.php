<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-2.5 bg-white/50 backdrop-blur-md border border-gray-200/50 rounded-xl font-bold text-sm text-gray-700 tracking-wide hover:bg-white/80 active:scale-95 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-sm transition-all duration-200']) }}>
    {{ $slot }}
</button>
