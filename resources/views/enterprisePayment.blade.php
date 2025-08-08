@include('masks.header', ['css' => 'css/enterprisePayment.css'])

@if ($empresaPagamentoId === 0)
    <div class="alert alert-warning">
        <strong>Atenção:</strong> Nenhuma empresa de pagamento foi configurada para sua conta. 
        Por favor, selecione uma opção abaixo.
    </div>
@endif

<div class="col-11 d-flex flex-column align-items-center mt-5" id="total">
    <div class="col-12 text-end">
        <button class="btn btn-success px-5" type="button" onclick="salvarConfiguracoes()">Salvar</button>
    </div>
    <h2 class="text-center">Configurações da Empresa</h2>
    <div class="d-flex col-12 flex-column ms-5 px-3 mb-1 gap-2">
        <label class="text-start">Empresas de Pagamento</label>
        <select class="input-style select" id="paymentEnterprise" name="paymentEnterprise" style="width: 200px;">
            <option value="" {{ $empresaPagamentoId == 0 ? 'selected' : '' }}>Selecione uma empresa</option>
            @foreach ($empresasPagamento as $empresa)
                <option value="{{ $empresa->empresa_pagamento_id }}"
                    {{ $empresa->empresa_pagamento_id == $empresaPagamentoId ? 'selected' : '' }}>
                    {{ $empresa->descricao }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-10 mt-4">
        <div id="efipay" class="payment-details" style="display: {{ $empresaPagamentoId == 1 ? 'block' : 'none' }};">
            <h4>Configurações EfiPay</h4>
            <div class="d-flex px-5 col-12 justify-content-between">
                <div class="my-4 col-12 col-md-6">
                    <div class="d-flex flex-column">
                        <div class="d-flex">
                            <h5>Homologação</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input mx-1" id="toggle_homologacao" type="checkbox" role="switch"
                                    onchange="toggleModo('homologacao')"
                                    @checked($empresaPagamento->efipayParametro->homologacao ?? false)>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Client ID</label>
                            <input type="text" class="input form-cont col-6" id="client_id_homologacao" name="client_id_homologacao"
                                value="{{ $empresaPagamento->efipayParametro->client_id_homologacao ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label>Client Secret</label>
                            <input type="text" class="input form-cont col-6" id="client_secret_homologacao" name="client_secret_homologacao"
                                value="{{ $empresaPagamento->efipayParametro->client_secret_homologacao ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="my-4 col-12 col-md-6">
                    <div class="d-flex flex-column">
                        <div class="d-flex">
                            <h5>Produção</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input mx-1" id="toggle_producao" type="checkbox" role="switch"
                                    onchange="toggleModo('producao')"
                                    @checked($empresaPagamento->efipayParametro->producao ?? false)>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Client ID</label>
                            <input type="text" class="input form-cont col-6" id="client_id_producao" name="client_id_producao"
                                value="{{ $empresaPagamento->efipayParametro->client_id_producao ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label>Client Secret</label>
                            <input type="text" class="input form-cont col-6" id="cliente_secret_producao" name="cliente_secret_producao"
                                value="{{ $empresaPagamento->efipayParametro->cliente_secret_producao ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex px-5 flex-column col-12">
                <h5>Certificados</h5>
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-column mb-2 col-6">
                        <label>Certificado (Homologação)</label>
                        <input type="file" class="input form-cont col-6" id="certificado_homologacao_file" accept=".pem, .p12"
                            onchange="mostrarNomeArquivo('homologacao')">
                        <small id="certificado_homologacao_nome" class="mt-1 text-muted">
                            Arquivo atual: {{ basename($empresaPagamento->efipayParametro->certificado_homologacao ?? 'Nenhum arquivo escolhido') }}
                        </small>
                        <button class="btn btn-success col-6" type="button" onclick="uploadCertificado('homologacao')">Enviar Certificado</button>
                    </div>
                    <div class="d-flex flex-column mb-2 col-6">
                        <label>Certificado (Produção)</label>
                        <input type="file" class="input form-cont col-6" id="certificado_producao_file"
                            onchange="mostrarNomeArquivo('producao')">
                        <small id="certificado_producao_nome" class="mt-1 text-muted">
                            Arquivo atual: {{ basename($empresaPagamento->efipayParametro->certificado_producao ?? 'Nenhum arquivo escolhido') }}
                        </small>
                        <button class="btn btn-success col-6" type="button" onclick="uploadCertificado('producao')">Enviar Certificado</button>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column px-5 my-4 col-12">
                <h5>Outras Configurações</h5>
                <div class="d-flex flex-column">
                    <div class="mb-2">
                        <label>Identificador da Conta</label>
                        <input type="text" class="input form-cont col-5" id="identificador_conta" name="identificador_conta"
                            value="{{ $empresaPagamento->efipayParametro->identificador_conta ?? '' }}">
                    </div>
                    <div class="mb-2">
                        <label>Rota PIX</label>
                        <input type="text" class="input form-cont col-5" id="pix_rota" name="pix_rota"
                            value="{{ $empresaPagamento->efipayParametro->pix_rota ?? '' }}">
                    </div>
                    <div class="mb-2">
                        <label>Rota Cartão</label>
                        <input type="text" class="input form-cont col-5" id="cartao_rota" name="cartao_rota"
                            value="{{ $empresaPagamento->efipayParametro->cartao_rota ?? '' }}">
                    </div>
                    <div class="mb-2">
                        <label>Rota Boleto</label>
                        <input type="text" class="input form-cont col-5" id="boleto_rota" name="boleto_rota"
                            value="{{ $empresaPagamento->efipayParametro->boleto_rota ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
        <div id="empresa2Div" class="payment-details" style="display: {{ $empresaPagamentoId == 2 ? 'block' : 'none' }};">
            <h4>Configurações Pagar.me</h4>
            <div class="d-flex px-5 col-12 justify-content-between align-items-start">
                <div class="my-4 col-12 col-md-6">
                    <div class="d-flex flex-column">
                        <div class="d-flex">
                            <h5>Homologação</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input mx-1" id="toggle_homologacao_pagarme" type="checkbox" role="switch"
                                    onchange="toggleModo('homologacao_pagarme')"
                                    @checked($empresaPagamento->pagarmeParametro->homologacao ?? false)>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Conta Pagar.me ID</label>
                            <input type="text" class="input form-cont col-6" id="conta_pagarme_id" name="conta_pagarme_id"
                                value="{{ $empresaPagamento->pagarmeParametro->conta_pagarme_id ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label>API Key</label>
                            <input type="text" class="input form-cont col-6" id="api_key" name="api_key"
                                value="{{ $empresaPagamento->pagarmeParametro->api_key ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label>Chave de Desenvolvimento</label>
                            <input type="text" class="input form-cont col-6" id="chave_desenvolvimento" name="chave_desenvolvimento"
                                value="{{ $empresaPagamento->pagarmeParametro->chave_desenvolvimento ?? '' }}">
                        </div>
                    </div>
                </div>
                <div class="my-4 col-12 col-md-6">
                    <div class="d-flex flex-column">
                        <div class="d-flex">
                            <h5>Produção</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input mx-1" id="toggle_producao_pagarme" type="checkbox" role="switch"
                                    onchange="toggleModo('producao_pagarme')"
                                    @checked($empresaPagamento->pagarmeParametro->producao ?? false)>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Chave de Produção</label>
                            <input type="text" class="input form-cont col-6" id="chave_producao" name="chave_producao"
                                value="{{ $empresaPagamento->pagarmeParametro->chave_producao ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="empresa3Div" class="payment-details" style="display: {{ $empresaPagamentoId == 3 ? 'block' : 'none' }};">
            <p>Detalhes para Empresa 3</p>
        </div>
    </div>
</div>

<!-- Modais -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Mensagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="feedbackMessage"></div>
            <div class="modal-footer">
                <button id="closeModal" type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

@include('masks.footer')

<script>
let empresaPag = {{ $empresaPagamentoId }};

document.addEventListener('DOMContentLoaded', function () {
    togglePaymentDetails();
    document.getElementById('paymentEnterprise').addEventListener('change', togglePaymentDetails);
});

function togglePaymentDetails() {
    const selectElement = document.getElementById('paymentEnterprise');
    const selectedValue = selectElement.value;

    // Oculta todos os blocos de detalhes
    document.querySelectorAll('.payment-details').forEach(div => {
        div.style.display = 'none';
    });

    // Exibe o bloco correspondente ao valor selecionado
    if (selectedValue === '1') {
        document.getElementById('efipay').style.display = 'block';
        empresaPag = 1;
    } else if (selectedValue === '2') {
        document.getElementById('empresa2Div').style.display = 'block';
        empresaPag = 2;
    } else if (selectedValue === '3') {
        document.getElementById('empresa3Div').style.display = 'block';
        empresaPag = 3;
    } else {
        empresaPag = 0;
    }
}

function mostrarNomeArquivo(tipo) {
    const inputFile = document.getElementById(`certificado_${tipo}_file`);
    const nomeArquivo = document.getElementById(`certificado_${tipo}_nome`);

    if (inputFile.files.length > 0) {
        nomeArquivo.textContent = "Arquivo selecionado: " + inputFile.files[0].name;
    } else {
        nomeArquivo.textContent = "Nenhum arquivo escolhido";
    }
}

function uploadCertificado(tipo) {
    const inputFile = document.getElementById(`certificado_${tipo}_file`);
    const formData = new FormData();
    formData.append('certificado', inputFile.files[0]);
    formData.append('tipo', tipo);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("uploadCertificate") }}', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            const feedbackMessage = document.getElementById('feedbackMessage');
            feedbackMessage.innerHTML = data.success ? 'Certificado enviado com sucesso!' : 'Erro ao enviar o certificado.';
            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show();

            const closeButton = document.getElementById('closeModal');
            const modalCloseButton = document.querySelector('#feedbackModal .btn-close');
            const reloadPage = () => location.reload();

            closeButton.addEventListener('click', reloadPage, { once: true });
            modalCloseButton.addEventListener('click', reloadPage, { once: true });
        })
        .catch(error => {
            console.error('Erro:', error);
            const feedbackMessage = document.getElementById('feedbackMessage');
            feedbackMessage.innerHTML = 'Erro ao enviar o certificado. Tente novamente mais tarde.';
            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show();
        });
}

