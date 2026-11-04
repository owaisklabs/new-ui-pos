<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBranchAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect('login');
        }

        // If user is admin, allow access to everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has default branch set
        if (!$user->default_branch_id && $user->branches()->count() === 0) {
            return redirect()->route('branches.select')->with('error', 'No branch assigned to your account');
        }

        // Set current branch in session
        $branchId = $request->get('branch_id') ?? $user->default_branch_id;

        if ($branchId && !$user->canAccessBranch($branchId)) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access to this branch');
        }

        return $next($request);
    }
}
