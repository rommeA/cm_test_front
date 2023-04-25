<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnsureGavePermissionToProcessData
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
        if ($request->user()) {
            if (! $request->user()?->consent_personal_data) {
                return $request->expectsJson()
                    ? abort(403, 'You did not give permission to process your personal data.')
                    : Redirect::route('ask-permission-to-process-data');
            }
        }
        return $next($request);
    }
}
