<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTenant
{
    public function __construct(
        private readonly Tenant $tenant,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $hasAccess = session()->get('has_access');

        if ($hasAccess) {
            return $next($request);
        }

        $tenant = $this->validTenancyExists($request);

        // Se não há tenant, permite acesso direto.
        if (is_null($tenant)) {
            session()->put('is_master', true); // Define que está no modo master.

            return $next($request);
        }

        // Verifica se o tenant está ativo
        if (!$tenant->is_active) {
            abort(403, 'Tenant is inactive. Access forbidden.');
        }

        session()->put('tenant', $tenant);

        if (is_null($request->user())) {
            return $next($request);
        }

        // Permitir usuários sem tenant (exceção)
        if (is_null($request->user()->tenant)) {
            session()->put('is_master', true); // Usuário sem tenant tratado como master.

            return $next($request);
        }

        // Verifica se o tenant do usuário logado corresponde ao tenant da sessão
        if (Auth::user()->tenant?->id !== $tenant->id) { //@phpstan-ignore-line
            Auth::logout();

            return redirect('/login')->with('no_access', true);
        }

        $subdomainUrl = subdomain_url($tenant->domain, '');

        config(['app.url' => $subdomainUrl]);

        $request->session()->put('has_access', true);

        return $next($request);
    }

    protected function validTenancyExists(Request $request): mixed
    {
        list($subdomain) = explode('.', $request->getHost(), 2);

        $tenant = $this->tenant->where('domain', $subdomain)->first(); //@phpstan-ignore-line

        if ($tenant === null) {
            // Retorna null caso não encontre um tenant válido.
            return null;
        }

        return $tenant;
    }
}
