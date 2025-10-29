<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function abort_if;

class EnsureRouteHasPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (! $routeName || $user->hasRole('Super Admin')) {
            return $next($request);
        }

        $permissions = Permission::query()
            ->whereJsonContains('routes', $routeName)
            ->get();

        if ($permissions->isEmpty()) {
            return $next($request);
        }

        $isAllowed = $permissions->contains(function (Permission $permission) use ($user) {
            return $user->can($permission->name);
        });

        abort_if(! $isAllowed, 403, 'Anda tidak memiliki akses ke halaman ini.');

        return $next($request);
    }
}
