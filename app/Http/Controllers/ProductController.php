<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use App\Models\CategoriaProduto;
use App\Models\ProdutoPreco;
use App\Models\TipoProduto;
use App\Models\ProdutoPrecoEspecifico;
use App\Models\ProdutoPrecoDia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Promocao;
use App\Models\PromocaoCompraAntecipada;
use App\Models\PromocaoCompreEGanhe;
use App\Models\PromocaoDataEspecifica;
use App\Models\PromocaoDescontoFixo;
use App\Models\PromocaoNivel;
use App\Models\ComboProduto;
use App\Models\CalendarioParametro;

class ProductController extends Controller
{
    public function newProductView (Request $request) 
    {
        $empresaId = Auth::user()->empresa_id;

        $categorias = CategoriaProduto::where('empresa_id', $empresaId)
        ->with('tipoProduto') 
        ->get();

        $produtos = Produto::where('empresa_id', $empresaId)
        ->get();

        return view('newProduct', ['categorias' => $categorias, 'produtos' => $produtos]);
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
    
         // Carregar o produto com todas as relações necessárias
         $produto = Produto::with([
            'produtoPreco',
            'produtoPrecoDia',
            'produtoPrecoEspecifico',
            'combo.produto',
            'promocaoRelacionada', 
            'promocaoRelacionada.promocaoCompreGanhe',
            'promocaoRelacionada.promocaoNivel',
            'promocaoRelacionada.promocaoCompraAntecipada',
            'promocaoRelacionada.promocaoDescontoFixo',
            'promocaoRelacionada.promocaoDataEspecifica'
        ])->where('empresa_id', $empresaId)->findOrFail($produto_id);
    
        $categorias = CategoriaProduto::where('empresa_id', $empresaId)->get();
        $produtos = Produto::where('empresa_id', $empresaId)->where('produto_id', '!=', $produto_id)->get();

        return view('editProduct', compact('produto', 'categorias', 'produtos'));
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
            $produto->promocao_id = null;

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

            if($produto_fixo) {

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

            } else {
                $produtoPreco = new ProdutoPreco();
                $produtoPreco->produto_id = $produto->produto_id;
                $produtoPreco->valor = null;
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
                

                $produto->promocao_id = $promocao->promocao_id;
                $produto->promocao = true; 
                $produto->save();

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
                        $promocaoDataEspecifica->data_especifica = $promocaoData['data_evento'];
                        $promocaoDataEspecifica->save();
                    }
                }
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
        $dias_ativos = $request->input('dias_ativos');
        $tipo_geral = $request->input('tipo_geral');

        $empresaId = Auth::user()->empresa_id;
        