function toggleModo(modo) {
    if (modo === 'homologacao') {
        document.getElementById('toggle_producao').checked = false;
        document.getElementById('toggle_homologacao').checked = true;
    } else if (modo === 'producao') {
        document.getElementById('toggle_homologacao').checked = false;
        document.getElementById('toggle_producao').checked = true;
    } else if (modo === 'homologacao_pagarme') {
        document.getElementById('toggle_producao_pagarme').checked = false;
        document.getElementById('toggle_homologacao_pagarme').checked = true;
    } else if (modo === 'producao_pagarme') {
        document.getElementById('toggle_homologacao_pagarme').checked = false;
        document.getElementById('toggle_producao_pagarme').checked = true;
    }
}

function salvarConfiguracoes() {
    if (!empresaPag) {
        const feedbackMessage = document.getElementById('feedbackMessage');
        feedbackMessage.innerHTML = 'Por favor, selecione uma empresa de pagamento antes de salvar.';
        const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
        feedbackModal.show();
        return;
    }

    if (empresaPag == 1) {
        salvarEfiPay();
    } else if (empresaPag == 2) {
        salvarPagarme();
    }
}

function salvarEfiPay() {
    let formData = {
        client_id_homologacao: $('#client_id_homologacao').val(),
        client_secret_homologacao: $('#client_secret_homologacao').val(),
        client_id_producao: $('#client_id_producao').val(),
        client_secret_producao: $('#cliente_secret_producao').val(),
        identificador_conta: $('#identificador_conta').val(),
        pix_rota: $('#pix_rota').val(),
        cartao_rota: $('#cartao_rota').val(),
        boleto_rota: $('#boleto_rota').val(),
        homologacao: $('#toggle_homologacao').prop('checked'),
        producao: $('#toggle_producao').prop('checked'),
        empresa_pagamento_id: empresaPag
    };

    $.ajax({
        url: '{{ route("saveEfiConfigPayment") }}',
        type: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            const feedbackMessage = document.getElementById('feedbackMessage');
            feedbackMessage.innerHTML = response.success ? response.message : response.error;
            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show();
        },
        error: function (xhr) {
            const feedbackMessage = document.getElementById('feedbackMessage');
            feedbackMessage.innerHTML = xhr.responseJSON?.message || 'Erro ao salvar configurações.';
            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show();
        }
    });
}

