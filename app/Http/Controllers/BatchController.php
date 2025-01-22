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
    public function newBatchView (Request $request) 
    {
        $empresaId = Auth::user()->empresa_id;

        $categorias = CategoriaProduto::where('empresa_id', $empresaId)
        ->with('tipoProduto') 
        ->get();

        $produtos = Produto::where('empresa_id', $empresaId)->get();

        return view('newBatch',['categorias' => $categorias, 'produtos' => $produtos]);
    }
    
    public function batchView (Request $request) 
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
            ->with('loteProduto')
            ->with('loteParametro')
            ->firstOrFail(); 

        $categorias = CategoriaProduto::where('empresa_id', $empresaId)
        ->with('tipoProduto') 
        ->get();
        
        return view('editBatch', compact('lote', 'categorias'));
    }


    public function saveBatch(Request $request)
    {
        $nome = $request->input('nome');
        $qtdLotes = $request->input('qtdLotes');
        $tipoLote = $request->input('tipoLote');
        $tipoDesconto = $request->input('tipoDesconto');
        $valorDesconto = $request->input('valorDesconto');
        $categoriasProdutos = $request->input('categoriasProdutos');
        $dadosLotes = $request->input('dadosLotes');

        $empresaId = Auth::user()->empresa_id;
        if($tipoLote == 'data'){
            $lote_por_data = true;
            $lote_por_qtd = false;
        }else {
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
            $lote->valor_desconto = $valorDesconto;
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
        $produtoId = $request->input('produtoId');

        $produto = Produto::where('produto_id', $produtoId)->first();

        $titulo = $request->input('titulo');
        $subtitulo = $request->input('subtitulo');
        $descricao = $request->input('descricao');
        $categoria_produto_id = $request->input('categoria_produto_id');
        $url_capa = $request->input('url_capa');
        $venda_qtd_min = $request->input('venda_qtd_min', 0); 
        $venda_qtd_max = $request->input('venda_qtd_max', 0); 
        $venda_qtd_max_diaria = $request->input('venda_qtd_max_diaria', 0); 
        $venda_individual = $request->boolean('venda_individual');
        $venda_combo = $request->boolean('venda_combo'); 
        
        try {
            DB::beginTransaction();

            $produto->categoria_produto_id = $categoria_produto_id;
            $produto->titulo = $titulo;
            $produto->subtitulo = $subtitulo;
            $produto->descricao = $descricao;
            $produto->url_capa = $url_capa;
            $produto->ativo = true;
            $produto->venda_combo = $venda_combo;
            $produto->venda_qtd_min = $venda_qtd_min;
            $produto->venda_qtd_max = $venda_qtd_max;
            $produto->venda_qtd_max_diaria = $venda_qtd_max_diaria;
            $produto->promocao = false;
            $produto->venda_individual = $venda_individual;
            $produto->principal = false;
            
            
            $produto->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produto editado com sucesso!',
                'produto' => $produto
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao editar o produto: ' . $e->getMessage()
            ], 500);
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
        
        return response()->json(['produtos' => $produtos,'loteParametro' => $loteParametro]);
    }

}

