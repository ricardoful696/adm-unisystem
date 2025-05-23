<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\Models\Interno;
class AtualizaDominioMiddleware
{
    public function handle(Request $request, Closure $next)
    {   
        if (session('ultimo_update_dominio') !== Carbon::today()->toDateString()) {
            Artisan::call('atualiza:dominio');

            session(['ultimo_update_dominio' => Carbon::today()->toDateString()]);
        }

        return $next($request);
    }
}
