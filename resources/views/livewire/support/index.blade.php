<div class="bg-gray-50 text-[#111815] transition-colors duration-300 min-h-screen flex flex-col font-display">
    <main class="flex-1">
        <section class="pt-20 pb-16 px-4" style="background: radial-gradient(circle at top right, rgba(19, 236, 146, 0.15), transparent), radial-gradient(circle at bottom left, rgba(19, 236, 146, 0.05), transparent)">
            <div class="max-w-[800px] mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-black text-[#111815] mb-6 tracking-tight">
                    Central de Ajuda
                </h1>
                <p class="text-lg text-gray-600 mb-10">
                    Tudo o que você precisa para conquistar seus objetivos sem obstáculos.
                </p>
            </div>
        </section>

        <section class="max-w-[1200px] mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Example Cards - These should link to actual documentation or FAQ if available -->
                <a class="group bg-white p-8 rounded-3xl border border-[#dbe6e1] hover:border-primary transition-all hover:shadow-lg hover:-translate-y-1" href="#">
                    <div class="size-14 bg-gray-50 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">key</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#111815] mb-2">Problemas de Login</h3>
                    <p class="text-sm text-gray-500">Recuperação de senha, autenticação e segurança da conta.</p>
                </a>
                <a class="group bg-white p-8 rounded-3xl border border-[#dbe6e1] hover:border-primary transition-all hover:shadow-lg hover:-translate-y-1" href="#">
                    <div class="size-14 bg-gray-50 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">flag</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#111815] mb-2">Gerenciamento de Metas</h3>
                    <p class="text-sm text-gray-500">Como criar, editar e acompanhar seu progresso no Everest.</p>
                </a>
                <a class="group bg-white p-8 rounded-3xl border border-[#dbe6e1] hover:border-primary transition-all hover:shadow-lg hover:-translate-y-1" href="#">
                    <div class="size-14 bg-gray-50 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">auto_awesome</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#111815] mb-2">Gamificação e XP</h3>
                    <p class="text-sm text-gray-500">Entenda como funcionam os níveis, badges e recompensas.</p>
                </a>
            </div>
        </section>

        <section class="bg-white border-y border-[#dbe6e1] py-20">
            <div class="max-w-[800px] mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-black text-[#111815] mb-4">Fale Conosco</h2>
                    <p class="text-gray-500">
                        Não encontrou o que procurava? Nossa equipe de especialistas está pronta para te ajudar a chegar no topo.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <div class="flex items-start gap-4 p-6 rounded-3xl bg-gray-50 border border-[#dbe6e1] hover:border-primary transition-colors">
                        <div class="p-3 bg-white rounded-2xl text-primary shadow-sm">
                            <span class="material-symbols-outlined">mail</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#111815] text-lg">E-mail</h4>
                            <p class="text-gray-500 text-sm mb-2">Para assuntos gerais e parcerias</p>
                            <a href="mailto:suporte@everest.app" class="text-primary font-bold hover:underline">suporte@everest.app</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-6 rounded-3xl bg-gray-50 border border-[#dbe6e1] hover:border-primary transition-colors">
                        <div class="p-3 bg-white rounded-2xl text-primary shadow-sm">
                            <span class="material-symbols-outlined">folder_open</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-[#111815] text-lg">Meus Chamados</h4>
                            <p class="text-gray-500 text-sm mb-2">Acompanhe suas solicitações</p>
                            <!-- Note: Route for my-tickets might need to be adjusted or implemented -->
                            <a href="{{ route('support.my-tickets') }}" class="text-primary font-bold hover:underline">Acessar painel</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 md:p-10 rounded-[2rem] border border-[#dbe6e1] shadow-xl relative overflow-hidden">
                    @if($sent)
                        <div class="flex flex-col items-center justify-center py-10 text-center animate-fade-in">
                            <div class="size-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6">
                                <span class="material-symbols-outlined text-4xl">check_circle</span>
                            </div>
                            <h3 class="text-2xl font-black text-[#111815] mb-2">Mensagem enviada!</h3>
                            <p class="text-gray-500 max-w-md mx-auto mb-8">
                                Recebemos sua solicitação e nossa equipe entrará em contato em breve. Um protocolo foi enviado para o seu e-mail.
                            </p>
                            <button wire:click="sendNew" class="px-8 py-3 rounded-full bg-primary text-[#111815] font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform">
                                Enviar nova mensagem
                            </button>
                        </div>
                    @else
                        <form wire:submit="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-[#111815] mb-2" htmlFor="name">Seu Nome</label>
                                    <input type="text" id="name" wire:model="name" class="w-full h-14 px-4 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-base transition-all" placeholder="Digite seu nome completo" />
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-[#111815] mb-2" htmlFor="email">Seu E-mail</label>
                                    <input type="email" id="email" wire:model="email" class="w-full h-14 px-4 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-base transition-all" placeholder="seu@email.com" />
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-[#111815] mb-2" htmlFor="subject">Assunto</label>
                                <select id="subject" wire:model="subject" class="w-full h-14 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-base transition-all">
                                    <option value="Dúvida Técnica">Dúvida Técnica</option>
                                    <option value="Problemas com Pagamento">Problemas com Pagamento</option>
                                    <option value="Sugestão de Melhoria">Sugestão de Melhoria</option>
                                    <option value="Outros">Outros</option>
                                </select>
                                @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-[#111815] mb-2" htmlFor="message">Sua Mensagem</label>
                                <textarea id="message" wire:model="message" class="w-full p-4 rounded-2xl bg-gray-50 border-transparent focus:border-primary focus:ring-primary text-base transition-all resize-none" rows="8" maxLength="5000" placeholder="Descreva detalhadamente como podemos te ajudar..."></textarea>
                                <div class="flex justify-end mt-1">
                                    <span class="text-xs {{ strlen($message) >= 5000 ? 'text-red-500' : 'text-gray-400' }}">
                                        {{ strlen($message) }}/5000
                                    </span>
                                </div>
                                @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="w-full cursor-pointer items-center justify-center rounded-full h-14 px-8 bg-primary text-[#111815] text-lg font-bold shadow-xl shadow-primary/20 hover:scale-[1.01] transition-transform disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove>Enviar Mensagem</span>
                                <span wire:loading>Enviando...</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </section>
    </main>
</div>
