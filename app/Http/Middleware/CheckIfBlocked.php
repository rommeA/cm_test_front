<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIfBlocked
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
        if (auth()->check()) {
            if (auth()->user()->is_blocked) {
                if (auth()->user()->is_ldap) {
                    $email = config('auth-log.admin_email');
                    $message = trans('messages.ldap_user_blocked', [
                        'email'=>$email
                    ]);
                } else {
                    $message = trans('messages.user_blocked');
                }
                auth()->logout();
                return redirect()->route('login')->with('status', $message);
            }
        }
        return $next($request);
    }
}
