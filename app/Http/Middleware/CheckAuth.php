<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->cookie('refresh_token')) {
            \Log::log('info', 'Refresh token not found in cookies.');
            return redirect()->route('login');
        }
        if (!$request->cookie('access_token')) {
            \Log::log('info', 'Access token not found in cookies.');
            return redirect()->route('login');
        }
        \Log::log('info', 'Refresh token found in cookies.');
        return $next($request);
    }
}
