<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class HandleLocale
{
    /**
     * Apply the locale carried by the request.
     *
     * A cookie rather than a column on the account, mirroring how appearance is
     * handled: it works before anyone has signed in, which matters because the
     * login screen is the first thing a visitor reads.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale');

        // A cookie can legitimately arrive as an array, so the type is checked
        // rather than forced: an unexpected shape leaves the default locale in
        // place instead of producing a nonsense one.
        if (is_string($locale) && in_array($locale, (array) config('alyawma.locales'), strict: true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
