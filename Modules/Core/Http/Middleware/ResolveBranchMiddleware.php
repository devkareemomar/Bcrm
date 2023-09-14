<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Repositories\Branches\BranchRepositoryInterface;

class ResolveBranchMiddleware
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
        /** @var User */
        $user = auth()->user();

        $branch = app(BranchRepositoryInterface::class)->findById($request->route('branch'), fields: ['id', 'company_id']);

        if (!$user) {
            throw abort(401, 'authentication required.');
        }

        if (!$branch) {
            throw abort(404, 'branch doesn\'t exsist.');
        }

        if ($user->belongsToBranch($branch)) {
            return $next($request);
        }

        throw abort(403, 'user doesn\'t belong to this branch');
    }
}
