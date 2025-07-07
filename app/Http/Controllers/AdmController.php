<?php

namespace App\Http\Controllers;

use App\Models\CalendarioParametro;
use App\Models\CategoriaProduto;
use App\Models\Produto;
use App\Models\ProdutoPrecoDia;
use App\Models\ProdutoPrecoEspecifico;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Calendario;
use App\Models\PrecoControle;
use App\Models\ProdutoPreco;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdmController extends Controller
{
    public function viewAdmPanel($empresa)
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        return view('login', compact('dados', 'empresa'));
    }

    public function viewTeste()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        $empresa_id = $dados->empresa_id;
        $calendario = Calendario::where('empresa_id', $empresa_id)->get();
        return view('teste', compact('dados', 'empresa', 'calendario'));
    }

    public function view()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        $empresa_id = $dados->empresa_id;
        $calendario = Calendario::where('empresa_id', $empresa_id)->get();
        return view('sidebar', compact('dados', 'empresa', 'calendario'));
    }

    public function viewCalendar()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        $empresa_id = $dados->empresa_id;

        $calendario = Calendario::where('empresa_id', $empresa_id)
            ->get()
            ->keyBy('dia_semana'); // ← organiza por dia

        $categorias = CategoriaProduto::where('empresa_id', $empresa_id)
            ->with('tipoProduto')
            ->get();

        $calendario_parametro = CalendarioParametro::where('empresa_id', $empresa_id)
            ->get();

        $parametrosMapeados = $calendario_parametro->pluck('status', 'dia_semana')->toArray();

        $produtos = [];


        return view('calendar', compact('dados', 'empresa', 'calendario', 'categorias', 'produtos', 'parametrosMapeados'));
    }

    public function getProduct($categoriaId)
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        $empresa_id = $dados->empresa_id;

        $produtos = Produto::where('categoria_produto_id', $categoriaId)
            ->where('empresa_id', $empresa_id)
            ->get();

        return response()->json($produtos);
    }

    public function atualizarDia(Request $request)
    {
        $dia = Calendario::find($request->calendario_id);
        $dia->status = $request->status;
        $dia->save();

        return response()->json(['success' => true, 'message' => 'Dia atualizado com sucesso.']);
    }

    public function getCalendario($produtoId)
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        $empresa_id = $dados->empresa_id;
    
        $produto = Produto::where('produto_id', $produtoId)
                  ->with([
                      'produtoPreco' => function ($query) {
                          $query->where('produto_id', '!=', null);
                      },
                      'produtoPrecoDia' => function ($query) {
                          $query->where('produto_id', '!=', null);
                      },
                      'produtoPrecoEspecifico' => function ($query) {
                          $query->where('produto_id', '!=', null);
                      }
                  ])
                  ->first();
        
        if (!$produto) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }

        $preco = null;
        $precosPorDia = null;
        $valor_unico = null;
        $valor_semana = null;
        $precosEspecificos = collect($produto->produtoPrecoEspecifico->all()); 
        
        if($produto->produtoPreco->isNotEmpty()){
            $preco = $produto->produtoPreco->first()->valor;
            $valor_unico = $preco;
        }else {
            $valor_semana = ProdutoPrecoDia::where('produto_id', $produtoId)->get();
            $precosPorDia = [
                'segunda' => null,
                'terca' => null,
                'quarta' => null,
                'quinta' => null,
                'sexta' => null,
                'sabado' => null,
                'domingo' => null,
                'feriado' => null,
            ];
            $dias = $produto->produtoPrecoDia->all();

             foreach ($dias as $dia) {
                if ($dia->ativo && $dia->valor !== null) {
                    $precosPorDia[$dia->dia_semana] = $dia->valor;
                }
            }
        }

    $events = Calendario::where('empresa_id', $empresa_id)
                    ->get()
                    ->map(function ($calendario) use ($precosPorDia, $preco, $precosEspecificos) {
                        $classNames = [];
                        $precoDia = null;
                        $diasDaSemanaEmPortugues = [
                            'monday' => 'segunda',
                            'tuesday' => 'terca',
                            'wednesday' => 'quarta',
                            'thursday' => 'quinta',
                            'friday' => 'sexta',
                            'saturday' => 'sabado',
                            'sunday' => 'domingo'
                        ];
                        
                        $status = $calendario->status;

                        $precoEspecifico = $precosEspecificos->first(function ($item) use ($calendario) {
                            $data = Carbon::parse($item->data); 
                            $dataCalendario = Carbon::parse($calendario->data); 
                            
                            return $dataCalendario->isSameDay($data);
                        });
                        

                        if($precoEspecifico) {
                            $precoDia = $precoEspecifico->valor;
                            $status = $precoEspecifico->status;
                        } else {
                            if($preco !== null){
                                $precoDia = $preco;
                            }else{
                                $diaSemana = Carbon::parse($calendario->data)->format('l');
                                $diaSemana = strtolower($diaSemana);
                                $diaSemanaEmPortugues = $diasDaSemanaEmPortugues[strtolower($diaSemana)];
    
                                if (isset($precosPorDia[$diaSemanaEmPortugues])) {
                                    $precoDia = $precosPorDia[$diaSemanaEmPortugues];
                                }
                            }
                        }

                        if ($precoDia === null || $calendario->status === false) {
                            $precoDia = null;
                            $classNames[] = 'event-unavailable';
                        } else {
                            $classNames[] = 'event-available';
                        }

                        if($status === false){
                            $classNames[] = 'event-unavailable';
                            $precoDia = null;
                        }
                        
                        return [
                            
                            'id' => $calendario->calendario_id,
                            'title' => $precoDia ? 'R$ ' . number_format($precoDia, 2, ',', '.') : 'Fechado',
                            'start' => $calendario->data,
                            'classNames' => $classNames,
                            'extendedProps' => [
                                'status' => $status,
                                'feriado' => $calendario->feriado,
                                'promocao' => $calendario->promocao,
                                'preco' => $precoDia
                            ]
                        ];
                    });

                    return response()->json([
                        'events' => $events,
                        'valor_unico' => $valor_unico,
                        'valor_semana' => $valor_semana
                    ]);
                    
    }

               
    public function getAllCalendar()
    {
        $empresa = Session::get('empresa');
        $dados = Empresa::where('nome_fantasia', $empresa)->firstOrFail();
        $empresa_id = $dados->empresa_id;
        
        $events = Calendario::where('empresa_id', $empresa_id)
            ->get()
            ->map(function ($calendario)  {
                $classNames = [];
                $statusDia = 'Disponivel';
                
                if ($calendario->status === false) {
                    $statusDia = 'Fechado';
                    $classNames[] = 'event-unavailable';
                } else {
                    $classNames[] = 'event-available';
                }

                $title = $statusDia;

                return [
                    'id' => $calendario->calendario_id,
                    'title' => $title,
                    'start' => $calendario->data,
                    'classNames' => $classNames,
                    'extendedProps' => [
                        'status' => $calendario->status,
                        'feriado' => $calendario->feriado,
                        'promocao' => $calendario->promocao
                    ]
                ];
            });

        // Retornando os eventos e o status dos dias
        return response()->json([
            'events' => $events,
        ]);
    }


    public function updateCalendario(Request $request)
    {
        $diaId = $request->get('calendarioId');
        $status = $request->get('status') === 'true'; 
        $valor = $request->get('valor');
        $data = $request->get('dataSelecionada');
        $produtoId = $request->get('produtoId');

        $geral = $request->get('geral') === 'true'; 

        DB::beginTransaction();

        try{
            if($geral){
                if($status){
                    $calendario = Calendario::where('calendario_id', $diaId)->firstOrFail();
                    $calendario->status = true;
                    $calendario->save();
                }else{
                    $calendario = Calendario::where('calendario_id', $diaId)->firstOrFail();
                    $calendario->status = false;
                    $calendario->save();
                }
            }else {
                if($status){
                    $precoEspecifico = ProdutoPrecoEspecifico::where('produto_id', $produtoId)
                    ->where('data', '=', $data)
                    ->first();
                    if($precoEspecifico){
                        $precoEspecifico->valor = $valor; 
                        $precoEspecifico->status = true;
                        $precoEspecifico->save();
                    }else {
                        $precoEspecifico = new ProdutoPrecoEspecifico();
                        $precoEspecifico->produto_id = $produtoId; 
                        $precoEspecifico->data = $data;
                        $precoEspecifico->valor = $valor;
                        $precoEspecifico->status = true;
                        $precoEspecifico->save();
                    }
                }else{
                    $precoEspecifico = ProdutoPrecoEspecifico::where('produto_id', $produtoId)
                    ->where('data', '=', $data)
                    ->first();
                    if($precoEspecifico){
                        $precoEspecifico->status = false; 
                        $precoEspecifico->save();
                    }else {
                        $precoEspecifico = new ProdutoPrecoEspecifico();
                        $precoEspecifico->produto_id = $produtoId; 
                        $precoEspecifico->data = $data;
                        $precoEspecifico->valor = $valor;
                        $precoEspecifico->status = false;
                        $precoEspecifico->save();
                    }
                }
            }
            DB::commit();

            return response()->json(['message' => 'Evento atualizado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao atualizar evento: ' . $e->getMessage()], 500);
        }
        
    }
}