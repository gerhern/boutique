<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        return $user->isAdmin()
            ? $next($request)
            : redirect(route('products.index'))
                ->withErrors(['error' => 'This section is reserved for the administrative team of ' . config('app.name')]);
    }
}
