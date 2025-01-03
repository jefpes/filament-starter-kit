<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantStoreUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        config([
            'filesystems.disks.public.url' => sprintf(
                '%s://%s/storage',
                $request->isSecure() ? 'https' : 'http',
                $request->getHost()
            ),
        ]);

        return $next($request);
    }
}
