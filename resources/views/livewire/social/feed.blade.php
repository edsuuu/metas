<div class="mx-auto px-4 md:px-0 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Sidebar Esquerda --}}
        <aside class="hidden lg:block lg:col-span-3 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                <a href="{{ route('social.profile') }}" class="block relative group" wire:navigate>
                    <div
                        class="size-20 mx-auto rounded-2xl overflow-hidden mb-4 transition-transform group-hover:scale-105">
                        <img alt="{{ Auth::user()->name }}" class="w-full h-full object-cover"
                            src="{{ Auth::user()->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" />
                    </div>
                </a>
                <h3 class="font-extrabold text-lg uppercase tracking-tighter">{{ Auth::user()->name }}</h3>
                <p class="text-gray-500 text-xs mb-4">
                    @ {{ Auth::user()->nickname ?: str_replace(' ', '_', strtolower(Auth::user()->name)) }}</p>
                <div class="flex flex-col gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('social.profile') }}"
                        class="text-[10px] font-black uppercase text-primary tracking-widest hover:underline"
                        wire:navigate>Meu Perfil</a>
                    <div
                        class="flex items-center justify-center gap-1.5 bg-orange-50 px-3 py-1 rounded-full border border-orange-100 w-fit mx-auto mt-2">
                        <span class="material-symbols-outlined text-orange-500 text-xs"
                            style="font-variation-settings: 'FILL' 1">local_fire_department</span>
                        <span class="text-orange-600 font-bold text-xs">Ofensiva</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h4 class="text-xs font-black uppercase text-gray-400 tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-xs text-primary">trending_up</span>
                    Ranking
                </h4>
                <div class="space-y-4">
                    <p class="text-[10px] text-gray-400 font-bold uppercase text-center italic">Em breve novidades!</p>
                </div>
            </div>
        </aside>

        {{-- Feed Central --}}
        <div class="lg:col-span-6 space-y-6">
            {{-- Criar Post --}}
            <section
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm transition-all focus-within:shadow-md">
                <div class="flex gap-4">
                    <div class="size-10 rounded-xl overflow-hidden shrink-0">
                        <img alt="{{ Auth::user()->name }}" class="w-full h-full object-cover"
                            src="{{ Auth::user()->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" />
                    </div>
                    <form wire:submit.prevent="submitPost" class="w-full">
                        <textarea wire:model.live="content"
                            class="w-full border-none focus:ring-1! focus:ring-primary! focus:border-primary! focus:outline-hidden! outline-hidden! text-sm font-bold resize-none p-0 placeholder-gray-400 rounded-sm transition-all"
                            placeholder="O que você conquistou hoje, {{ explode(' ', Auth::user()->name)[0] }}?" rows="2"></textarea>
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        @if ($image)
                            <div
                                class="relative mt-2 rounded-xl overflow-hidden border border-gray-200 aspect-video bg-gray-50">
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                    class="w-full h-full object-contain" />
                                <button type="button" wire:click="$set('image', null)"
                                    class="absolute top-2 right-2 size-8 bg-black/50 text-white rounded-full flex items-center justify-center hover:bg-black/70 transition-colors">
                                    <span class="material-symbols-outlined"
                                        style="font-size: 14px !important;">close</span>
                                </button>
                            </div>
                        @endif

                        <div class="w-full flex items-center justify-between pt-4">
                            <div class="flex gap-2">
                                <input type="file" id="feed-image" wire:model="image" class="hidden"
                                    accept="image/*" />
                                <label for="feed-image"
                                    class="px-3 py-1.5 hover:bg-gray-100 rounded-xl transition-colors flex items-center gap-2 cursor-pointer {{ $image ? 'text-primary' : 'text-gray-400 font-bold' }}">
                                    <span class="material-symbols-outlined text-sm">image</span>
                                    <span class="text-[10px] uppercase font-black tracking-widest">
                                        {{ $image ? 'Midia OK' : 'Foto' }}
                                    </span>
                                </label>
                            </div>
                            <button type="submit" wire:loading.attr="disabled" @disabled(empty($content) && empty($image))
                                class="bg-primary px-6 py-2 rounded-xl text-gray-900 font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed">
                                Postar
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            {{-- Skeleton Loading on Initial Load --}}
            <div wire:loading.delay.block wire:target="render, loadMore" class="space-y-6">
                @if ($posts->isEmpty())
                    <x-social.post-skeleton />
                    <x-social.post-skeleton />
                    <x-social.post-skeleton />
                @endif
            </div>

            {{-- Posts List --}}
            <div wire:loading.class="opacity-50" wire:target="loadMore" class="space-y-6">
                @foreach ($posts as $post)
                    <article
                        class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm transition-all hover:shadow-md">
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('social.profile', $post->user->nickname ?: $post->user->id) }}"
                                    class="size-10 rounded-xl overflow-hidden border border-primary shrink-0 relative hover:scale-105 transition-transform"
                                    wire:navigate>
                                    <img alt="{{ $post->user->name }}" class="w-full h-full object-cover"
                                        src="{{ $post->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}" />
                                </a>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('social.profile', $post->user->nickname ?: $post->user->id) }}"
                                            class="font-extrabold text-sm hover:underline uppercase tracking-tighter"
                                            wire:navigate>{{ $post->user->name }}</a>
                                        @if ($post->type === 'goal_completed')
                                            <span
                                                class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">concluiu
                                                uma meta</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-gray-400 flex items-center gap-1 font-bold uppercase">
                                        <span class="material-symbols-outlined text-gray-400"
                                            style="font-size: 12px">schedule</span>
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
                                                Ocultar do Feed
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
                            <p class="text-sm font-medium leading-relaxed whitespace-pre-wrap">{{ $post->content }}
                            </p>
                        </div>

                        @if ($post->files->isNotEmpty())
                            <div class="mt-3 aspect-video bg-gray-50 overflow-hidden border-y border-gray-50">
                                <img src="{{ route('files.show', $post->files->first()->uuid) }}" alt="Post Content"
                                    class="w-full h-full object-cover cursor-pointer hover:opacity-95 transition-opacity"
                                    wire:click="$set('fullscreenImageUrl', '{{ route('files.show', $post->files->first()->uuid) }}'); $set('fullscreenPostId', {{ $post->id }})" />
                            </div>
                        @endif

                        <div class="p-4 border-t border-gray-50">
                            <div class="flex items-center gap-4">
                                <button wire:click="toggleLike({{ $post->id }})"
                                    class="flex items-center gap-1.5 font-bold transition-all hover:scale-110 {{ $post->is_liked ? 'text-secondary font-black' : 'text-gray-500 hover:text-primary' }}">
                                    <span class="material-symbols-outlined {{ $post->is_liked ? 'fill-1' : '' }}"
                                        style="font-size: 15px !important;">favorite</span>
                                    <span
                                        class="text-xs uppercase tracking-tighter">{{ $post->likes_count ?: 0 }}</span>
                                </button>
                                <button wire:click="toggleComments({{ $post->id }})"
                                    class="flex items-center gap-1.5 text-gray-500 font-bold hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined"
                                        style="font-size: 15px !important;">chat_bubble</span>
                                    <span
                                        class="text-xs uppercase tracking-tighter">{{ $post->comments_count ?: 0 }}</span>
                                </button>
                            </div>

                            <div class="space-y-4 pt-4 mt-4 border-t border-gray-100">
                                @if ($post->comments_count > 0)
                                    <div class="space-y-3 mb-4">
                                        @foreach ($post->comments as $comment)
                                            <div class="flex gap-2">
                                                <a href="{{ route('social.profile', $comment->user->nickname ?: $comment->user->id) }}"
                                                    class="size-6 rounded-lg overflow-hidden shrink-0 mt-0.5"
                                                    wire:navigate>
                                                    <img src="{{ $comment->user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                                                        alt="" class="w-full h-full object-cover" />
                                                </a>
                                                <div class="flex-1 bg-gray-50 p-2 rounded-md border border-gray-100">
                                                    <div class="flex justify-between items-center mb-0.5">
                                                        <a href="{{ route('social.profile', $comment->user->nickname ?: $comment->user->id) }}"
                                                            class="text-[10px] font-black hover:underline uppercase tracking-tighter"
                                                            wire:navigate>{{ $comment->user->name }}
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
                                    <div
                                        class="size-9 rounded-xl overflow-hidden shrink-0 border border-gray-100 shadow-sm">
                                        <img src="{{ Auth::user()->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                            alt="" class="w-full h-full object-cover" />
                                    </div>
                                    <div class="flex-1 flex gap-2 items-center">
                                        <div class="flex-1">
                                            <x-text-input wire:model.live="commentContent.{{ $post->id }}"
                                                wire:keydown.enter="submitComment({{ $post->id }})"
                                                placeholder="Mande um incentivo..."
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
                @endforeach

                {{-- Infinite Scroll Indicator --}}
                <div class="py-10 text-center" x-data x-intersect.margin.500px="$wire.loadMore()">
                    @if ($posts->hasMorePages())
                        <div class="flex flex-col items-center gap-4">
                            <x-social.post-skeleton />
                            <div class="flex justify-center gap-2" wire:loading wire:target="loadMore">
                                <div class="size-2 bg-primary rounded-full animate-bounce"></div>
                                <div class="size-2 bg-primary rounded-full animate-bounce [animation-delay:-0.15s]">
                                </div>
                                <div class="size-2 bg-primary rounded-full animate-bounce [animation-delay:-0.3s]">
                                </div>
                            </div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                Carregando mais conquistas...
                            </span>
                        </div>
                    @elseif ($posts->total() > 0)
                        <span
                            class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-xs">done_all</span>
                            Você chegou ao fim da trilha!
                        </span>
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-500 font-bold">O feed está silencioso...</p>
                            <a href="{{ route('social.index') }}"
                                class="text-primary font-black uppercase text-[10px] tracking-widest hover:underline mt-4 inline-block"
                                wire:navigate>Procurar Amigos</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Direita --}}
        <aside class="hidden lg:block lg:col-span-3 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h4 class="text-xs font-black uppercase text-gray-400 tracking-widest mb-4">Sugestões de amigos</h4>
                <div class="space-y-4">
                    @foreach ($suggestions as $user)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('social.profile', $user->nickname ?: $user->id) }}"
                                    class="size-9 rounded-xl overflow-hidden shrink-0 hover:ring-2 ring-primary transition-all"
                                    wire:navigate>
                                    <img alt="{{ $user->name }}" class="w-full h-full object-cover"
                                        src="{{ $user->avatar_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" />
                                </a>
                                <div class="min-w-0">
                                    <p class="text-xs font-black truncate uppercase tracking-tighter">
                                        {{ $user->name }}</p>
                                    <p class="text-[9px] text-gray-500">
                                        @ {{ $user->nickname ?: explode(' ', $user->name)[0] }}</p>
                                </div>
                            </div>

                            @if ($user->is_following)
                                <span class="bg-gray-50 text-gray-400 p-1.5 rounded-lg">
                                    <span class="material-symbols-outlined text-sm">how_to_reg</span>
                                </span>
                            @else
                                <a href="{{ route('social.profile', $user->nickname ?: $user->id) }}"
                                    class="p-1.5 rounded-lg transition-colors text-primary hover:bg-primary/10"
                                    wire:navigate>
                                    <span class="material-symbols-outlined text-sm">person_add</span>
                                </a>
                            @endif
                        </div>
                    @endforeach
                    @if ($suggestions->isEmpty())
                        <p class="text-[10px] text-gray-400 font-bold italic">Nada por aqui no momento.</p>
                    @endif
                </div>
            </div>
        </aside>
    </div>

    {{-- Modais --}}
    <x-modal :show="$fullscreenImageUrl !== null" maxWidth="2xl" wire:model="fullscreenImageUrl">
        <div class="p-2">
            <img src="{{ $fullscreenImageUrl }}" alt="Fullscreen" class="w-full h-auto rounded-xl" />
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
            <div class="fixed inset-0 z-[100] flex bg-black/95 transition-all animate-in fade-in duration-200" x-data
                @keydown.escape.window="$wire.set('fullscreenImageUrl', null); $wire.set('fullscreenPostId', null)">

                {{-- Left Side: Image Content --}}
                <div class="flex-1 relative flex items-center justify-center p-4 md:p-12">
                    <button wire:click="$set('fullscreenImageUrl', null); $wire.set('fullscreenPostId', null)"
                        class="absolute top-6 left-6 text-white/70 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-full z-10">
                        <span class="material-symbols-outlined text-4xl">close</span>
                    </button>

                    <img src="{{ $fullscreenImageUrl }}"
                        class="max-w-full max-h-full object-contain shadow-2xl rounded-sm" alt="Fullscreen image" />
                </div>

                {{-- Right Side: Comments Sidebar --}}
                <div class="w-full md:w-[400px] h-full bg-white flex flex-col shadow-2xl overflow-hidden">
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
                        <button wire:click="$set('fullscreenImageUrl', null); $wire.set('fullscreenPostId', null)"
                            class="md:hidden text-gray-400 p-1">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    {{-- Scrollable Content: Post & Comments --}}
                    <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                        <div class="mb-6">
                            <p class="text-sm font-medium leading-relaxed text-gray-800 whitespace-pre-wrap italic">
                                "{{ $fullscreenPost->content }}"
                            </p>
                        </div>

                        {{-- Interaction Stats --}}
                        <div class="flex items-center justify-between pb-4 border-b border-gray-50 mb-4">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-secondary text-sm fill-1">favorite</span>
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
                                    <div class="flex-1">
                                        <div class="bg-gray-50 rounded-2xl px-3 py-2">
                                            <h4
                                                class="text-[10px] font-black text-gray-900 uppercase tracking-tighter">
                                                {{ $comment->user->name }}
                                                }
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
                    <div class="p-4 bg-gray-50/50 border-t border-gray-100">
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
</div>
