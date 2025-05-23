<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProduto;
use App\Models\Lote;
use App\Models\LoteParametro;
use App\Models\LoteProduto;
use App\Models\TipoProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    public function newBatchView(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        $categorias = CategoriaProduto::where('empresa_id', $empresaId)
            ->with('tipoProduto')
            ->get();

        $produtos = Produto::where('empresa_id', $empresaId)->get();

        return view('newBatch', ['categorias' => $categorias, 'produtos' => $produtos]);
    }

    public function batchView(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;

        $lotes = Lote::where('empresa_id', $empresaId)
            ->with('loteProduto')
            ->with('loteParametro')
            ->get();

        return view('batch', ['lotes' => $lotes]);
    }

    public function editBatch($lote_id)
    {
        $empresaId = Auth::user()->empresa_id;
        $lote = Lote::where('lote_id', $lote_id)
            ->where('empresa_id', $empresaId)
            ->with(['loteProduto.produto.categoriaProduto.tipoProduto'])
            ->with('loteParametro')
            ->firstOrFail();

        $categorias = CategoriaProduto::where('empresa_id', $empresaId)
            ->with('tipoProduto.CategoriaProduto.produto')
            ->get();

        $produtos = Produto::where('empresa_id', $empresaId)->get();

        return view('editBatch', compact('lote', 'categorias', 'produtos'));
    }


    public function saveBatch(Request $request)
    {
        $nome = $request->input('nome');
        $qtdLotes = $request->input('qtdLotes');
        $tipoLote = $request->input('tipoLote');
        $tipoDesconto = $request->input('tipoDesconto');
        $categoriasProdutos = $request->input('categoriasProdutos');
        $dadosLotes = $request->input('dadosLotes');

        $empresaId = Auth::user()->empresa_id;
        if ($tipoLote == 'data') {
            $lote_por_data = true;
            $lote_por_qtd = false;
        } else {
            $lote_por_data = false;
            $lote_por_qtd = true;
        }

        try {
            DB::beginTransaction();

            $lote = new Lote();
            $lote->empresa_id = $empresaId;
            $lote->nome = $nome;
            $lote->ativo = true;
            $lote->lote_por_data = $lote_por_data;
            $lote->lote_por_qtd = $lote_por_qtd;
            $lote->tipo_desconto_id = $tipoDesconto;
            $lote->save();

            $loteNumero = 1;
            $isFirst = true;
            foreach ($dadosLotes as $loteData) {

                $loteParametro = new LoteParametro();

                if (isset($loteData['dataInicio']) && isset($loteData['dataFinal'])) {
                    $loteParametro->data_inicio = $loteData['dataInicio'];
                    $loteParametro->data_final = $loteData['dataFinal'];
                }

                if (isset($loteData['qtdVendas'])) {
                    $loteParametro->qtd_venda = $loteData['qtdVendas'];
                }
                
                if (isset($loteData['valorDesconto'])) {
                    $loteParametro->valor_desconto = $loteData['valorDesconto'];
                }
                
                $loteParametro->qtd_venda_realizada = 0;
                $loteParametro->ativo = $isFirst;
                $loteParametro->lote_numero = $loteNumero++;
                $loteParametro->lote_id = $lote->lote_id;
                $isFirst = false;
                $loteParametro->save();
            }


            foreach ($categoriasProdutos as $categoriaProduto) {
                $loteProduto = new LoteProduto();
                $loteProduto->lote_id = $lote->lote_id;
                $loteProduto->produto_id = $categoriaProduto['produto_id'];
                $loteProduto->save();
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Lote salvo com sucesso!']);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Erro ao salvar o lote.', 'error' => $e->getMessage()]);
        }
    }

    public function batchUpdate(Request $request)
    {
        $loteId = $request->input('loteId');
        $ativo = $request->input('ativo');
        $nome = $request->input('nome');
        $qtdLotes = $request->input('qtdLotes');
        $tipoLote = $request->input('tipoLote');
        $tipoDesconto = $request->input('tipoDesconto');
        $categoriasProdutos = $request->input('categoriasProdutos');
        $dadosLotes = $request->input('dadosLotes');

        $empresaId = Auth::user()->empresa_id;

        if ($tipoLote == 'data') {
            $lote_por_data = true;
            $lote_por_qtd = false;
        } else {
            $lote_por_data = false;
            $lote_por_qtd = true;
        }

        try {
            $lote = Lote::where('lote_id', $loteId)->first();

            if ($lote) {
                $lote->nome = $nome;
                $lote->lote_por_data = $lote_por_data;
                $lote->lote_por_qtd = $lote_por_qtd;
                $lote->tipo_desconto_id = $tipoDesconto;
                $lote->ativo = $ativo;
                $lote->save();
            }

            $loteIdsEnviados = array_column($dadosLotes, 'loteId');
            $lotesSalvos = LoteParametro::where('lote_id', $loteId)->pluck('lote_parametro_id')->toArray();
            $lotesParaDeletar = array_diff($lotesSalvos, $loteIdsEnviados);
            LoteParametro::whereIn('lote_parametro_id', $lotesParaDeletar)->delete();

            foreach ($dadosLotes as $loteData) {
                
                $loteParametro = LoteParametro::where('lote_parametro_id',  $loteData['loteId'])->first();

                if ($loteParametro) {
                    if (isset($loteData['dataInicio']) && isset($loteData['dataFinal'])) {
                        $loteParametro->data_inicio = $loteData['dataInicio'];
                        $loteParametro->data_final = $loteData['dataFinal'];
                    }

                    if (isset($loteData['qtdVendas'])) {
                        $loteParametro->qtd_venda = $loteData['qtdVendas'];
                    }
                    
                    if (isset($loteData['valorDesconto'])) {
                        $loteParametro->valor_desconto = $loteData['valorDesconto'];
                    }

                    $loteParametro->save(); 
                } else {
                    $maiorLoteNumero = LoteParametro::where('lote_id', $loteId)->max('lote_numero');

                    $loteParametro = new LoteParametro();
                    $loteParametro->lote_id = $loteId;
                    $loteParametro->lote_numero = ($maiorLoteNumero ?? 0) + 1;
                    $loteParametro->data_inicio = $loteData['dataInicio'] ?? null;
                    $loteParametro->data_final = $loteData['dataFinal'] ?? null;
                    $loteParametro->qtd_venda = $loteData['qtdVendas'] ?? 0;
                    $loteParametro->qtd_venda_realizada = 0;
                    $loteParametro->ativo = true;
                    $isFirst = false;
                    $loteParametro->save();  
                }
            }

            $produtosEnviados = array_column($categoriasProdutos, 'produto_id');
            $produtosSalvos = LoteProduto::where('lote_id', $loteId)->pluck('produto_id')->toArray();
            $produtosParaDeletar = array_diff($produtosSalvos, $produtosEnviados);
            LoteProduto::where('lote_id', $loteId)->whereIn('produto_id', $produtosParaDeletar)->delete();

            foreach ($categoriasProdutos as $categoriaProduto) {
                $loteProduto = LoteProduto::where('lote_id', $loteId)
                    ->where('produto_id', $categoriaProduto['produto_id'])
                    ->first();

                if ($loteProduto) {
                    $loteProduto->lote_id = $loteId;
                    $loteProduto->produto_id = $categoriaProduto['produto_id'];
                    $loteProduto->save();
                } else {
                    $loteProduto = new LoteProduto();
                    $loteProduto->lote_id = $loteId;
                    $loteProduto->produto_id = $categoriaProduto['produto_id'];
                    $loteProduto->save();
                }
            }


            DB::commit();  // Confirma a transação

            return response()->json(['success' => true, 'message' => 'Lote atualizado com sucesso!']);

        } catch (\Exception $e) {
            DB::rollBack();  // Desfaz a transação em caso de erro

            return response()->json(['success' => false, 'message' => 'Erro ao atualizar o lote.', 'error' => $e->getMessage()]);
        }
    }


    public function getCategory(Request $request)
    {
        $categoriaId = $request->input('categoria_id');

        $produtos = Produto::where('categoria_produto_id', $categoriaId)->get();

        return response()->json($produtos);
    }

    public function getDetails(Request $request)
    {
        $loteId = $request->input('lote_id');

        $produtos = Lote::with('loteProduto.produto')->find($loteId);

        $loteParametro = LoteParametro::where('lote_id', $loteId)->get();

        return response()->json(['produtos' => $produtos, 'loteParametro' => $loteParametro]);
    }

}

