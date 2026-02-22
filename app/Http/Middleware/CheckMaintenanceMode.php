<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for admin routes or if maintenance mode is off
        if ($request->is('admin*') || !setting('maintenance_mode', false)) {
            return $next($request);
        }

        // Whitelist IP addresses
        $whitelistedIps = explode(',', setting('maintenance_ips', ''));
        if (in_array($request->ip(), $whitelistedIps)) {
            return $next($request);
        }

        // Custom maintenance message or view
        $message = setting('maintenance_message', 'Site is under maintenance. Please check back later.');
        
        return response()->view('errors.maintenance', ['message' => $message], 503);
    }
}
