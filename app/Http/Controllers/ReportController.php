<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProduto;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Venda;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function reportView()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        $empresa_id = $dados->empresa_id;
        $categorias = CategoriaProduto::where('empresa_id', $empresa_id)
        ->with('tipoProduto')
        ->get();

        return view('reportView', compact('dados', 'empresa', 'categorias'));
    }

    public function generateReport(Request $request)
    {
        $dataInicio = $request->input('data_inicio');
        $dataFinal = $request->input('data_final');
        $categoriaId = $request->input('categoria_id');
        $produtoId = $request->input('produto_id');
    
        // Inicia a consulta
        $vendas = Venda::with('vendaProduto') 
            ->whereBetween('data', [$dataInicio, $dataFinal])
            ->where('status_pagamento_descricao_id', 2); 
    
        if ($categoriaId !== 'categoriaGeral') {
            $vendas->whereHas('vendaProduto.produto', function ($query) use ($categoriaId) {
                $query->where('categoria_produto_id', $categoriaId);
            });
        }
    
        if ($produtoId) {
            $vendas->whereHas('vendaProduto', function ($query) use ($produtoId) {
                $query->where('produto_id', $produtoId);
            });
        }
    
        $vendas = $vendas->get(); 
    
        
        return view('generatedReport', compact('vendas', 'dataInicio', 'dataFinal'));

    }
    
    
    
    
}
