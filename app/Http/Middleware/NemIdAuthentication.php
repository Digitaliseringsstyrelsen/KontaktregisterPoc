<?php

namespace Digist\Http\Middleware;

use Auth;
use Closure;
use Digist\Models\ApiToken;
use Illuminate\Http\Request;

class NemIdAuthentication
{
    public function handle($request, Closure $next)
    {
        if ($this->authenticateUsingBasicAuth($request)) {
            return $next($request);
        }

        if ($this->authenticateWithNemId($request)) {
            return $next($request);
        }

        return $this->accessDenied();
    }

    public function authenticateUsingBasicAuth(Request $request)
    {
        list ($type, $_) = explode(' ', $request->header('Authorization'));

        if ($type !== 'Basic') {
            return false;
        }

        Auth::shouldUse('api-basic');
        /** @var ApiToken $token */
        $token = Auth::user('api_token');

        return (bool) $token;
    }

    public function authenticateWithNemId(Request $request)
    {
        try {
            list ($type, $xml) = explode(' ', mb_convert_encoding($request->header('Authorization'), 'utf-8'), 2);

            if ($type !== 'NemID' || ! $xml) {
                return false;
            }

            nemid()->login($xml);

            return nemid()->check();

        } catch (\Exception $e) {
        } // @todo we need to handle this differently.

        return false;
    }

    private function accessDenied()
    {
        return response()->json('Invalid credentials.', 401);
    }
}
