<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\AuthenticationProcess;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MustBeOTP
{
    use AuthenticationProcess;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->isAdmin()) {
            $user = User::where('id', Auth::id())->first();

            if ($user->mobile_verified != 1) {
                if ($this->checkAuthentication(Auth::id())) {
                    return $next($request);
                } else {
                    abort(403);
                }
            }
        } else {
            abort(403);
        }
    }
}
