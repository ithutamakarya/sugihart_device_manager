<?php

namespace App\Http\Middleware\Sugihart;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        $guard = auth('sdm');
        if (! $guard->check() || ! $guard->user()->is_active) {
            $guard->logout();
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            if ($request->isMethod('GET')) {
                $request->session()->put('sdm.url.intended', $request->fullUrl());
            }

            return redirect()->route('sdm.login');
        }

        return $next($request);
    }
}
