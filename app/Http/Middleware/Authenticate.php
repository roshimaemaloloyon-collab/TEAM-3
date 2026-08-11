<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $this->authenticate($request, $guards);

        return $next($request);
    }

    protected function authenticate(Request $request, array $guards): void
    {
        if (empty($guards)) {
            $guards = ['web'];
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard);

                return;
            }
        }

        $this->unauthenticated($request, $guards);
    }

    protected function unauthenticated(Request $request, array $guards): void
    {
        if ($request->is('api/*')) {
            throw new \Illuminate\Auth\AuthenticationException('Unauthenticated.', [], route('login'));
        }

        $this->redirectTo($request);
    }

    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            return route('login');
        }

        return null;
    }
}