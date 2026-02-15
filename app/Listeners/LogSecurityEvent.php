<?php

namespace App\Listeners;

use App\Models\SecurityEvent;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSecurityEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(object $event): void
    {
        $eventType = $this->getEventType($event);
        $userId = (property_exists($event, 'user') && $event->user) ? $event->user->id : (auth()->check() ? auth()->id() : null);
        
        $details = [];
        if ($eventType === 'login_failed') {
            $details['credentials'] = $event->credentials;
            unset($details['credentials']['password']); // Security!
        }

        if ($eventType === 'lockout') {
            $details['email'] = $event->request->email;
        }

        SecurityEvent::create([
            'event_type' => $eventType,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => $details,
        ]);
    }

    private function getEventType(object $event): string
    {
        return match (get_class($event)) {
            Login::class => 'login',
            Failed::class => 'login_failed',
            Logout::class => 'logout',
            Lockout::class => 'lockout',
            default => 'unknown',
        };
    }
}
