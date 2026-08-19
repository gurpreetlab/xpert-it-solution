<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('super-admin');

        $intended = session()->get('url.intended');
        if (! $isSuperAdmin && $intended && str_contains($intended, '/super-admin')) {
            session()->forget('url.intended');
        }

        $targetRoute = $isSuperAdmin ? route('dashboard') : route('home');

        return redirect()->intended($targetRoute);
    }
}
