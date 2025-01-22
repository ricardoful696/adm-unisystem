<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\EmpresaPagamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EnterprisePayment extends Controller
{
    public function enterprisePaymentView()
    {
        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('empresa_id', $empresaId)
        ->with('parametroEmpresa')
        ->first();
        $empresasPagamento = EmpresaPagamento::all(); 
        $empresaPagamentoId = $empresa->parametroEmpresa->empresa_pagamento_id;
        
        if($empresaPagamentoId == 1){
            $empresaPagamento = Empresa::where('empresa_id', $empresaId)
            ->with('parametroEmpresa', 'efipayParametro')
            ->first();
        }
        return view('enterprisePayment', [
            'empresaPagamento' => $empresaPagamento,
            'empresasPagamento' => $empresasPagamento,
        ]);
    }
}
