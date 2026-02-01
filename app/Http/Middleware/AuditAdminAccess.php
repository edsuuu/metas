<?php

namespace App\Http\Middleware;

use App\Models\SecurityEvent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin*')) {
            $user = $request->user();
            
            // If authenticated but doesn't have required roles, log it
            if ($user && !$user->hasAnyRole(['Administrador', 'Suporte'])) {
                SecurityEvent::create([
                    'event_type' => 'unauthorized_access',
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'details' => ['url' => $request->fullUrl()],
                ]);
            }
        }

        return $next($request);
    }
}
