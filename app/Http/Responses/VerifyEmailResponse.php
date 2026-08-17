<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return response()->json([], 204);
        }

        $user = auth()->user();
        $targetRoute = ($user && $user->hasRole('super-admin')) ? route('dashboard') : route('home');

        return redirect($targetRoute.'?verified=1');
    }
}
