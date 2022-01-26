<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class LogPageVisit
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guest()) {
            return $next($request);
        }

        activity()
            ->causedBy($request->user())
            ->withProperties([
                'user' => $request->user()->name ?? 'Guest',
                'user_id' => $request->user()->id,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ])
            ->event('pageVisit')
            ->log('Visited page: ' . $request->fullUrl());

        return $next($request);
    }
}
