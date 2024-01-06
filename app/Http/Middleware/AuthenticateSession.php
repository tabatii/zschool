<?php

namespace App\Http\Middleware;

use Illuminate\Session\Middleware\AuthenticateSession as Middleware;
use Illuminate\Http\Request;
use Filament\Facades\Filament;

class AuthenticateSession extends Middleware
{
    /**
     * Get the path the user should be redirected to when their session is not autheneticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request)
    {
        return Filament::getLoginUrl();
    }
}
