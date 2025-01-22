<?php

namespace App\Http\Controllers;

use App\Models\EfipayParametro;
use App\Models\Empresa;
use App\Models\EmpresaPagamento;
use App\Models\ParametroEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfigurationController extends Controller
{
    
    public function configurationView()
    {
        $empresaId = Auth::user()->empresa_id;

        $config = ParametroEmpresa::where('empresa_id', $empresaId)->first();

        if (!$config) {
            $config = new ParametroEmpresa();
            $config->valor_max_diario_venda = null;
            $config->valor_max_dirario_compra_visitante = null;
            $config->validacao_email = false;
            $config->politica_privacidade = '';
            $config->aceita_pix = false;
            $config->aceita_cartao = false;
            $config->aceita_boleto = false;
            $config->usa_voucher = false;
            $config->cartao_mastercard = false;
            $config->cartao_cielo = false;
            $config->cartao_visa = false;
            $config->cartao_amex = false;
            $config->cartao_elo = false;
            $config->parcelas_cartao_max = 1;
            $config->cupom_desconto = false;
            $config->chave_pix = '';
        }

        return view('configuration', ['config' => $config]);
    }

    public function enterprisePaymentView()
    {
        $empresaId = Auth::user()->empresa_id;
        $empresa = Empresa::where('empresa_id', $empresaId)
        ->with('parametroEmpresa')
        ->first();
        $empresaPagamentoId = $empresa->parametroEmpresa->empresa_pagamento_id;
        $empresaPagamento = EmpresaPagamento::where('empresa_pagamento_id', $empresaPagamentoId)->first();
        $empresasPagamento = EmpresaPagamento::all(); 
    
        $config = ParametroEmpresa::where('empresa_id', $empresaId)->first();
    
        if (!$config) {
            $config = new ParametroEmpresa();
            $config->valor_max_diario_venda = null;
            $config->valor_max_dirario_compra_visitante = null;
            $config->validacao_email = false;
            $config->politica_privacidade = '';
            $config->aceita_pix = false;
            $config->aceita_cartao = false;
            $config->aceita_boleto = false;
            $config->usa_voucher = false;
            $config->cartao_mastercard = false;
            $config->cartao_cielo = false;
            $config->cartao_visa = false;
            $config->cartao_amex = false;
            $config->cartao_elo = false;
            $config->parcelas_cartao_max = 1;
            $config->cupom_desconto = false;
            $config->chave_pix = '';
        }
    
        return view('enterprisePaymentConfig', [
            'config' => $config,
            'empresaPagamento' => $empresaPagamento,
            'empresasPagamento' => $empresasPagamento,
        ]);
    }
    
    public function saveConfiguration(Request $request)
    {
        $maxDaySale = $request->input('maxDaySale');
        $maxVisitorBuy = $request->input('maxVisitorBuy');
        $emailValidation = filter_var($request->input('emailValidation'), FILTER_VALIDATE_BOOLEAN);
        $privacyPolicy = $request->input('privacyPolicy');
        $useVoucher = filter_var($request->input('useVoucher'), FILTER_VALIDATE_BOOLEAN);
        $acceptDiscountCoupon = filter_var($request->input('acceptDiscountCoupon'), FILTER_VALIDATE_BOOLEAN);
        $maxDaySaleActive = filter_var($request->input('maxDaySaleActive'), FILTER_VALIDATE_BOOLEAN);
        $maxVisitorBuyActive = filter_var($request->input('maxVisitorBuyActive'), FILTER_VALIDATE_BOOLEAN);

        $empresaId = Auth::user()->empresa_id;
    
        DB::beginTransaction();
    
        try {
            $config = ParametroEmpresa::where('empresa_id', $empresaId)->first();
    
            if (!$config) {
                $config = new ParametroEmpresa();
                $config->empresa_id = $empresaId;
                $config->empresa_pagamento_id = null;
                $config->aceita_pix = null;
                $config->aceita_cartao = null;
                $config->aceita_boleto = null;
                $config->cartao_mastercard = null;
                $config->cartao_cielo = null;
                $config->cartao_visa = null;
                $config->cartao_amex = null;
                $config->cartao_elo = null;
                $config->parcelas_cartao_max = null;
                $config->chave_pix = null;
            }
    
            $config->politica_privacidade = $privacyPolicy;
            $config->usa_voucher = $useVoucher;
            $config->cupom_desconto = $acceptDiscountCoupon;
            $config->valor_max_diario_venda_ativo = $maxDaySaleActive;
            $config->valor_max_diario_compra_visitante_ativo = $maxVisitorBuyActive;
            $config->valor_max_diario_venda = $maxDaySale;
            $config->valor_max_diario_compra_visitante = $maxVisitorBuy;
            $config->validacao_email = $emailValidation;
    
            $config->save();
    
            DB::commit();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function savePaymentConfiguration(Request $request)
    {   
        $paymentEnterprise = $request->input('paymentEnterprise');
        $acceptPix = filter_var($request->input('acceptPix'), FILTER_VALIDATE_BOOLEAN);
        $acceptCard = filter_var($request->input('acceptCard'), FILTER_VALIDATE_BOOLEAN);
        $acceptBoleto = filter_var($request->input('acceptBoleto'), FILTER_VALIDATE_BOOLEAN);
        $acceptMastercard = filter_var($request->input('acceptMastercard'), FILTER_VALIDATE_BOOLEAN);
        $acceptCielo = filter_var($request->input('acceptCielo'), FILTER_VALIDATE_BOOLEAN);
        $acceptVisa = filter_var($request->input('acceptVisa'), FILTER_VALIDATE_BOOLEAN);
        $acceptAmex = filter_var($request->input('acceptAmex'), FILTER_VALIDATE_BOOLEAN);
        $acceptElo = filter_var($request->input('acceptElo'), FILTER_VALIDATE_BOOLEAN);
        $maxCardInstallments = $request->input('maxCardInstallments');
        $pixKey = $request->input('pixKey');
    
        $empresaId = Auth::user()->empresa_id;
    
        DB::beginTransaction();
    
        try {
            $config = ParametroEmpresa::where('empresa_id', $empresaId)->first();
    
            if (!$config) {
                $config = new ParametroEmpresa();
                $config->empresa_id = $empresaId;
                $config->empresa_pagamento_id = $paymentEnterprise;
                $config->usa_voucher = null;
                $config->cupom_desconto = null;
                $config->valor_max_diario_venda = null;
                $config->valor_max_diario_compra_visitante = null;
                $config->validacao_email = null;
            }
    
            $config->aceita_pix = $acceptPix;
            $config->aceita_cartao = $acceptCard;
            $config->aceita_boleto = $acceptBoleto;
            $config->cartao_mastercard = $acceptMastercard;
            $config->cartao_cielo = $acceptCielo;
            $config->cartao_visa = $acceptVisa;
            $config->cartao_amex = $acceptAmex;
            $config->cartao_elo = $acceptElo;
            $config->parcelas_cartao_max = $maxCardInstallments;
            $config->chave_pix = $pixKey;
            
            $config->save();
    
            DB::commit();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function saveEfiConfigPayment(Request $request)
    {
        $clientIdHomologacao = $request->input('client_id_homologacao');
        $clientSecretHomologacao = $request->input('client_secret_homologacao');
        $clientIdProducao = $request->input('client_id_producao');
        $clientSecretProducao = $request->input('client_secret_producao');
        $identificadorConta = $request->input('identificador_conta');
        $pixRota = $request->input('pix_rota');
        $cartaoRota = $request->input('cartao_rota');
        $boletoRota = $request->input('boleto_rota');
        
        $empresaId = Auth::user()->empresa_id;
        
        DB::beginTransaction();
        
        try {
            $config = EfipayParametro::where('empresa_id', $empresaId)->first();

            if (!$config) {
                $config = new EfipayParametro();
                $config->empresa_id = $empresaId; 
            }

            $config->client_id_homologacao = $clientIdHomologacao;
            $config->client_secret_homologacao = $clientSecretHomologacao;
            $config->client_id_producao = $clientIdProducao;
            $config->client_secret_producao = $clientSecretProducao;
            $config->identificador_conta = $identificadorConta;
            $config->pix_rota = $pixRota;
            $config->cartao_rota = $cartaoRota;
            $config->boleto_rota = $boletoRota;

            $config->save(); 

            DB::commit();

            return response()->json([
		    'success' => true,
		    'message' => utf8_encode('Configurações salvas com sucesso!')
		]);
        } catch (\Exception $e) {
		
            DB::rollBack();
            return response()->json(['success' => false, 'error' => 'Erro ao salvar configurações. Tente novamente mais tarde.']);
        }
    }


    public function uploadCertificate(Request $request)
    {
        $empresaId = Auth::user()->empresa_id;
        
        $tipo = $request->input('tipo') === 'homologacao' ? 'certificado_homologacao' : 'certificado_producao';
        
        $empresaCertificado = EfipayParametro::where('empresa_id', $empresaId)->first();
        if (!$empresaCertificado) {
            return response()->json(['success' => false, 'message' => 'Empresa não encontrada.']);
        }
    
        if ($request->hasFile('certificado')) {
            $file = $request->file('certificado');
    
            $extensao = strtolower($file->getClientOriginalExtension());
            if (!in_array($extensao, ['pem', 'p12'])) {
                return response()->json(['success' => false, 'message' => 'Formato de arquivo inválido.']);
            }
    
            $fileName = uniqid() . '.' . $extensao;  
            $path = $file->storeAs('certificados', $fileName, 'public');  
    
            $appUrl = env('APP_URL');
            $fullPath = $appUrl . '/storage/' . $path;
    
            $empresaCertificado->{$tipo} = $fullPath;
            $empresaCertificado->save();
    
            return response()->json([
                'success' => true,
                'message' => "Certificado do tipo '$tipo' salvo com sucesso.",
                'certificado_path' => $fullPath,
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Nenhum arquivo enviado.']);
    }
    


}
