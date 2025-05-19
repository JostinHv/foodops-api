<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests extends BaseThrottleRequests
{

    protected function buildException($request, $key, $maxAttempts, $responseCallback = null)
    {
        $retryAfter = intval($this->getTimeUntilNextRetry($key));

        $response = response()->json([
            'error' => true,
            'message' => 'Demasiados intentos',
            'details' => [
                'retry_after' => $retryAfter,
                'available_at' => now()->addSeconds($retryAfter)->format('Y-m-d H:i:s'),
                'attempts' => $this->limiter->attempts($key),
                'limit' => $maxAttempts
            ]
        ], Response::HTTP_TOO_MANY_REQUESTS)->withHeaders([
            'Retry-After' => $retryAfter,
            'X-RateLimit-Reset' => $this->availableAt($retryAfter),
            'Content-Type' => 'application/json'
        ]);

        throw new HttpResponseException($response);
    }

    protected function handleRequest($request, Closure $next, array $limits): Response
    {
        foreach ($limits as $limit) {
            if ($this->limiter->tooManyAttempts($limit->key, $limit->maxAttempts)) {
                $this->buildException($request, $limit->key, $limit->maxAttempts);
            }
            $this->limiter->hit($limit->key, $limit->decayMinutes * 60);
        }

        $response = $next($request);

        $remainingAttempts = $this->calculateRemainingAttempts($limit->key, $limit->maxAttempts);
        $retryAfter = intval($this->limiter->availableIn($limit->key));

        return $this->addHeaders($response, $limit->maxAttempts, $remainingAttempts, $retryAfter);
    }

    public function addHeaders(Response $response,  $maxAttempts,  $remainingAttempts,  $retryAfter = null): Response
    {
        if (!is_null($retryAfter)) {
            $response->headers->add([
                'Retry-After' => $retryAfter,
                'X-RateLimit-Reset' => $this->availableAt(intval($retryAfter)),
            ]);
        }

        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ]);

        return $response;
    }
}
