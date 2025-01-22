<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class UpdateCalendario
{
    public function handle(Request $request, Closure $next)
    {
        if (session('ultimo_update_calendario') !== Carbon::today()->toDateString()) {
            Artisan::call('calendario:disable-past-days');
            
            session(['ultimo_update_calendario' => Carbon::today()->toDateString()]);
        }

        return $next($request);
    }
}
