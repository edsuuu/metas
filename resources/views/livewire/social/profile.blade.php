<main class="max-w-[1100px] mx-auto pb-20">
    <div class="relative bg-white border-x border-b border-gray-100 transition-all duration-300">
        <div class="h-48 md:h-72 w-full overflow-hidden bg-linear-to-r from-emerald-400 via-primary to-blue-400">
        </div>

        <div class="px-6 pb-6 relative">
            <div class="flex flex-col md:flex-row items-start md:items-end gap-6 -mt-16 md:-mt-20">
                <div class="relative group shrink-0 md:mb-6">
                    <div
                        class="size-32 md:size-40 rounded-full border-[5px] border-white overflow-hidden bg-white shadow-xl relative group">
                        <img alt="{{ $profileUser->name }}" class="w-full h-full object-cover"
                            src="{{ $profileUser->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($profileUser->name) . '&size=200' }}" />
                        @if ($isOwnProfile)
                            <div onclick="document.getElementById('avatar-upload').click()"
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                <span class="material-symbols-outlined text-white text-2xl">photo_camera</span>
                                <input id="avatar-upload" type="file" wire:model="avatar" class="hidden"
                                    accept="image/*" />
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-1 pb-2 space-y-3 w-full md:w-auto">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl md:text-3xl font-extrabold uppercase tracking-tighter">
                            {{ $profileUser->name }}
                        </h1>
                        <p class="text-gray-500 font-medium">
                            @ {{ $profileUser->nickname ?: str_replace(' ', '_', strtolower($profileUser->name)) }}</p>
                        @if ($isOwnProfile)
                            <a href="{{ route('profile.edit') }}"
                                class="ml-2 text-xs font-bold text-gray-400 hover:text-primary transition-colors flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">settings</span> Editar Perfil
                            </a>
                        @endif
                    </div>
                    <div
                        class="bg-gray-50 rounded-2xl p-4 flex flex-col md:flex-row items-center gap-4 border border-[#dbe6e1] w-full">
                        <div class="flex items-center gap-3 min-w-fit">
                            <div
                                class="size-12 rounded-xl bg-gray-900 flex items-center justify-center text-white shadow-sm shrink-0">
                                <span class="text-xs font-black">Lvl {{ $xpInfo['level'] }}</span>
                            </div>
                        </div>
                        <div class="flex-1 w-full space-y-1.5">
                            <div class="flex justify-between items-end">
                                <span class="text-[11px] font-extrabold text-gray-500">{{ $xpInfo['levelXp'] }} /
                                    {{ $xpInfo['levelTarget'] }} XP</span>
                                <span class="text-[11px] font-black text-primary">Nível {{ $xpInfo['level'] + 1 }} em
                                    {{ $xpInfo['levelTarget'] - $xpInfo['levelXp'] }} XP</span>
                            </div>
                            <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-linear-to-r from-primary to-green-400 rounded-full shadow-[0_0_10px_rgba(19,236,146,0.3)] transition-all duration-1000"
                                    style="width: {{ $xpInfo['progressPercentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (!$isOwnProfile)
                    <div class="flex items-center gap-2 mb-2">
                        <button wire:click="toggleFollow"
                            class="font-extrabold px-6 py-2.5 rounded-full transition-all shadow-lg shadow-primary/20 {{ $isFollowing ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-primary hover:bg-emerald-400 text-black' }}">
                            {{ $isFollowing ? 'Parar de Seguir' : 'Adicionar amigo' }}
                        </button>
                    </div>
                @endif
            </div>
            <div class="mt-6 flex flex-wrap gap-6 border-t border-gray-50 pt-6">
                <div class="flex gap-1 items-center">
                    <span class="font-bold">{{ $stats['followers'] }}</span>
                    <span class="text-gray-500 text-sm">Seguidores</span>
                </div>
                <div class="flex gap-1 items-center">
                    <span class="font-bold">{{ $stats['following'] }}</span>
                    <span class="text-gray-500 text-sm">Seguindo</span>
                </div>
                <div class="flex gap-1 items-center">
                    <span class="font-bold">{{ $profileUser->goals_count ?: 0 }}</span>
                    <span class="text-gray-500 text-sm">Metas Batidas</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid de Conteúdo --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8 px-4 md:px-0">
        {{-- Feed Column --}}
        <div class="lg:col-span-8 space-y-6 order-2 lg:order-1">
            <h3 class="text-lg font-black flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-primary">dynamic_feed</span>
                Feed de Metas
            </h3>

            {{-- Skeleton Loading --}}
            <div wire:loading.delay wire:target="render, toggleFollow, toggleLike, submitComment" class="space-y-6">
                @if ($posts->isEmpty())
                    <x-social.post-skeleton />
                    <x-social.post-skeleton />
                @endif
            </div>

            <div class="space-y-6">
                @forelse ($posts as $post)
                    <article
                        class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm transition-all hover:shadow-md">
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded-full overflow-hidden border border-primary shrink-0">
                                    <img alt="{{ $post->user->name }}" class="w-full h-full object-cover"
                                        src="{{ $post->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}" />
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="font-bold text-sm">{{ $post->user->name }}</p>
                                        @if ($post->type === 'goal_completed')
                                            <span class="text-xs text-gray-400">concluiu uma meta</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 flex items-center gap-1">
                                        <span class="material-symbols-outlined" style="font-size: 12px">schedule</span>
                                        {{ $post->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            @if (!($post->user_id == Auth::id() && ($post->type === 'goal_completed' || $post->type === 'streak_maintained')))
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100">
                                            <span class="material-symbols-outlined">more_horiz</span>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        @if ($post->user_id == Auth::id())
                                            @if (!($post->type === 'goal_completed' || $post->type === 'streak_maintained'))
                                                <button
                                                    wire:click="$set('editingPostId', {{ $post->id }}); $set('editContent', '{{ addslashes($post->content) }}')"
                                                    class="block w-full px-4 py-2 text-start text-sm font-bold text-gray-700 hover:bg-gray-100">
                                                    Editar
                                                </button>
                                                <button wire:click="$set('deletingPostId', {{ $post->id }})"
                                                    class="block w-full px-4 py-2 text-start text-sm font-bold text-red-600 hover:bg-gray-100">
                                                    Excluir
                                                </button>
                                            @endif
                                        @else
                                            <button wire:click="hidePost({{ $post->id }})"
                                                class="block w-full px-4 py-2 text-start text-sm font-bold text-gray-700 hover:bg-gray-100">
                                                Ocultar da Timeline
                                            </button>
                                            <button wire:click="$set('reportingPostId', {{ $post->id }})"
                                                class="block w-full px-4 py-2 text-start text-sm font-bold text-orange-600 hover:bg-gray-100">
                                                Denunciar Postagem
                                            </button>
                                            @if ($isAdmin)
                                                <button wire:click="$set('deletingPostId', {{ $post->id }})"
                                                    class="block w-full px-4 py-2 text-start text-sm font-bold text-red-600 border-t border-gray-100">
                                                    Excluir (Staff)
                                                </button>
                                            @endif
                                        @endif
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                        <div class="px-4 pb-2">
                            @if ($post->type === 'goal_completed')
                                <div
                                    class="bg-emerald-50 px-3 py-2 rounded-xl mb-3 flex items-center gap-2 border border-emerald-100">
                                    <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wide">Meta
                                        Batida</span>
                                </div>
                            @endif
                            <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $post->content }}</p>
                        </div>

                        @if ($post->files->isNotEmpty())
                            <div class="mt-3 aspect-video bg-gray-100 overflow-hidden border-y border-gray-50">
                                <img src="{{ route('files.show', $post->files->first()->uuid) }}" alt="Post Content"
                                    class="w-full h-full object-cover cursor-pointer hover:scale-[1.02] transition-transform"
                                    wire:click="$set('fullscreenImageUrl', '{{ route('files.show', $post->files->first()->uuid) }}'); $set('fullscreenPostId', {{ $post->id }})" />
                            </div>
                        @endif

                        <div class="p-4 border-t border-gray-50">
                            <div class="flex items-center gap-4">
                                <button wire:click="toggleLike({{ $post->id }})"
                                    class="flex items-center gap-1.5 transition-all hover:scale-110 {{ $post->is_liked ? 'text-secondary font-bold' : 'text-gray-500 font-bold' }}">
                                    <span class="material-symbols-outlined {{ $post->is_liked ? 'fill-1' : '' }}"
                                        style="font-size: 12px !important;">favorite</span>
                                    <span class="text-sm">{{ $post->likes_count ?: 0 }}</span>
                                </button>
                                <button wire:click="toggleComments({{ $post->id }})"
                                    class="flex items-center gap-1.5 text-gray-500 hover:text-primary transition-colors font-bold">
                                    <span class="material-symbols-outlined"
                                        style="font-size: 12px !important;">chat_bubble</span>
                                    <span class="text-sm">{{ $post->comments_count ?: 0 }}</span>
                                </button>
                            </div>

                            <div class="space-y-4 pt-4 mt-4 border-t border-gray-100">
                                @if ($post->comments_count > 0)
                                    <div class="space-y-3 mb-4">
                                        @foreach ($post->comments as $comment)
                                            <div class="flex gap-2">
                                                <a href="{{ route('social.profile', $comment->user->nickname ?: $comment->user->id) }}"
                                                    class="size-6 rounded-full overflow-hidden shrink-0 mt-0.5"
                                                    wire:navigate>
                                                    <img src="{{ $comment->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                                                        alt="" class="w-full h-full object-cover" />
                                                </a>
                                                <div class="flex-1 bg-gray-50 p-2 rounded-xl">
                                                    <div class="flex justify-between items-center mb-0.5">
                                                        <a href="{{ route('social.profile', $comment->user->nickname ?: $comment->user->id) }}"
                                                            class="text-[10px] font-black hover:underline uppercase tracking-tighter"
                                                            wire:navigate>
                                                            {{ $comment->user->name }}
                                                        </a>
                                                        <span
                                                            class="text-[8px] text-gray-400 font-bold">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs text-gray-700 font-medium">
                                                        {{ $comment->content }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex gap-3 items-center">
                                    <div class="size-8 rounded-lg overflow-hidden shrink-0">
                                        <img src="{{ Auth::user()->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                            alt="" class="w-full h-full object-cover" />
                                    </div>
                                    <div class="flex-1 flex gap-2 items-center">
                                        <div class="flex-1">
                                            <x-text-input wire:model.live="commentContent.{{ $post->id }}"
                                                wire:keydown.enter="submitComment({{ $post->id }})"
                                                placeholder="Incentive com um comentário..."
                                                class="h-10! text-xs! rounded-xl! shadow-sm" />
                                        </div>
                                        <button wire:click="submitComment({{ $post->id }})"
                                            @disabled(empty($commentContent[$post->id] ?? ''))
                                            class="bg-primary size-10 rounded-xl flex items-center justify-center text-gray-900 transition-all hover:scale-105 active:scale-95 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed"
                                            wire:loading.attr="disabled"
                                            wire:target="submitComment({{ $post->id }})">
                                            <span class="material-symbols-outlined text-sm">send</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="text-center py-20 bg-white border border-dashed border-gray-100 rounded-2xl">
                        <p class="text-gray-500 font-bold">Nenhum marco alcançado ainda.</p>
                    </div>
                @endforelse
            </div>

            {{-- Lateral Column --}}
            <aside class="lg:col-span-4 space-y-6 order-1 lg:order-2">
                <section
                    class="bg-gradient-to-br from-gray-900 to-black rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-orange-500 fill-1">local_fire_department</span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Ritmo
                                Atual</span>
                        </div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-black">{{ $streak }}</span>
                            <span class="text-xs font-bold text-gray-400 uppercase">
                                {{ $streak <= 1 ? 'Dia' : 'Dias' }} de Ofensiva
                            </span>
                        </div>
                        <div class="mt-4 h-1 w-full bg-white/10 rounded-full overflow-hidden">
                            <div class="bg-orange-500 h-full transition-all duration-1000"
                                style="width: {{ min(($streak / 30) * 100, 100) }}%"></div>
                        </div>
                        <p class="text-[10px] mt-2 text-gray-500 font-bold uppercase tracking-tight">Mantenha a chama
                            acesa!
                        </p>
                    </div>
                    <div class="absolute top-0 right-0 -mr-8 -mt-8 size-32 bg-orange-500/10 blur-[60px] rounded-full">
                    </div>
                </section>
            </aside>
        </div>

        {{-- Modais --}}
        <x-modal :show="$fullscreenImageUrl !== null" maxWidth="4xl" wire:model="fullscreenImageUrl">
            <div class="p-2 bg-black/5 flex justify-center">
                <img src="{{ $fullscreenImageUrl }}" alt="Fullscreen"
                    class="max-w-full max-h-[85vh] w-auto h-auto mx-auto object-contain rounded-xl" />
            </div>
        </x-modal>

        <x-modal :show="$reportingPostId !== null" wire:model="reportingPostId">
            <form wire:submit.prevent="reportPost" class="p-6">
                <h2 class="text-lg font-black mb-4 uppercase tracking-tighter">Denunciar Postagem</h2>
                <div class="space-y-4">
                    <div>
                        <x-input-label value="Motivo Principal" />
                        <select wire:model="reportReason"
                            class="w-full mt-1 border-gray-100 focus:border-primary focus:ring-primary rounded-xl shadow-inner font-bold text-sm"
                            required>
                            <option value="">Selecione...</option>
                            <option value="spam">Spam / Propaganda</option>
                            <option value="harassment">Assédio / Ofensa</option>
                            <option value="inappropriate">Inadequado</option>
                            <option value="hate_speech">Discurso de Ódio</option>
                            <option value="other">Outro</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="O que aconteceu? (opcional)" />
                        <textarea wire:model="reportDetails"
                            class="w-full mt-1 border-gray-100 focus:border-primary focus:ring-primary rounded-xl shadow-inner text-sm font-medium"
                            rows="3"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button wire:click="$set('reportingPostId', null)">Cancelar</x-secondary-button>
                    <x-primary-button>Enviar Agora</x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal :show="$editingPostId !== null" wire:model="editingPostId">
            <form wire:submit.prevent="updatePost" class="p-6">
                <h2 class="text-lg font-black mb-4 uppercase tracking-tighter">Ajustar Conquista</h2>
                <div class="space-y-4">
                    <div>
                        <x-input-label value="Texto da Postagem" />
                        <textarea wire:model="editContent"
                            class="w-full mt-1 border-gray-100 focus:border-primary focus:ring-primary rounded-xl shadow-inner font-bold text-sm"
                            rows="4" required></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button wire:click="$set('editingPostId', null)">Voltar</x-secondary-button>
                    <x-primary-button>Atualizar</x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal :show="$deletingPostId !== null" wire:model="deletingPostId">
            <div class="p-6 text-center">
                <h2 class="text-xl font-black mb-2 uppercase tracking-tighter">Remover do Everest?</h2>
                <p class="text-gray-500 text-sm font-medium mb-6">Esta conquista será removida permanentemente de sua
                    trilha.</p>
                <div class="flex justify-center gap-4">
                    <x-secondary-button wire:click="$set('deletingPostId', null)">Manter Post</x-secondary-button>
                    <x-danger-button wire:click="deletePost">Excluir Agora</x-danger-button>
                </div>
            </div>
        </x-modal>
        {{-- Fullscreen Image Viewer (Facebook Style) --}}
        @if ($fullscreenImageUrl && $fullscreenPostId)
            @php $fullscreenPost = $posts->firstWhere('id', $fullscreenPostId); @endphp
            @if ($fullscreenPost)
                <div class="fixed inset-0 z-[100] flex bg-black/95 transition-all animate-in fade-in duration-200"
                    x-data
                    @keydown.escape.window="$wire.set('fullscreenImageUrl', null); $wire.set('fullscreenPostId', null)">

                    {{-- Left Side: Image Content --}}
                    <div class="flex-1 relative flex items-center justify-center p-4 md:p-12">
                        <button wire:click="$set('fullscreenImageUrl', null); $wire.set('fullscreenPostId', null)"
                            class="absolute top-6 left-6 text-white/70 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-full z-10">
                            <span class="material-symbols-outlined text-4xl">close</span>
                        </button>

                        <img src="{{ $fullscreenImageUrl }}"
                            class="max-w-full max-h-full object-contain shadow-2xl rounded-sm"
                            alt="Fullscreen image" />
                    </div>

                    {{-- Right Side: Comments Sidebar --}}
                    <div
                        class="w-full md:w-[400px] h-full bg-white flex flex-col shadow-2xl overflow-hidden text-start">
                        {{-- Header: User Info --}}
                        <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-primary/10 border-2 border-primary/20 flex items-center justify-center overflow-hidden shrink-0">
                                @if ($fullscreenPost->user->avatar_url)
                                    <img src="{{ $fullscreenPost->user->avatar_url }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span
                                        class="text-primary font-black text-sm uppercase">{{ substr($fullscreenPost->user->name, 0, 2) }}</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-black text-gray-900 truncate uppercase tracking-tighter">
                                    {{ $fullscreenPost->user->name }}
                                </h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                    {{ $fullscreenPost->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        {{-- Scrollable Content: Post & Comments --}}
                        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                            <div class="mb-6">
                                <p
                                    class="text-sm font-medium leading-relaxed text-gray-800 whitespace-pre-wrap italic">
                                    "{{ $fullscreenPost->content }}"
                                </p>
                            </div>

                            {{-- Interaction Stats --}}
                            <div class="flex items-center justify-between pb-4 border-b border-gray-50 mb-4">
                                <div class="flex items-center gap-1">
                                    <span
                                        class="material-symbols-outlined text-secondary text-sm fill-1">favorite</span>
                                    <span
                                        class="text-xs font-black text-gray-900">{{ $fullscreenPost->likes_count ?: 0 }}</span>
                                </div>
                                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                    {{ $fullscreenPost->comments_count ?: 0 }} incentivos
                                </div>
                            </div>

                            {{-- Interaction Buttons --}}
                            <div class="flex items-center gap-4 mb-6">
                                <button wire:click="toggleLike({{ $fullscreenPost->id }})"
                                    class="flex-1 flex items-center justify-center gap-2 py-2 rounded-xl transition-all {{ $fullscreenPost->is_liked ? 'bg-secondary/10 text-secondary' : 'bg-gray-50 text-gray-500 hover:bg-gray-100' }}">
                                    <span
                                        class="material-symbols-outlined {{ $fullscreenPost->is_liked ? 'fill-1' : '' }}"
                                        style="font-size: 16px !important;">favorite</span>
                                    <span class="text-xs font-black uppercase tracking-widest">Incentivar</span>
                                </button>
                            </div>

                            {{-- Comments List --}}
                            <div class="space-y-4">
                                @foreach ($fullscreenPost->comments as $comment)
                                    <div class="flex gap-3 animate-in slide-in-from-bottom-2 duration-300">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden shrink-0 border border-gray-200">
                                            @if ($comment->user->avatar_url)
                                                <img src="{{ $comment->user->avatar_url }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <span
                                                    class="text-gray-400 font-bold text-[10px] uppercase">{{ substr($comment->user->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 text-start">
                                            <div class="bg-gray-50 rounded-2xl px-3 py-2">
                                                <h4
                                                    class="text-[10px] font-black text-gray-900 uppercase tracking-tighter">
                                                    {{ $comment->user->name }}
                                                </h4>
                                                <p class="text-xs text-gray-600 font-medium leading-normal">
                                                    {{ $comment->content }}
                                                </p>
                                            </div>
                                            <span
                                                class="text-[9px] text-gray-400 font-bold uppercase tracking-widest ml-2 mt-1">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Footer: Comment Input --}}
                        <div class="p-4 bg-gray-50/50 border-t border-gray-100 mt-auto">
                            <form wire:submit.prevent="submitComment({{ $fullscreenPost->id }})" class="relative">
                                <x-text-input wire:model="commentContent.{{ $fullscreenPost->id }}"
                                    placeholder="Dê um incentivo..." class="pr-12 !h-11" />
                                <button type="submit" @disabled(empty($commentContent[$fullscreenPost->id] ?? ''))
                                    class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-full transition-all {{ empty($commentContent[$fullscreenPost->id] ?? '') ? 'text-gray-300' : 'text-primary hover:bg-primary/10' }}">
                                    <span class="material-symbols-outlined fill-1">send</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endif
</main>
