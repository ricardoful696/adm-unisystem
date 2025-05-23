<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaProduto;
use App\Models\ProdutoPreco;
use App\Models\TipoProduto;
use App\Models\ProdutoPrecoEspecifico;
use App\Models\ProdutoPrecoDia;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;
use App\Models\ComboProduto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Promocao;
use App\Models\PromocaoTipo;
use App\Models\PromocaoCompraAntecipada;
use App\Models\PromocaoCompreEGanhe;
use App\Models\PromocaoDataEspecifica;
use App\Models\PromocaoNivel;
use App\Models\PromocaoDescontoFixo;

class ComboController extends Controller
{
    public function newComboView (Request $request) 
    {
        $empresaId = Auth::user()->empresa_id;

        $categorias = CategoriaProduto::where('empresa_id', $empresaId)
        ->with('tipoProduto') 
        ->get();   

        $produtos = Produto::where('empresa_id', $empresaId)
        ->get();

        return view('newCombo', ['categorias' => $categorias, 'produtos' => $produtos]);
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

    public function saveCombo(Request $request)
    {   
        $bilhete = $request->boolean('bilhete'); 
        $produto_fixo = $request->boolean('produto_fixo'); 
        $titulo = $request->input('titulo');
        $subtitulo = $request->input('subtitulo');
        $descricao = $request->input('descricao');
        $termos = $request->input('termos');
        $categoria_produto_id = $request->input('categoria_produto_id');
        $url_capa = $request->input('url_capa');
        $venda_qtd_max = $request->input('venda_qtd_max', 0); 
        $venda_qtd_max_diaria = $request->input('venda_qtd_max_diaria', 0); 
        $qtd_entrada_saida = $request->input('qtd_entrada_saida', 0); 
        $datasPrecosEspecificos = $request->input('datasPrecosEspecificos', []);
        $precoPorDia = $request->input('preco_por_dia', []);
        $valor_unico = $request->input('valor_unico');
        $categoriasProdutos = json_decode($request->input('categorias_produtos'), true);
        $promocoes = json_decode($request->input('promocoes'), true) ?? [];


        $empresaId = Auth::user()->empresa_id;
        
        try {
            DB::beginTransaction();

            $comboId = uniqid();

            $produto = new Produto();
            $produto->categoria_produto_id = $categoria_produto_id;
            $produto->empresa_id = $empresaId;
            $produto->titulo = $titulo;
            $produto->subtitulo = $subtitulo;
            $produto->descricao = $descricao;
            $produto->termos_condicoes = $termos;
            $produto->url_capa = $url_capa;
            $produto->ativo = true;
            $produto->venda_qtd_max = $venda_qtd_max;
            $produto->venda_qtd_max_diaria = $venda_qtd_max_diaria;
            $produto->qtd_entrada_saida = $qtd_entrada_saida;
            $produto->promocao = false;
            $produto->principal = false;
            $produto->combo_id = $comboId;
            $produto->bilhete = $bilhete;
            $produto->produtos_fixos_combo = $produto_fixo;
            $produto->principal = false;

            $produto->save();

            if ($request->hasFile('capa')) {
                $file = $request->file('capa');
                $uniqueId = uniqid(); // Gera um ID único
                $filename = $uniqueId . '.' . $file->getClientOriginalExtension(); 

                // Armazena o arquivo na pasta "public/produtos" do disco público
                $path = Storage::disk('public')->putFileAs('produtos', $file, $filename);

                // Gera a URL correta para o arquivo público
                $urlCapa = asset('storage/produtos/' . $filename);

                // Salva a URL no banco de dados
                $produto->url_capa = $urlCapa;
                $produto->save();
            }

            

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

            foreach ($categoriasProdutos as $item) {
                $comboProduto = new ComboProduto();
                $comboProduto->combo_id = $comboId;
                $comboProduto->produto_id = $item['produto_id'];
                $comboProduto->qtd_produto = $item['quantidade'] ?? 1;
                $comboProduto->save();
            }

            foreach ($promocoes as $promocaoData) {
                $promocaoTipoId = $promocaoData['promocao_tipo_id'];
    
                $promocao = new Promocao();
                $promocao->promocao_tipo_id = $promocaoTipoId;
                $promocao->data_inicio = $promocaoData['data_inicio']; 
                $promocao->data_final = $promocaoData['data_fim']; 
                $promocao->nome = $promocaoData['nome_promocao'];
                $promocao->descricao = $promocaoData['descricao_promocao'];
                $promocao->maximo_disponivel = $promocaoData['maximo_disponivel'];
                $promocao->ativo = true;
                $promocao->save();
    
                // Salva os detalhes específicos de cada tipo de promoção
                if ($promocaoTipoId === '1') { // Promoção por Nível
                    foreach ($promocaoData['niveis'] as $nivel) {
                        $promocaoNivel = new PromocaoNivel();
                        $promocaoNivel->promocao_id = $promocao->promocao_id;
                        $promocaoNivel->quantidade_min = $nivel['quantidade_min'];
                        $promocaoNivel->quantidade_max = $nivel['quantidade_max'];
                        $promocaoNivel->desconto_percentual = $nivel['desconto_percentual'];
                        $promocaoNivel->save();
                    }
                } elseif ($promocaoTipoId === '2') { // Compre e Ganhe
                    foreach ($promocaoData['compre_ganhe'] as $item) {
                        $promocaoCompreGanhe = new PromocaoCompreEGanhe();
                        $promocaoCompreGanhe->promocao_id = $promocao->promocao_id;
                        $promocaoCompreGanhe->quantidade_compra = $item['quantidade_comprada'];
                        $promocaoCompreGanhe->quantidade_gratis = $item['quantidade_gratis'];
                        $promocaoCompreGanhe->produto_id_gratis = $item['produto_id_ganho'];
                        $promocaoCompreGanhe->save();
                    }
                } elseif ($promocaoTipoId === '3') { // Compra Antecipada
                    foreach ($promocaoData['antecipadas'] as $antecipada) {
                        $promocaoAntecipada = new PromocaoCompraAntecipada();
                        $promocaoAntecipada->promocao_id = $promocao->promocao_id;
                        $promocaoAntecipada->dias_antecedencia_min = $antecipada['dias_antecedencia_min'];
                        $promocaoAntecipada->dias_antecedencia_max = $antecipada['dias_antecedencia_max'];
                        $promocaoAntecipada->desconto_percentual = $antecipada['desconto_percentual'];
                        $promocaoAntecipada->save();
                    }
                } elseif ($promocaoTipoId === '4') { // Desconto Fixo
                    foreach ($promocaoData['descontos_fixos'] as $desconto) {
                        $promocaoDescontoFixo = new PromocaoDescontoFixo();
                        $promocaoDescontoFixo->promocao_id = $promocao->promocao_id;
                        $promocaoDescontoFixo->quantidade_min = $desconto['quantidade_min'];
                        $promocaoDescontoFixo->desconto_percentual = $desconto['desconto_percentual'];
                        $promocaoDescontoFixo->save();
                    }
                } elseif ($promocaoTipoId === '5') { // Data Específica
                    foreach ($promocaoData['data_especificas'] as $dataEspecifica) {
                        $promocaoDataEspecifica = new PromocaoDataEspecifica();
                        $promocaoDataEspecifica->promocao_id = $promocao->promocao_id;
                        $promocaoDataEspecifica->dias_antecedencia_min = $dataEspecifica['dias_antecedencia_min'];
                        $promocaoDataEspecifica->dias_antecedencia_max = $dataEspecifica['dias_antecedencia_max'];
                        $promocaoDataEspecifica->desconto_percentual = $dataEspecifica['desconto_percentual'];
                        $promocaoDataEspecifica->data_especifica = $dataEspecifica['data_evento'];
                        $promocaoDataEspecifica->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Combo salvo com sucesso!',
                'produto' => $produto
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar o combo: ' . $e->getMessage()
            ], 500);
        }
    }
}
