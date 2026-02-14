<?php

namespace App\Livewire\Admin;

use App\Services\WebPushService;
use Livewire\Component;
use Illuminate\View\View;

class Notifications extends Component
{
    public function render(): View
    {
        /** @var WebPushService $webPushService */
        $webPushService = app(WebPushService::class);

        return view('livewire.admin.notifications', [
            'vapidPublicKey' => config('webpush.vapid.public_key'),
            'isConfigured' => $webPushService->isConfigured(),
        ]);
    }
}
