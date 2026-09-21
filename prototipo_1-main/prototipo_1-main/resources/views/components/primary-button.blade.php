<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex w-full items-center justify-center rounded-xl bg-forest-950 px-6 py-3.5 text-sm font-bold uppercase tracking-[0.12em] text-white shadow-lg shadow-forest-950/25 transition-all duration-300 hover:bg-forest-900 hover:shadow-xl hover:shadow-forest-950/30 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-forest-600/40 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
