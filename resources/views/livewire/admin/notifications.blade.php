<div>
    <div class="space-y-6" x-data="pushNotifications('{{ $vapidPublicKey }}', {{ $isConfigured ? 'true' : 'false' }})">
        <div class="bg-white p-8 rounded-3xl border border-[#dbe6e1] shadow-sm">
            <div class="flex items-center gap-4 mb-6">
                <div class="size-12 rounded-2xl flex items-center justify-center text-orange-500 bg-orange-50">
                    <span class="material-symbols-outlined font-bold">notifications</span>
                </div>
                <div>
                    <h2 class="text-xl font-black">Teste de Web Push</h2>
                    <p class="text-sm text-gray-500">Teste o envio de notificações push</p>
                </div>
            </div>

            {{-- Status Check --}}
            <div class="space-y-4 mb-8">
                <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50">
                    <span class="material-symbols-outlined" :class="configured ? 'text-green-500' : 'text-red-500'"
                        x-text="configured ? 'check_circle' : 'error'"></span>
                    <span class="font-medium">
                        VAPID: <span x-text="configured ? 'Configurado' : 'Não configurado'"></span>
                    </span>
                </div>

                <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50">
                    <span class="material-symbols-outlined"
                        :class="{
                            'text-green-500': subStatus === 'subscribed',
                            'text-red-500': subStatus === 'denied',
                            'text-yellow-500': subStatus !== 'subscribed' && subStatus !== 'denied'
                        }"
                        x-text="subStatus === 'subscribed' ? 'check_circle' : (subStatus === 'denied' ? 'block' : 'pending')">
                    </span>
                    <span class="font-medium">
                        Subscription:
                        <span
                            x-text="subStatus === 'subscribed' ? 'Inscrito' : (subStatus === 'denied' ? 'Bloqueado' : (subStatus === 'not-subscribed' ? 'Não inscrito' : 'Verificando...'))"></span>
                    </span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap gap-4">
                <button x-show="subStatus !== 'denied'" @click="subscribe()" :disabled="isSubscribing || !configured"
                    :class="subStatus === 'subscribed' ? 'bg-gray-200 hover:bg-gray-300 text-gray-700' :
                        'bg-blue-500 hover:bg-blue-600 text-white'"
                    class="flex items-center gap-2 px-6 py-3 font-bold rounded-xl disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer">
                    <span class="material-symbols-outlined">notifications_active</span>
                    <span
                        x-text="isSubscribing ? 'Processando...' : (subStatus === 'subscribed' ? 'Re-inscrever / Sincronizar' : 'Inscrever-se')"></span>
                </button>

                <button x-show="subStatus === 'subscribed'" @click="sendTest()" :disabled="sending"
                    class="flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl disabled:opacity-50 disabled:cursor-not-allowed transition-colors cursor-pointer">
                    <span class="material-symbols-outlined">send</span>
                    <span x-text="sending ? 'Enviando...' : 'Enviar Notificação de Teste'"></span>
                </button>
            </div>

            {{-- Message --}}
            <div x-show="message" x-transition
                :class="{
                    'bg-green-50 text-green-700': msgType === 'success',
                    'bg-red-50 text-red-700': msgType === 'error',
                    'bg-blue-50 text-blue-700': msgType !== 'success' && msgType !== 'error'
                }"
                class="mt-6 p-4 rounded-xl" x-text="message">
            </div>
        </div>

        {{-- Instructions --}}
        <div class="bg-white p-8 rounded-3xl border border-[#dbe6e1] shadow-sm">
            <h3 class="text-lg font-black mb-4">Como funciona</h3>
            <ol class="list-decimal list-inside space-y-2 text-gray-600">
                <li>Clique em "Inscrever-se" e permita notificações</li>
                <li>Após inscrito, clique em "Enviar Notificação de Teste"</li>
                <li>Você receberá uma notificação push no navegador</li>
            </ol>
        </div>
    </div>

    <script>
        function pushNotifications(vapidKey, configured) {
            return {
                configured,
                vapidKey,
                subStatus: 'unknown',
                isSubscribing: false,
                sending: false,
                message: '',
                msgType: 'info',

                init() {
                    this.checkSubscription();
                },

                async checkSubscription() {
                    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
                        this.subStatus = 'denied';
                        this.message = 'Seu navegador não suporta notificações push.';
                        return;
                    }
                    if (Notification.permission === 'denied') {
                        this.subStatus = 'denied';
                        this.message = 'Notificações bloqueadas pelo navegador.';
                        return;
                    }
                    try {
                        const reg = await navigator.serviceWorker.ready;
                        const sub = await reg.pushManager.getSubscription();
                        this.subStatus = sub ? 'subscribed' : 'not-subscribed';
                    } catch {
                        this.subStatus = 'not-subscribed';
                    }
                },

                urlBase64ToUint8Array(base64String) {
                    const padding = '='.repeat((4 - base64String.length % 4) % 4);
                    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
                    const rawData = window.atob(base64);
                    const out = new Uint8Array(rawData.length);
                    for (let i = 0; i < rawData.length; ++i) out[i] = rawData.charCodeAt(i);
                    return out;
                },

                async subscribe() {
                    if (!this.vapidKey) {
                        this.message = 'VAPID não configurado.';
                        return;
                    }
                    this.isSubscribing = true;
                    this.message = '';
                    try {
                        const reg = await navigator.serviceWorker.register('/sw.js');
                        await navigator.serviceWorker.ready;
                        const perm = await Notification.requestPermission();
                        if (perm !== 'granted') {
                            this.subStatus = 'denied';
                            this.message = 'Permissão negada.';
                            return;
                        }
                        const sub = await reg.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: this.urlBase64ToUint8Array(this.vapidKey)
                        });
                        const toB64 = (buf) => {
                            let b = '';
                            const u = new Uint8Array(buf);
                            for (let i = 0; i < u.byteLength; i++) b += String.fromCharCode(u[i]);
                            return btoa(b);
                        };
                        const resp = await fetch('{{ route('push.subscribe') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                endpoint: sub.endpoint,
                                keys: {
                                    p256dh: toB64(sub.getKey('p256dh')),
                                    auth: toB64(sub.getKey('auth'))
                                }
                            })
                        });
                        if (resp.ok) {
                            this.subStatus = 'subscribed';
                            this.message = 'Inscrito com sucesso!';
                            this.msgType = 'success';
                        } else {
                            this.message = 'Erro ao salvar subscription.';
                            this.msgType = 'error';
                        }
                    } catch (e) {
                        this.message = 'Erro: ' + e.message;
                        this.msgType = 'error';
                    } finally {
                        this.isSubscribing = false;
                    }
                },

                async sendTest() {
                    this.sending = true;
                    this.message = '';
                    try {
                        const resp = await fetch('{{ route('admin.notifications.test') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = await resp.json();
                        this.msgType = data.success ? 'success' : 'error';
                        this.message = data.message;
                    } catch (e) {
                        this.msgType = 'error';
                        this.message = 'Erro: ' + e.message;
                    } finally {
                        this.sending = false;
                    }
                }
            }
        }
    </script>
</div>
