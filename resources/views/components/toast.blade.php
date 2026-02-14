<div x-data="{
    show: false,
    message: '',
    type: 'success',
    colors: {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-orange-500 text-white',
        info: 'bg-blue-500 text-white'
    },
    icons: {
        success: 'check_circle',
        error: 'error',
        warning: 'warning',
        info: 'info'
    }
}" x-init="$watch('show', value => {
    if (value) {
        setTimeout(() => show = false, 3000)
    }
})"
    x-on:success.window="message = $event.detail[0].message; type = 'success'; show = true"
    x-on:error.window="message = $event.detail[0].message; type = 'error'; show = true"
    x-on:show-toast.window="message = $event.detail.message; type = $event.detail.type || 'success'; show = true"
    class="fixed bottom-4 right-4 z-[100] max-w-sm w-full" x-show="show"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" style="display: none;">
    <div :class="`rounded-xl shadow-lg p-4 flex items-center gap-3 ${colors[type]}`">
        <span class="material-symbols-outlined" x-text="icons[type]"></span>
        <p class="font-bold text-sm" x-text="message"></p>
        <button x-on:click="show = false" class="ml-auto hover:bg-white/20 rounded-full p-1 transition-colors">
            <span class="material-symbols-outlined text-sm">close</span>
        </button>
    </div>
</div>
