<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProduto;
use App\Models\ProdutoPreco;
use App\Models\TipoProduto;
use App\Models\ProdutoPrecoEspecifico;
use App\Models\ProdutoPrecoDia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function newProductView (Request $request) 
    {
        $empresaId = Auth::user()->empresa_id;

        $categorias = CategoriaProduto::where('empresa_id', $empresaId)
        ->with('tipoProduto') 
        ->get();

        return view('newProduct', ['categorias' => $categorias]);
    }

    
    public function productView (Request $request) 
    {
        $empresaId = Auth::user()->empresa_id;

        $produtos = Produto::where('empresa_id', $empresaId)
        ->with('categoriaProduto.tipoProduto') 
        ->get();

        $categorias = CategoriaProduto::all();

        return view('product', ['produtos' => $produtos, 'categorias' => $categorias]);
    }

    
    public function categoryView (Request $request) 
    {
        $empresaId = Auth::user()->empresa_id;

        $categorias = CategoriaProduto::where('empresa_id', $empresaId)
        ->with('empresa', 'tipoProduto') 
        ->get();

        $categorias = CategoriaProduto::all();

        return view('categoryView', ['categorias' => $categorias]);
    }

    public function categorySave(Request $request)
    {
        $categoriaNome = $request->input('nome');
        $empresaId = Auth::user()->empresa_id;

        $registroMaisAntigo = CategoriaProduto::orderBy('categoria_produto_id', 'desc')->first();
        $idMaisAntigo = $registroMaisAntigo->categoria_produto_id;
        $novoId = $idMaisAntigo + 1;
        

        try {
            DB::beginTransaction();

            $tipoProduto = new TipoProduto();
            $tipoProduto->descricao = $categoriaNome;
            $tipoProduto->save();

            $categoria = new CategoriaProduto();
            $categoria->categoria_produto_id = $novoId;
            $categoria->tipo_produto_id = $tipoProduto->tipo_produto_id;
            $categoria->empresa_id = $empresaId; 
            $categoria->save();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function categoryEdit(Request $request)
    {
        $categoriaNome = $request->input('nome');
        $categoriaId = $request->input('id');

        DB::beginTransaction();
        
        try {
            $tipoProduto = TipoProduto::findOrFail($categoriaId);
            $tipoProduto->descricao = $categoriaNome;
            $tipoProduto->save();
            
            DB::commit();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Erro ao editar categoria.']);
        }
    }



    public function productUpdateModal(Request $request)
    {
        $produtoId = $request->input('produtoId');
    
        // Busca o produto
        $produto = Produto::where('produto_id', $produtoId)->first();
        if (!$produto) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }
    
        // Busca as categorias
        $categorias = CategoriaProduto::with('tipoProduto')->get();
    
        return response()->json([
            'produto' => $produto,
            'categorias' => $categorias
        ]);
    }
    
    public function editProduct($produto_id)
    {   
        $empresaId = Auth::user()->empresa_id;
        $produto = Produto::findOrFail($produto_id);
        $categorias = CategoriaProduto::where('empresa_id', $empresaId)
        ->with('tipoProduto')
        ->get(); 

        return view('editProduct', compact('produto', 'categorias'));
    }

    public function primaryProductSave(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        $produtoId = $request->input('produtoId');
    
        try {
            DB::transaction(function () use ($empresaId, $produtoId) {
                Produto::where('empresa_id', $empresaId)->update(['principal' => false]);
    
                $produto = Produto::where('produto_id', $produtoId)->firstOrFail();
                $produto->principal = true;
                $produto->save();
            });
    
            return response()->json(['message' => 'Produto marcado como principal com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao marcar o produto como principal.','error' => $e->getMessage()], 500);
        }
    }

    public function saveProduct(Request $request)
    {   
        $titulo = $request->input('titulo');
        $subtitulo = $request->input('subtitulo');
        $descricao = $request->input('descricao');
        $categoria_produto_id = $request->input('categoria_produto_id');
        $url_capa = $request->input('url_capa');
        $venda_qtd_min = $request->input('venda_qtd_min', 0); 
        $venda_qtd_max = $request->input('venda_qtd_max', 0); 
        $venda_qtd_max_diaria = $request->input('venda_qtd_max_diaria', 0); 
        $qtd_entrada_saida = $request->input('qtd_entrada_saida', 0); 
        $venda_individual = $request->boolean('venda_individual');
        $venda_combo = $request->boolean('venda_combo'); 
        $datasPrecosEspecificos = $request->input('datasPrecosEspecificos', []);
        $precoPorDia = $request->input('preco_por_dia', []);
        $valor_unico = $request->input('valor_unico');

        $empresaId = Auth::user()->empresa_id;
        
        try {
            DB::beginTransaction();

            $produto = new Produto();
            $produto->categoria_produto_id = $categoria_produto_id;
            $produto->empresa_id = $empresaId;
            $produto->titulo = $titulo;
            $produto->subtitulo = $subtitulo;
            $produto->descricao = $descricao;
            $produto->url_capa = $url_capa;
            $produto->ativo = true;
            $produto->venda_combo = $venda_combo;
            $produto->venda_qtd_min = $venda_qtd_min;
            $produto->venda_qtd_max = $venda_qtd_max;
            $produto->venda_qtd_max_diaria = $venda_qtd_max_diaria;
            $produto->qtd_entrada_saida = $qtd_entrada_saida;
            $produto->promocao = false;
            $produto->venda_individual = $venda_individual;
            $produto->principal = false;
            
            $produto->save();

            if(!$valor_unico){
                foreach ($datasPrecosEspecificos as $dataPreco) {
                    $dataInicio = \Carbon\Carbon::parse($dataPreco['data_inicio']);
                    $dataFim = \Carbon\Carbon::parse($dataPreco['data_fim']);
                    $preco = $dataPreco['preco'];

                    $currentDate = $dataInicio;

                    while ($currentDate <= $dataFim) {
                        $produtoPrecoEspecifico = new ProdutoPrecoEspecifico();
                        $produtoPrecoEspecifico->produto_id = $produto->produto_id;
                        $produtoPrecoEspecifico->data = $currentDate->format('Y-m-d'); 
                        $produtoPrecoEspecifico->valor = $preco; 
                        $produtoPrecoEspecifico->save();
        
                        $currentDate->addDay();
                    }
                }
        
                foreach ($precoPorDia as $dia => $preco) {
                    $ativo = true;
                    
                    if (is_null($preco)) {
                        $ativo = false;
                    }

                    $produtoPrecoDia = new ProdutoPrecoDia();
                    $produtoPrecoDia->produto_id = $produto->produto_id;
                    $produtoPrecoDia->dia_semana = $dia;
                    $produtoPrecoDia->valor = $preco;
                    $produtoPrecoDia->ativo = $ativo;

                    $produtoPrecoDia->save();
                }
            }else {
                $produtoPreco = new ProdutoPreco();
                $produtoPreco->produto_id = $produto->produto_id;
                $produtoPreco->valor = $valor_unico;
                $produtoPreco->valor_promocional = null;
                
                $produtoPreco->save();
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produto salvo com sucesso!',
                'produto' => $produto
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar o produto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCalendarProduct(Request $request)
    {   
        $productId = $request->input('produto_id');
        $precoPorDia = $request->input('preco_por_dia', []);
        $tipo_preco = $request->input('tipo_preco');
        $valor_unico = $request->input('valor_unico');

        $empresaId = Auth::user()->empresa_id;
        
        try {

            DB::beginTransaction();

            if($tipo_preco == 'dia_semana'){
                $preco = ProdutoPreco::where('produto_id', $productId)->first();
                
                if ($preco) {
                    $preco->delete(); 

                    foreach ($precoPorDia as $dia => $valor) {
                        $registro = new ProdutoPrecoDia();
                        $registro->produto_id = $productId;
                        $registro->dia_semana = $dia;
                        $registro->valor = is_numeric($valor) && $valor !== 'NaN' ? (float) $valor : null;
                        $registro->ativo = is_numeric($valor) && $valor !== 'NaN' ? true : false;
                        $registro->save();
                    }
                }else  {

                    $precoDia = ProdutoPrecoDia::where('produto_id', $productId)->get();

                    foreach ($precoDia as $registro) {
                        switch ($registro->dia_semana) {
                            case 'segunda':
                                $valor = isset($precoPorDia['segunda']) ? $precoPorDia['segunda'] : null;
                                $registro->valor = (is_numeric($valor) && $valor !== 'NaN') ? (float) $valor : null;
                                $registro->ativo = (is_numeric($valor) && $valor !== 'NaN') ? true : false;
                                break;
                            case 'terca':
                                $valor = isset($precoPorDia['terca']) ? $precoPorDia['terca'] : null;
                                $registro->valor = (is_numeric($valor) && $valor !== 'NaN') ? (float) $valor : null;
                                $registro->ativo = (is_numeric($valor) && $valor !== 'NaN') ? true : false;
                                break;
                            case 'quarta':
                                $valor = isset($precoPorDia['quarta']) ? $precoPorDia['quarta'] : null;
                                $registro->valor = (is_numeric($valor) && $valor !== 'NaN') ? (float) $valor : null;
                                $registro->ativo = (is_numeric($valor) && $valor !== 'NaN') ? true : false;
                                break;
                            case 'quinta':
                                $valor = isset($precoPorDia['quinta']) ? $precoPorDia['quinta'] : null;
                                $registro->valor = (is_numeric($valor) && $valor !== 'NaN') ? (float) $valor : null;
                                $registro->ativo = (is_numeric($valor) && $valor !== 'NaN') ? true : false;
                                break;
                            case 'sexta':
                                $valor = isset($precoPorDia['sexta']) ? $precoPorDia['sexta'] : null;
                                $registro->valor = (is_numeric($valor) && $valor !== 'NaN') ? (float) $valor : null;
                                $registro->ativo = (is_numeric($valor) && $valor !== 'NaN') ? true : false;
                                break;
                            case 'sabado':
                                $valor = isset($precoPorDia['sabado']) ? $precoPorDia['sabado'] : null;
                                $registro->valor = (is_numeric($valor) && $valor !== 'NaN') ? (float) $valor : null;
                                $registro->ativo = (is_numeric($valor) && $valor !== 'NaN') ? true : false;
                                break;
                            case 'domingo':
                                $valor = isset($precoPorDia['domingo']) ? $precoPorDia['domingo'] : null;
                                $registro->valor = (is_numeric($valor) && $valor !== 'NaN') ? (float) $valor : null;
                                $registro->ativo = (is_numeric($valor) && $valor !== 'NaN') ? true : false;
                                break;
                            case 'feriado':
                                $valor = isset($precoPorDia['feriado']) ? $precoPorDia['feriado'] : null;
                                $registro->valor = (is_numeric($valor) && $valor !== 'NaN') ? (float) $valor : null;
                                $registro->ativo = (is_numeric($valor) && $valor !== 'NaN') ? true : false;
                                break;
                        }
                        $registro->save();
                    }
                }
            }else {
                $precoDia = ProdutoPrecoDia::where('produto_id', $productId)->get();
                
                if ($precoDia) {
                    foreach ($precoDia as $item) {
                        $item->delete();  
                    }

                    $preco = new ProdutoPreco();
                    $preco->produto_id = $productId;
                    $preco->valor = $valor_unico;
                    $preco->valor_promocional = null;
                    $preco->save();
                }

                $precoDia = ProdutoPreco::where('produto_id', $productId)->first();
                $precoDia->valor = $valor_unico;
                $precoDia->save();
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Produto atualizado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Erro ao atualizar o produto: ' . $e->getMessage()]);
        }
    }
    public function productUpdate(Request $request)
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

}
