<?php

namespace App\Http\Middleware;

use App\Models\Result;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyOTP
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user != null) {
            if ($user->email_otp != null) {
                return response()->json(Result::failed("Your email not yet verified, please check your email $user->email"), Response::HTTP_FORBIDDEN);
            }
        }
        return $next($request);
    }
}