<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\ProdutoPreco;

class CalendarController extends Controller
{
    public function getPrecos(Request $request)
    {
        $dataInicio = $request->query('start');
        $dataFim = $request->query('end');

        // Busca produtos tipo 'ingresso' e subtitulo 'inteira'
        $produtos = Produto::where('tipo_produto', 'ingresso')
            ->where('subtitulo', 'inteira')
            ->with('preco') // Assume-se que tenha relação com produto_preco
            ->get();

        // Criar array com os preços
        $precos = [];
        foreach ($produtos as $produto) {
            foreach ($produto->precos as $preco) {
                // Para simplificação, adicionamos uma data fixa (você pode adaptar ao seu modelo)
                $precos[] = [
                    'data' => $preco->data,  // Supondo que exista uma data no modelo
                    'preco' => $preco->valor,
                    'promocao' => $preco->valor_promocional
                ];
            }
        }

        return response()->json($precos);
    }
}
