<?php

namespace App\Http\Middleware;

use Closure;
use App;

class LanguageManager
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        } elseif ($request->cookie('locale')) {
            $locale = $request->cookie('locale');
            App::setLocale($locale);
            session()->put('locale', $locale);
        }
        return $next($request);
    }
}