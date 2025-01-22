<?php

namespace App\Http\Controllers;

use App\Models\Campanha;
use App\Models\Empresa;
use App\Models\Cupom;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CampaignController extends Controller
{
    public function newCampaignView ()
    {
        return view('newCampaign');
    }


    public function campaignView(Request $request)
    {   
        $empresaId = Auth::user()->empresa_id;

        $campanhas = Campanha::where('empresa_id', $empresaId)
            ->with(['cupom']) 
            ->get();

        foreach ($campanhas as $campanha) {
            if (!$campanha->cupom->isEmpty()) {
                $cuponsUtilizados = $campanha->cupom->filter(function ($cupom) use ($campanha) {
                    return $cupom->qtd_uso <= $campanha->qtd_uso_cupom; 
                })->sum('qtd_uso'); 
                $campanha->cupons_utilizados = $cuponsUtilizados;
            } else {
                $campanha->cupons_utilizados = 0;
            }
        }

        return view('campaign', compact('campanhas'));
    }

    public function getCoupons(Request $request)
    {
        $campanhaId = $request->input('campanhaId');

        $campanha = Campanha::with('cupom')->find($campanhaId);

        return response()->json(['coupons' => $campanha->cupom]);
    }

    public function getCampaignDetails(Request $request)
    {
        $campanhaId = $request->input('campanhaId');

        $campanha = Campanha::where('campanha_id', $campanhaId)
        ->with('cupom')
        ->with('tipoDesconto')
        ->first();

        $cuponsUtilizados = Cupom::where('campanha_id', $campanhaId)
        ->where('ativo', false)
        ->count();


        if ($campanha) {
            $campanha->data_inicio = Carbon::parse($campanha->data_inicio)->format('d/m/Y');
            $campanha->data_final = Carbon::parse($campanha->data_final)->format('d/m/Y');

            $tipo = $campanha->tipoDesconto->descricao; 

            if ($tipo === 'porcentagem') {
                $tipo = '%';
            } else {
                $tipo = 'R$';
            }

            return response()->json(['success' => true,'campanha' => $campanha,'cuponsUtilizados' => $cuponsUtilizados,'tipoDesconto' => $tipo
            ]);
        } else {
            return response()->json(['success' => false,'message' => 'Campanha não encontrada']);
        }
    }
    
    public function editCampaign($campanhaId)
    {   
        $campanha = Campanha::where('campanha_id', $campanhaId)->first();
    
        return view('editCampaign', compact('campanha'));
    }

    public function saveCampaign(Request $request)
    {
        //PARTE DE CATEGORIA
        $categoriaIngresso = $request->input('categoriaIngresso');
        $codigo = $request->input('codigo');
        $quantidadeDisponivel = $request->input('quantidadeDisponivel');

        // Captura os dados da requisição
        $descricao = $request->input('descricao');
        $dataInicial = $request->input('dataInicial');
        $dataFinal = $request->input('dataFinal');
        $tipoDesconto = $request->input('tipoDesconto');
        $desconto = $request->input('desconto');
        $dataCreation = $request->input('dataCreation');
        $numberLenghtCoupon = $request->input('numberLenghtCoupon');
        $qtdCoupons = $request->input('qtdCoupons');
        $qtdTicketsCoupon = $request->input('qtdTicketsCoupon');
        $maxLimitDay = $request->input('maxLimitDay');
        $sigla = $request->input('sigla');
        $minimoCompras = $request->input('minimoCompras');
        $maximoDesconto = $request->input('maximoCompras');

        $nomeEmpresa = Session::get('empresa');
        $empresa = Empresa::where('nome_fantasia', $nomeEmpresa)->firstOrFail();
        $user = Auth::user();

        if($tipoDesconto === '%'){
            $tipoDesconto = 1;
        } else{
            $tipoDesconto = 2;
        }

        DB::beginTransaction();

        try {
            $Campanha = new Campanha();
            $Campanha->empresa_id = $empresa->empresa_id;
            $Campanha->usuario_id = $user->usuario_id;
            $Campanha->data_criacao = $dataCreation;
            $Campanha->data_inicio = $dataInicial;
            $Campanha->data_final = $dataFinal;
            $Campanha->tipo_desconto_id = $tipoDesconto;
            $Campanha->valor_desconto = $desconto;
            $Campanha->status = true;
            $Campanha->nome = $descricao;
            $Campanha->qtd_cupons = $qtdCoupons;
            $Campanha->qtd_digitos_cupom = $numberLenghtCoupon;
            $Campanha->sigla_inicial_cupom = $sigla;
            $Campanha->valor_min_compras = $minimoCompras;
            $Campanha->valor_max_desconto = $maximoDesconto;
            $Campanha->qtd_uso_cupom = $qtdTicketsCoupon;
            $Campanha->limite_max_diario = $maxLimitDay;
            $Campanha->contador_limite_diario = 0;

            $Campanha->save();
            $campanhaId = $Campanha->campanha_id;

            $this->createCoupons($campanhaId, $qtdCoupons, $sigla, $numberLenghtCoupon);

            DB::commit();

            return response()->json(['success' => 'Campanha e cupons criados com sucesso!']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json(['error' => 'Erro ao criar a campanha: ' . $e->getMessage()], 500);
        }
    }
    
    private function createCoupons($campaignId, $qtdCoupons, $sigla, $numberLenghtCoupon)
    {
        for ($i = 0; $i < $qtdCoupons; $i++) {
            do {
                $codigoAleatorio = strtoupper(Str::random($numberLenghtCoupon));
                $chaveCupom = $sigla . $codigoAleatorio;
                $cupomExistente = Cupom::where('chave', $chaveCupom)->exists();
            } while ($cupomExistente);

            $cupom = new Cupom();
            $cupom->chave = $chaveCupom;
            $cupom->ativo = true;
            $cupom->campanha_id = $campaignId;
            $cupom->qtd_uso = 0;
            
            $cupom->save();
        }
    }

    public function campaignUpdate(Request $request)
    {   
        $ativo = $request->input('ativo');
        $campanhaId = $request->input('campanhaId');
        $descricao = $request->input('descricao');
        $dataInicial = $request->input('dataInicial');
        $dataFinal = $request->input('dataFinal');
        $tipoDesconto = $request->input('tipoDesconto');
        $desconto = $request->input('desconto');
        $qtdTicketsCoupon = $request->input('qtdTicketsCoupon');
        $maxLimitDay = $request->input('maxLimitDay');
        $minimoCompras = $request->input('minimoCompras');
        $maximoDesconto = $request->input('maximoCompras');

        $nomeEmpresa = Session::get('empresa');
        $empresa = Empresa::where('nome_fantasia', $nomeEmpresa)->firstOrFail();
        $user = Auth::user();
        $campanha = Campanha::where('campanha_id', $campanhaId)->first();

        if($tipoDesconto == '%'){
            $tipoDesconto = 1;
        } else{
            $tipoDesconto = 2;
        }

        DB::beginTransaction();

        try {
            $campanha->data_inicio = $dataInicial;
            $campanha->data_final = $dataFinal;
            $campanha->tipo_desconto_id = $tipoDesconto;
            $campanha->valor_desconto = $desconto;
            $campanha->status = $ativo;
            $campanha->nome = $descricao;
            $campanha->valor_min_compras = $minimoCompras;
            $campanha->valor_max_desconto = $maximoDesconto;
            $campanha->qtd_uso_cupom = $qtdTicketsCoupon;
            $campanha->limite_max_diario = $maxLimitDay;

            $campanha->save();

            DB::commit();

            return response()->json(['success' => 'Campanha salva com sucesso!']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json(['error' => 'Erro ao salvar a campanha: ' . $e->getMessage()], 500);
        }
    }

}