function salvarPagarme() {
    let formData = {
        conta_pagarme_id: $('#conta_pagarme_id').val(),
        api_key: $('#api_key').val(),
        chave_desenvolvimento: $('#chave_desenvolvimento').val(),
        chave_producao: $('#chave_producao').val(),
        homologacao: $('#toggle_homologacao_pagarme').prop('checked') ? true : false,
        producao: $('#toggle_producao_pagarme').prop('checked') ? true : false,
        empresa_pagamento_id: empresaPag
    };

    if (formData.homologacao) {
        formData.producao = false;
    } else if (formData.producao) {
        formData.homologacao = false;
    }

    $.ajax({
        url: '{{ route("savePagarmeConfigPayment") }}',
        type: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            const feedbackMessage = document.getElementById('feedbackMessage');
            feedbackMessage.innerHTML = response.success ? response.message : response.error;
            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show();
             if (response.success) {
                const closeButton = document.getElementById('closeModal');
                const modalCloseButton = document.querySelector('#feedbackModal .btn-close');
                const reloadPage = () => location.reload();

                closeButton.addEventListener('click', reloadPage, { once: true });
                modalCloseButton.addEventListener('click', reloadPage, { once: true });
            }
        },
        error: function (xhr) {
            const feedbackMessage = document.getElementById('feedbackMessage');
            feedbackMessage.innerHTML = xhr.responseJSON?.message || 'Erro ao salvar configurações da Pagar.me.';
            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show();
        }
    });
}
</script>