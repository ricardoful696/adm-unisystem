<?php

namespace App\Http\Controllers;

use App\Models\EfipayParametro;
use App\Models\Empresa;
use App\Models\EmpresaPagamento;
use App\Models\PagarmeParametro;
use App\Models\ParametroEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


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
        $ingressoImpresso = filter_var($request->input('ingressoImpresso'), FILTER_VALIDATE_BOOLEAN);
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
            $config->ingresso_impresso = $ingressoImpresso;
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
        $homologacao = $request->input('homologacao'); 
        $producao = $request->input('producao');

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
            $config->homologacao = $homologacao;
            $config->producao = $producao;

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

         public function savePagarmeConfig(Request $request)
        {
            $data = $request->validate([
                'conta_pagarme_id' => 'nullable|string',
                'api_key' => 'nullable|string',
                'chave_desenvolvimento' => 'nullable|string',
                'chave_producao' => 'nullable|string',
                'homologacao' => 'required|in:0,1,true,false',
                'producao' => 'required|in:0,1,true,false',
                'empresa_pagamento_id' => 'required|integer|exists:empresa_pagamento,empresa_pagamento_id',
            ]);

            $empresaId = Auth::user()->empresa_id;

            // Converte valores para booleanos
            $data['homologacao'] = filter_var($data['homologacao'], FILTER_VALIDATE_BOOLEAN);
            $data['producao'] = filter_var($data['producao'], FILTER_VALIDATE_BOOLEAN);

            // Garante que apenas um dos campos seja true
            if ($data['homologacao']) {
                $data['producao'] = false;
            } elseif ($data['producao']) {
                $data['homologacao'] = false;
            }

            DB::beginTransaction();

            try {
                // Verifica se já existe um registro para o empresa_id
                $existingRecord = PagarmeParametro::where('empresa_id', $empresaId)->first();

                Log::debug('Registro existente em pagarme_parametro', [
                    'empresa_id' => $empresaId,
                    'exists' => !is_null($existingRecord),
                    'pagarme_parametro_id' => $existingRecord ? $existingRecord->pagarme_parametro_id : null,
                ]);

                // Atualiza ou cria o registro em parametro_empresa
                ParametroEmpresa::updateOrCreate(
                    ['empresa_id' => $empresaId],
                    ['empresa_pagamento_id' => $data['empresa_pagamento_id']]
                );

                // Atualiza ou cria o registro em pagarme_parametro
                PagarmeParametro::updateOrCreate(
                    ['empresa_id' => $empresaId], // Condição para encontrar o registro
                    [
                        'conta_pagarme_id' => $data['conta_pagarme_id'],
                        'api_key' => $data['api_key'],
                        'chave_desenvolvimento' => $data['chave_desenvolvimento'],
                        'chave_producao' => $data['chave_producao'],
                        'homologacao' => $data['homologacao'],
                        'producao' => $data['producao'],
                        'empresa_pagamento_id' => 2,
                    ]
                );

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Configurações salvas com sucesso!'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                DB::rollBack();
                Log::error('Erro de validação ao salvar configurações Pagar.me', [
                    'error' => $e->getMessage(),
                    'errors' => $e->errors(),
                    'request' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erro ao salvar configurações Pagar.me', [
                    'error' => $e->getMessage(),
                    'request' => $request->all(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Erro ao salvar configurações: ' . $e->getMessage()
                ], 500);
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
    
            $relativePath = 'storage/certificados/' . $fileName;

            $empresaCertificado->{$tipo} = $relativePath;
            $empresaCertificado->save();
    
            return response()->json([
                'success' => true,
                'message' => "Certificado do tipo '$tipo' salvo com sucesso.",
                'certificado_path' => $relativePath,
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Nenhum arquivo enviado.']);
    }

    public function download($filename)
    {
        $extensions = ['.pem', '.p12'];
    
        foreach ($extensions as $extension) {
            $filePath = 'certificados/' . $filename . $extension;
            
            if (Storage::disk('public')->exists($filePath)) {
                return response()->download(storage_path('app/public/' . $filePath));
            }
        }
    
        return response()->json(['message' => 'Arquivo não encontrado'], 404);
    }
    
    public function saveCreditCardPaymentData($usuario, $vendaId, $card_mask, $titular, $bandeira, $status)
    {
        DB::beginTransaction();

        try {
            $cartaoDetalhe = CartaoDetalhe::create([
                'venda_id' => $vendaId,
                'metodo_pagamento_id' => 1,
                'numero' => $card_mask,
                'titular' => $titular,
                'bandeira' => $bandeira
            ]);

            if($status == 'approved'){
                $status_pagamento_descricao_id = 2;

                $statusPagamento = new StatusPagamento();
                $statusPagamento->venda_id = $vendaId;
                $statusPagamento->status_pagamento_descricao_id = 2;
                $statusPagamento->data = Carbon::now('America/Sao_Paulo')->toDateString(); 
                $statusPagamento->hora = Carbon::now('America/Sao_Paulo')->toTimeString(); 
                $statusPagamento->save();

            }else{
                $status_pagamento_descricao_id = 3;

                $statusPagamento = new StatusPagamento();
                $statusPagamento->venda_id = $vendaId;
                $statusPagamento->status_pagamento_descricao_id = 3;
                $statusPagamento->data = Carbon::now('America/Sao_Paulo')->toDateString(); 
                $statusPagamento->hora = Carbon::now('America/Sao_Paulo')->toTimeString(); 
                $statusPagamento->save();
            }
            
            Venda::where('venda_id', $vendaId)->update([
                'metodo_pagamento_id' => 1,
                'status_pagamento_descricao_id' => $status_pagamento_descricao_id 
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pagamento salvo com sucesso.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Erro ao salvar o pagamento: ' . $e->getMessage()], 500);
        }
    }

    
}
