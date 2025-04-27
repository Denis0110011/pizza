<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return response()->json(['error' => 'Доступ запрещен'],403);
        }

        return $next($request);
    }
}
