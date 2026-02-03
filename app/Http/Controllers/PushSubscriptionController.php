<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller para gerenciar assinaturas de Web Push.
 * Preparado para implementação futura - requer VAPID configurado.
 */
class PushSubscriptionController extends Controller
{
    /**
     * Armazena uma nova assinatura de push.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|url',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        /** @var \App\Models\User */
        $user = Auth::user();

        $endpointHash = hash('sha256', $validated['endpoint']);

        $subscription = PushSubscription::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'endpoint_hash' => $endpointHash,
            ],
            [
                'endpoint' => $validated['endpoint'],
                'public_key' => $validated['keys']['p256dh'],
                'auth_token' => $validated['keys']['auth'],
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json([
            'message' => 'Subscription saved',
            'id' => $subscription->id,
        ], 201);
    }

    /**
     * Remove uma assinatura de push.
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|url',
        ]);

        /** @var \App\Models\User */
        $user = Auth::user();

        PushSubscription::query()
            ->where('user_id', $user->id)
            ->where('endpoint', $validated['endpoint'])
            ->delete();

        return response()->json([
            'message' => 'Subscription removed',
        ]);
    }
}
