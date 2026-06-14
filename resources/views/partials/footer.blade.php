<footer class="mt-auto border-t border-brandBlue/10 bg-white/70 backdrop-blur">
    <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-5 text-sm text-brandInk/70 sm:flex-row sm:items-center sm:justify-between lg:px-6">
        <p>&copy; {{ now()->year }} FA-DEV. Todos os direitos reservados.</p>

        <div class="flex items-center gap-4">
            <a href="{{ route('legal.terms') }}" class="transition hover:text-brandOrange">Termos</a>
            <a href="{{ route('legal.privacy') }}" class="transition hover:text-brandOrange">Privacidade</a>
        </div>
    </div>
</footer>
