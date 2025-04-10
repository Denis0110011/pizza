<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with(['error', 'Unauthorized']);
        }
        $user = Auth::user();
        if (!$user->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'not Admin'], 403);
            }
            return redirect('/')->with(['error' => 'You are not Admin']);
        }
        return $next($request);
    }
}
