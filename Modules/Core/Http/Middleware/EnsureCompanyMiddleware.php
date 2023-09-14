<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Repositories\Companies\CompanyRepositoryInterface;

class EnsureCompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $company = app(CompanyRepositoryInterface::class)->findById($request->route('company'), fields: ['id']);

        if ($company) {
            return $next($request);
        }

        throw abort(404, 'Compnay doesn\'t exist.');
    }
}
