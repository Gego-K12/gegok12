<?php

namespace App\Http\Middleware;

use App\Services\AcademyContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AcademyBackend
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!config('academy_backend.enabled') || !$user || !$user->school_id) {
            return $next($request);
        }
        if (config('academy_backend.test_only') && !in_array($user->email, config('academy_backend.demo_emails'), true)) {
            auth()->logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
            return redirect('/login')->withErrors(['email' => 'Test-only mode is active. Sign in with a prepared academy demo account.']);
        }
        foreach (config('academy_backend.disabled_paths', []) as $pattern) {
            if (Str::is($pattern, $request->path())) {
                $message = 'This school administration feature is not used by the academy.';
                if ($request->expectsJson()) {
                    return response()->json(['message' => $message], 410);
                }
                $path = match ((int) $user->usergroup_id) {
                    3 => '/admin/dashboard', 5 => '/teacher/dashboard', 6 => '/student/dashboard', default => '/login',
                };
                if (!in_array((int) $user->usergroup_id, [3, 5, 6], true)) {
                    abort(403, $message);
                }
                return redirect($path)->with('errormessage', $message);
            }
        }
        if ((int) $user->usergroup_id === 3 && $request->isMethod('GET')) {
            app(AcademyContext::class)->prepare((int) $user->school_id);
        }
        return $next($request);
    }
}
