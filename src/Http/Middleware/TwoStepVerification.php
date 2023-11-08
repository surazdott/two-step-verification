<?php

namespace SurazDott\TwoStep\Http\Middleware;

use Auth;
use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoStepVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (Auth::check() && $user->getTwoStepCode()) {
            if ($user->getTwoStepExpiry() < Carbon::now()) {
                Auth::logout();

                return redirect()
                    ->route('login')
                    ->withMessage('The two step code has been expired. Please login again.');
            }

            if (! $request->is('verify*')) {
                return redirect()->route('twostep.verify');
            }
        }

        return $next($request);
    }
}
