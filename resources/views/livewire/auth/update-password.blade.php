<section class="max-w-xl">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Atualizar Senha') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Certifique-se de que sua conta está usando uma senha longa e aleatória para permanecer segura.') }}
        </p>
    </header>

    <form wire:submit.prevent="update" class="mt-6 space-y-6">
        <div>
            <label for="current_password" class="block font-medium text-sm text-gray-700">
                {{ __('Senha Atual') }}
            </label>
            <input wire:model="current_password" id="current_password" type="password"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                autocomplete="current-password" />
            @error('current_password')
                <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password" class="block font-medium text-sm text-gray-700">
                {{ __('Nova Senha') }}
            </label>
            <input wire:model="password" id="password" type="password"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                autocomplete="new-password" />
            @error('password')
                <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">
                {{ __('Confirmar Senha') }}
            </label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                autocomplete="new-password" />
            @error('password_confirmation')
                <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Salvar') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Salvo.') }}</p>
            @endif
        </div>

        @error('message')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
        @enderror
    </form>
</section>
