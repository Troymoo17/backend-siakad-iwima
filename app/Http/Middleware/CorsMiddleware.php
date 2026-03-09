<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    private array $allowedOrigins = [
        'https://siakad-iwima.cloud',
        'https://www.siakad-iwima.cloud',
        'http://localhost:5173',
        'http://localhost:5174',
        'http://localhost:3000',
        'http://127.0.0.1:5173',
    ];

    public function handle(Request $request, Closure $next)
    {
        $origin = $request->header('Origin');

        if ($request->isMethod('OPTIONS')) {
            return $this->buildResponse(response('', 204), $origin);
        }

        $response = $next($request);
        return $this->buildResponse($response, $origin);
    }

    private function buildResponse($response, ?string $origin)
    {
        $allowed = array_unique(array_merge(
            $this->allowedOrigins,
            [env('FRONTEND_URL', 'https://siakad-iwima.cloud')]
        ));

        $allowOrigin = in_array($origin, $allowed) ? $origin : $allowed[0];

        $response->headers->set('Access-Control-Allow-Origin',      $allowOrigin);
        $response->headers->set('Access-Control-Allow-Methods',     'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers',     'Content-Type, Authorization, Accept, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age',           '86400');

        return $response;
    }
}