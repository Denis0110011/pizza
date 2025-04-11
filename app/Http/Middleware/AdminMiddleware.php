<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, \Closure $next): Response|RedirectResponse
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
