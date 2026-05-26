<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPageAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return $next($request);
        }

        if ($user->isAdministrator()) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (! $routeName) {
            return $next($request);
        }

        $page = $this->routeNameToPage($routeName);

        if (! $page) {
            return $next($request);
        }

        if ($user->canAccessPage($page)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    private function routeNameToPage(string $routeName): ?string
    {
        if (str_starts_with($routeName, 'cashier.')) {
            return 'cashier';
        }

        if (str_starts_with($routeName, 'transactions.') || $routeName === 'transactions.payment' || $routeName === 'transactions.process') {
            return 'transactions';
        }

        if (str_starts_with($routeName, 'reports.')) {
            return 'reports';
        }

        if (str_starts_with($routeName, 'profile.')) {
            return 'profile';
        }

        return null;
    }
}
