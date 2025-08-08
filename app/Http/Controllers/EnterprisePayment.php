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

        $empresaPagamentoId = optional($empresa->parametroEmpresa)->empresa_pagamento_id ?? 0;
        $empresasPagamento = EmpresaPagamento::all();

        // Carrega dados adicionais com base no empresaPagamentoId
        if ($empresaPagamentoId == 1) {
            $empresaPagamento = Empresa::where('empresa_id', $empresaId)
                ->with('parametroEmpresa', 'efipayParametro')
                ->first();
        } elseif ($empresaPagamentoId == 2) {
            $empresaPagamento = Empresa::where('empresa_id', $empresaId)
                ->with('parametroEmpresa', 'pagarmeParametro')
                ->first();
        } else {
            $empresaPagamento = $empresa;
        }

        return view('enterprisePayment', compact('empresaPagamento', 'empresasPagamento', 'empresaPagamentoId'));
    }
}
