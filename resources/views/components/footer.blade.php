<footer class="w-full bg-white border-t border-[#dbe6e1] py-12 px-4 md:px-20 lg:px-40">
    <div class="max-w-[1200px] mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-3">
                <div class="size-6 text-primary">
                    <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path clipRule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fillRule="evenodd"></path>
                    </svg>
                </div>
                <h2 class="text-[#111815] text-lg font-bold">Everest</h2>
            </div>
            <p class="text-gray-500 text-sm">Capacitando pessoas a alcançarem seu auge desde 2024.</p>
        </div>
        <div class="flex gap-8 text-sm font-medium text-gray-600">
            <a class="hover:text-primary transition-colors" href="{{ route('privacy') }}" wire:navigate>Privacidade</a>
            <a class="hover:text-primary transition-colors" href="{{ route('terms') }}" wire:navigate>Termos</a>
            <a class="hover:text-primary transition-colors" href="{{ route('blog') }}" wire:navigate>Blog</a>
            <a class="hover:text-primary transition-colors" href="{{ route('support') }}" wire:navigate>Contato</a>
        </div>
    </div>
    <div class="max-w-[1200px] mx-auto mt-12 pt-8 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-400">© {{ date('Y') }} Everest Technologies Inc. Todos os direitos reservados.</p>
    </div>
</footer>
