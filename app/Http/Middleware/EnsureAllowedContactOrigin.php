<?php

namespace App\Http\Middleware;

use App\Models\ContactSource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureAllowedContactOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        $origin = rtrim(trim((string) $request->header('Origin')), '/');
        $source = ContactSource::query()
            ->where('origin', $origin)
            ->where('is_active', true)
            ->first();

        if ($origin === '' || ! $source) {
            Log::warning('Rejected contact submission from unapproved origin', [
                'origin' => $origin ?: null,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return response()->json([
                'message' => 'This website is not authorized to submit contact forms.',
            ], Response::HTTP_FORBIDDEN);
        }

        $request->attributes->set('contact_source', $source);

        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin', $origin);
        $response->headers->set('Vary', 'Origin');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept');
        $response->headers->set('Access-Control-Max-Age', '86400');

        return $response;
    }
}
