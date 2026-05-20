<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SourceGuardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldInject($request, $response)) {
            return $response;
        }

        $content = $response->getContent();
        $script = '<script src="/js/source-guard.js" defer></script>';

        if (! is_string($content) || str_contains($content, 'source-guard.js') || ! str_contains($content, '</body>')) {
            return $response;
        }

        $response->setContent(str_replace('</body>', $script.'</body>', $content));

        return $response;
    }

    private function shouldInject(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return false;
        }

        return str_contains((string) $response->headers->get('Content-Type'), 'text/html');
    }
}
