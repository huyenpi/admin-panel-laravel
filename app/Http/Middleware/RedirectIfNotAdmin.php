<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role_array = ['admin', 'post editor', 'product manager'];
        if (Auth::user()->status == 'banned') {
            return redirect()->route('notify.banned');
        }
        if (Auth::user()->role == null || !in_array(Auth::user()->role->name, $role_array)) {
            return redirect()->route('home');
        }
        return $next($request);
    }
}
