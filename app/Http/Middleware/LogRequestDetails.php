<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestDetails
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Post Route Request', [
            'method'    => $request->method(),
            'url'       => $request->fullUrl(),
            'user_id'   => auth()->id() ?? 'guest',
            'timestamp' => now()->toIso8601String(),
        ]);

        return $next($request);
    }
}
