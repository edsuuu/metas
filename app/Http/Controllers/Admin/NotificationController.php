<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WebPushService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private WebPushService $webPushService
    ) {}

    /**
     * Envia notificação de teste para o usuário logado.
     */
    public function sendTest(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $subscriptionsCount = $user->pushSubscriptions()->count();

        if ($subscriptionsCount === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem nenhuma subscription de push. Inscreva-se primeiro!',
            ]);
        }

        $sent = $this->webPushService->sendToUser($user, [
            'title' => '🔔 Teste de Notificação',
            'body' => 'Se você está vendo isso, Web Push está funcionando!',
            'url' => route('admin.dashboard'),
            'tag' => 'test-notification',
        ]);

        return response()->json([
            'success' => $sent > 0,
            'message' => $sent > 0
                ? "Push enviado para {$sent} dispositivo(s)!"
                : 'Falha ao enviar push. Verifique os logs.',
            'sent' => $sent,
        ]);
    }
}
