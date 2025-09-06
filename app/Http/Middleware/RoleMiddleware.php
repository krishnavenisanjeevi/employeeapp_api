<?php

namespace App\Http\Middleware;

use App\Models\Result;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    private $roleMap = [
        'admin' => ['manager', 'editor', 'author', 'subscriber'],
        'manager' => ['editor', 'author', 'subscriber'],
        'editor' => ['author', 'subscriber'],
        'subscriber' => []
    ];
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user();
        if ($user->role == $role || in_array($role, $this->roleMap[$user->role])) {
            return $next($request);
        }

        return response()->json(Result::failed('Forbidden'), Response::HTTP_FORBIDDEN);
        //
    }
}