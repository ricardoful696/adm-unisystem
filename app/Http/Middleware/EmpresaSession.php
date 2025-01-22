<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EmpresaSession
{
    public function handle($request, Closure $next)
    {
        
        if (Auth::check()) {
            $usuario = Auth::user(); 
            $adm = $usuario->tipo_usuario_id;
            $empresaId = $usuario->empresa_id;
            $empresa = Empresa::where('empresa_id', $empresaId)->first();
            $empresaRota = $empresa->nome_fantasia;
        } else {
            $empresaRota = $request->route('empresa');
        }
        $empresaAtual = session('empresa');

        if (!is_object($empresaAtual)) {
            $empresaAtual = Empresa::where('nome_fantasia', $empresaAtual)
                                   ->with('empresaImg')
                                   ->first();
            Session::put('empresa', $empresaAtual);
        }
        
        if (!$empresaAtual) {
            $empresa = Empresa::where('nome_fantasia', $empresaRota)
                              ->with('empresaImg')
                              ->first();

            if ($empresa) {
                Session::put('empresa', $empresa);
            } else {
                return redirect()->route('selectEmp');
            }
        } else {
            if ($empresaRota !== $empresaAtual->nome_fantasia) {
                $empresa = Empresa::where('nome_fantasia', $empresaRota)
                                  ->with('empresaImg')
                                  ->first();

                if ($empresa) {
                    Session::put('empresa', $empresa);

                    if (Auth::check()) {
                        Auth::logout();
                        return redirect()->route('viewHome', ['empresa' => $empresa->nome_fantasia])
                            ->with('message', 'Você foi deslogado ao trocar de empresa.');
                    }
                } else {
                    return redirect()->route('erro');
                }
            }
        }
        return $next($request);
    }
}