        try {

            DB::beginTransaction();

            if ($tipo_geral) {
                foreach ($dias_ativos as $dia => $status) {
                    Calendario::where('empresa_id', $empresaId)
                        ->where('dia_semana', $dia)
                        ->update([
                            'status' => filter_var($status, FILTER_VALIDATE_BOOLEAN)
                        ]);
                }

                try {
                    $mapaDias = [
                        'segunda-feira' => 'segunda',
                        'terça-feira' => 'terca',
                        'quarta-feira' => 'quarta',
                        'quinta-feira' => 'quinta',
                        'sexta-feira'  => 'sexta',
                        'sábado'       => 'sabado',
                        'domingo'      => 'domingo',
                        'feriado'      => 'feriado',
                    ];

                    foreach ($dias_ativos as $diaLabel => $status) {
                        $diaBanco = $mapaDias[strtolower($diaLabel)] ?? null;

                        if ($diaBanco) {
                            CalendarioParametro::where('empresa_id', $empresaId)
                                ->where('dia_semana', $diaBanco)
                                ->update(['status' => filter_var($status, FILTER_VALIDATE_BOOLEAN)]);
                        }
                    }

                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
                 
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
        $produtoId = $request->input('produto_id');
        $produto = Produto::where('produto_id', $produtoId)->firstOrFail();

        $titulo = $request->input('titulo');
        $subtitulo = $request->input('subtitulo');
        $descricao = $request->input('descricao');
        $termos = $request->input('termos');
        $categoria_produto_id = $request->input('categoria_produto_id');
        $venda_qtd_max = $request->input('venda_qtd_max', 0); 
        $venda_qtd_max_diaria = $request->input('venda_qtd_max_diaria', 0); 
        $qtd_entrada_saida = $request->input('qtd_entrada_saida', 0); 
        $bilhete = $request->boolean('bilhete');
        $produto_fixo = $request->boolean('produto_fixo');
        $valor_unico = $request->input('valor_unico');
        $datasPrecosEspecificos = $request->input('datasPrecosEspecificos', []);
        $precoPorDia = $request->input('preco_por_dia', []);
        $categoriasProdutos = json_decode($request->input('categorias_produtos'), true);
        $promocoes = json_decode($request->input('promocoes'), true) ?? [];

        try {
            DB::beginTransaction();

            // Atualiza os dados do produto
            $produto->categoria_produto_id = $categoria_produto_id;
            $produto->titulo = $titulo;
            $produto->subtitulo = $subtitulo;
            $produto->descricao = $descricao;
            $produto->termos_condicoes = $termos;
            $produto->venda_qtd_max = $venda_qtd_max;
            $produto->venda_qtd_max_diaria = $venda_qtd_max_diaria;
            $produto->qtd_entrada_saida = $qtd_entrada_saida;
            $produto->bilhete = $bilhete;
            $produto->produtos_fixos_combo = $produto_fixo;
            $produto->ativo = true;
            $produto->save();

            // Atualiza imagem se necessário
            if ($request->hasFile('capa')) {
                $file = $request->file('capa');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('produtos', $file, $filename);
                $produto->url_capa = asset('storage/produtos/' . $filename);
                $produto->save();
            }

            // Remove preços antigos
            ProdutoPrecoEspecifico::where('produto_id', $produtoId)->delete();
            ProdutoPrecoDia::where('produto_id', $produtoId)->delete();
            ProdutoPreco::where('produto_id', $produtoId)->delete();

            // Adiciona os novos preços
            if (!$valor_unico) {
                foreach ($datasPrecosEspecificos as $dataPreco) {
                    $dataInicio = \Carbon\Carbon::parse($dataPreco['data_inicio']);
                    $dataFim = \Carbon\Carbon::parse($dataPreco['data_fim']);
                    $preco = $dataPreco['preco'];

                    while ($dataInicio <= $dataFim) {
                        ProdutoPrecoEspecifico::create([
                            'produto_id' => $produtoId,
                            'data' => $dataInicio->format('Y-m-d'),
                            'valor' => $preco
                        ]);
                        $dataInicio->addDay();
                    }
                }

                foreach ($precoPorDia as $dia => $preco) {
                    ProdutoPrecoDia::create([
                        'produto_id' => $produtoId,
                        'dia_semana' => $dia,
                        'valor' => $preco,
                        'ativo' => !is_null($preco)
                    ]);
                }
            } else {
                ProdutoPreco::create([
                    'produto_id' => $produtoId,
                    'valor' => $valor_unico,
                    'valor_promocional' => null
                ]);
            }

            // Atualiza combo se houver
            ComboProduto::where('combo_id', $produto->combo_id)->delete();
            foreach ($categoriasProdutos as $item) {
                ComboProduto::create([
                    'combo_id' => $produto->combo_id,
                    'produto_id' => $item['produto_id'],
                    'qtd_produto' => $item['quantidade'] ?? 1
                ]);
            }

            if ($produto->promocao_id) {
                $promocaoId = $produto->promocao_id;
                $produto->promocao_id = null;
                $produto->promocao = false;
                $produto->save();
            
                PromocaoNivel::where('promocao_id', $promocaoId)->delete();
                PromocaoCompreEGanhe::where('promocao_id', $promocaoId)->delete();
                PromocaoCompraAntecipada::where('promocao_id', $promocaoId)->delete();
                PromocaoDescontoFixo::where('promocao_id', $promocaoId)->delete();
                PromocaoDataEspecifica::where('promocao_id', $promocaoId)->delete();
            
                Promocao::where('promocao_id', $promocaoId)->delete();
                
            }
            
            if (!empty($promocoes)) {
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
                    

                    $produto->promocao_id = $promocao->promocao_id;
                    $produto->promocao = true; 
                    $produto->save();

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
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produto atualizado com sucesso!',
                'produto' => $produto
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o produto: ' . $e->getMessage()
            ], 500);
        }
    }


}
