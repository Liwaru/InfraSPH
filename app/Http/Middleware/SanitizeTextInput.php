<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeTextInput
{
    /**
     * @var array<int, string>
     */
    private array $exceptKeys = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'new_password_confirmation',
        'otp_code',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET')) {
            return $next($request);
        }

        $request->merge($this->sanitizeArray($request->all()));

        return $next($request);
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    private function sanitizeArray(array $values): array
    {
        foreach ($values as $key => $value) {
            if (in_array((string) $key, $this->exceptKeys, true)) {
                continue;
            }

            if (is_array($value)) {
                $values[$key] = $this->sanitizeArray($value);
                continue;
            }

            if (is_string($value)) {
                $values[$key] = trim(strip_tags($value));
            }
        }

        return $values;
    }
}
