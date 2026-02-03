import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";
import { useState, useEffect } from "react";
import axios from "axios";

interface Props {
    vapidPublicKey: string | null;
    isConfigured: boolean;
}

export default function Index({ vapidPublicKey, isConfigured }: Props) {
    const [status, setStatus] = useState<
        "idle" | "loading" | "success" | "error"
    >("idle");
    const [message, setMessage] = useState("");
    const [subscriptionStatus, setSubscriptionStatus] = useState<
        "unknown" | "subscribed" | "not-subscribed" | "denied"
    >("unknown");
    const [isSubscribing, setIsSubscribing] = useState(false);

    useEffect(() => {
        checkSubscription();
    }, []);

    const checkSubscription = async () => {
        if (!("serviceWorker" in navigator) || !("PushManager" in window)) {
            setSubscriptionStatus("denied");
            setMessage("Seu navegador não suporta notificações push.");
            return;
        }

        const permission = Notification.permission;
        if (permission === "denied") {
            setSubscriptionStatus("denied");
            setMessage("Notificações bloqueadas pelo navegador.");
            return;
        }

        try {
            const registration = await navigator.serviceWorker.ready;
            const subscription =
                await registration.pushManager.getSubscription();
            setSubscriptionStatus(
                subscription ? "subscribed" : "not-subscribed",
            );
        } catch (error) {
            console.error("Erro ao verificar subscription:", error);
            setSubscriptionStatus("not-subscribed");
        }
    };

    const urlBase64ToUint8Array = (base64String: string) => {
        const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, "+")
            .replace(/_/g, "/");
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    };

    const subscribe = async () => {
        if (!vapidPublicKey) {
            setMessage("VAPID não configurado no servidor.");
            return;
        }

        setIsSubscribing(true);
        setMessage("");

        try {
            // Registra o service worker
            const registration =
                await navigator.serviceWorker.register("/sw.js");
            await navigator.serviceWorker.ready;

            // Solicita permissão
            const permission = await Notification.requestPermission();
            if (permission !== "granted") {
                setSubscriptionStatus("denied");
                setMessage("Permissão de notificações negada.");
                setIsSubscribing(false);
                return;
            }

            // Cria subscription
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
            });

            // Função auxiliar para converter Uint8Array para base64 de forma robusta
            const arrayBufferToBase64 = (buffer: ArrayBuffer) => {
                let binary = "";
                const bytes = new Uint8Array(buffer);
                const len = bytes.byteLength;
                for (let i = 0; i < len; i++) {
                    binary += String.fromCharCode(bytes[i]);
                }
                return window.btoa(binary);
            };

            const p256dh = arrayBufferToBase64(subscription.getKey("p256dh")!);
            const auth = arrayBufferToBase64(subscription.getKey("auth")!);

            // Envia para o servidor usando axios
            const response = await axios.post(route("push.subscribe"), {
                endpoint: subscription.endpoint,
                keys: {
                    p256dh: p256dh,
                    auth: auth,
                },
            });

            if (response.status === 201 || response.status === 200) {
                setSubscriptionStatus("subscribed");
                setMessage(
                    "Inscrito com sucesso! Agora você pode testar o push.",
                );
            } else {
                setMessage("Erro ao salvar subscription no servidor.");
            }
        } catch (error: any) {
            console.error("Erro ao inscrever:", error);
            const errorMsg =
                error.response?.data?.message ||
                error.message ||
                "Erro desconhecido";
            setMessage("Erro: " + errorMsg);
        } finally {
            setIsSubscribing(false);
        }
    };

    const sendTestPush = async () => {
        setStatus("loading");
        setMessage("");

        try {
            const response = await axios.post(
                route("admin.notifications.test"),
            );
            setStatus("success");
            setMessage(response.data.message);
        } catch (error: any) {
            setStatus("error");
            const errorMsg =
                error.response?.data?.message ||
                error.message ||
                "Erro desconhecido";
            setMessage("Erro ao enviar: " + errorMsg);
        }
    };

    return (
        <AdminLayout>
            <Head title="Teste de Notificações" />

            <div className="space-y-6">
                <div className="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm">
                    <div className="flex items-center gap-4 mb-6">
                        <div className="size-12 rounded-2xl flex items-center justify-center text-orange-500 bg-orange-50 dark:bg-orange-900/20">
                            <span className="material-symbols-outlined font-bold">
                                notifications
                            </span>
                        </div>
                        <div>
                            <h2 className="text-xl font-black dark:text-white">
                                Teste de Web Push
                            </h2>
                            <p className="text-sm text-gray-500">
                                Teste o envio de notificações push
                            </p>
                        </div>
                    </div>

                    {/* Status Check */}
                    <div className="space-y-4 mb-8">
                        <div className="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/30">
                            <span
                                className={`material-symbols-outlined ${isConfigured ? "text-green-500" : "text-red-500"}`}
                            >
                                {isConfigured ? "check_circle" : "error"}
                            </span>
                            <span className="dark:text-white font-medium">
                                VAPID:{" "}
                                {isConfigured
                                    ? "Configurado"
                                    : "Não configurado"}
                            </span>
                        </div>

                        <div className="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/30">
                            <span
                                className={`material-symbols-outlined ${
                                    subscriptionStatus === "subscribed"
                                        ? "text-green-500"
                                        : subscriptionStatus === "denied"
                                          ? "text-red-500"
                                          : "text-yellow-500"
                                }`}
                            >
                                {subscriptionStatus === "subscribed"
                                    ? "check_circle"
                                    : subscriptionStatus === "denied"
                                      ? "block"
                                      : "pending"}
                            </span>
                            <span className="dark:text-white font-medium">
                                Subscription:{" "}
                                {subscriptionStatus === "subscribed"
                                    ? "Inscrito"
                                    : subscriptionStatus === "denied"
                                      ? "Bloqueado"
                                      : subscriptionStatus === "not-subscribed"
                                        ? "Não inscrito"
                                        : "Verificando..."}
                            </span>
                        </div>
                    </div>

                    {/* Actions */}
                    <div className="flex flex-wrap gap-4">
                        {subscriptionStatus !== "denied" && (
                            <button
                                onClick={subscribe}
                                disabled={isSubscribing || !isConfigured}
                                className={`flex items-center gap-2 px-6 py-3 font-bold rounded-xl disabled:opacity-50 disabled:cursor-not-allowed transition-colors ${
                                    subscriptionStatus === "subscribed"
                                        ? "bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:text-gray-300"
                                        : "bg-blue-500 hover:bg-blue-600 text-white"
                                }`}
                            >
                                <span className="material-symbols-outlined">
                                    notifications_active
                                </span>
                                {isSubscribing
                                    ? "Processando..."
                                    : subscriptionStatus === "subscribed"
                                      ? "Re-inscrever / Sincronizar"
                                      : "Inscrever-se"}
                            </button>
                        )}

                        {subscriptionStatus === "subscribed" && (
                            <button
                                onClick={sendTestPush}
                                disabled={status === "loading"}
                                className="flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <span className="material-symbols-outlined">
                                    send
                                </span>
                                {status === "loading"
                                    ? "Enviando..."
                                    : "Enviar Notificação de Teste"}
                            </button>
                        )}
                    </div>

                    {/* Message */}
                    {message && (
                        <div
                            className={`mt-6 p-4 rounded-xl ${
                                status === "success"
                                    ? "bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400"
                                    : status === "error"
                                      ? "bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400"
                                      : "bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400"
                            }`}
                        >
                            {message}
                        </div>
                    )}
                </div>

                {/* Instructions */}
                <div className="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-[#dbe6e1] dark:border-gray-700 shadow-sm">
                    <h3 className="text-lg font-black dark:text-white mb-4">
                        Como funciona
                    </h3>
                    <ol className="list-decimal list-inside space-y-2 text-gray-600 dark:text-gray-400">
                        <li>Clique em "Inscrever-se" e permita notificações</li>
                        <li>
                            Após inscrito, clique em "Enviar Notificação de
                            Teste"
                        </li>
                        <li>Você receberá uma notificação push no navegador</li>
                    </ol>
                </div>
            </div>
        </AdminLayout>
    );
}
