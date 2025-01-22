@include('masks.header', ['css' => 'css/enterprisePayment.css'])


<div class="col-11 d-flex flex-column justify-content-center align-items-center mt-5" id="total" >
    <div class="col-12 text-end">
        <button class="btn btn-success px-5" type="button" onclick="salvarConfiguracoes()">Salvar</button>
    </div>
    <h2 class="text-center">Configurações da Empresa</h2>
    <div class="d-flex col-12 flex-column ms-5 px-3 mb-1 gap-2">
        <label class="text-start">Empresas de Pagamento</label>
        <select class="input-style select" id="paymentEnterprise" name="paymentEnterprise" style="width: 200px;">
            <option value="">Selecione uma empresa</option>
            @foreach ($empresasPagamento as $empresa)
                <option value="{{ $empresa->empresa_pagamento_id }}" @if ($empresa->empresa_pagamento_id == $empresaPagamento->efipayParametro->empresa_pagamento_id) selected
                @endif>
                    {{ $empresa->descricao }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-10 mt-4 justify-content-center align-items-center">
        <div id="efipay" class="payment-details" style="display: none;">
            <h4 class="">Configurações EfiPay</h4>
            <div class="d-flex px-5 col-12  justify-content-between">
                <div class="my-4 col-12 col-md-6">
                    <div class="d-flex flex-column">
                        <h5>Homologação</h5>
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
                        <h5>Produção</h5>
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
            <div class="d-flex px-5 flex-column col-12 ">
                <h5>Certificados</h5>
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-column mb-2 col-6">
                        <label>Certificado (Homologação)</label>
                        <input type="file" class="input form-cont col-6" id="certificado_homologacao_file" accept=".pem, .p12"
                            onchange="mostrarNomeArquivo('homologacao')">
                        <small id="certificado_homologacao_nome" class="mt-1 text-muted">
                            Arquivo atual:
                            {{ basename($empresaPagamento->efipayParametro->certificado_homologacao ?? 'Nenhum arquivo escolhido') }}
                        </small>
                        <button class="btn btn-success col-6" type="button" onclick="uploadCertificado('homologacao')">Enviar
                            Certificado</button>
                    </div>
                    <div class="d-flex flex-column mb-2 col-6">
                        <label>Certificado (Produção)</label>
                        <input type="file" class="input form-cont col-6" id="certificado_producao_file"
                            onchange="mostrarNomeArquivo('producao')">
                        <small id="certificado_producao_nome" class="mt-1 text-muted">
                            Arquivo atual:
                            {{ basename($empresaPagamento->efipayParametro->certificado_producao ?? 'Nenhum arquivo escolhido') }}
                        </small>
                        <button class="btn btn-success col-6" type="button" onclick="uploadCertificado('producao')">Enviar
                            Certificado</button>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column px-5 my-4 col-12">
                <h5>Outras Configurações</h5>
                <div class="d-flex flex-column ">
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
        <div id="empresa2Div" class="payment-details" style="display: none;">
            <p>Detalhes para Empresa 2</p>
        </div>
        <div id="empresa3Div" class="payment-details" style="display: none;">
            <p>Detalhes para Empresa 3</p>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Mensagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="feedbackMessage">
                <!-- Mensagem será inserida dinamicamente -->
            </div>
            <div class="modal-footer">
                <button id="closeModal" type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sucesso -->
<div class="modal" id="modalSuccess" tabindex="-1" aria-labelledby="modalSuccessLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSuccessLabel">Sucesso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage">Configurações salvas com sucesso!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Erro -->
<div class="modal" id="modalError" tabindex="-1" aria-labelledby="modalErrorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalErrorLabel">Erro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="errorMessage">Erro ao salvar configurações. Tente novamente mais tarde.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>



@include('masks.footer')

<script>

    let empresaPag = {{ json_encode($empresaPagamento->efipayParametro->empresa_pagamento_id) }};

    document.addEventListener('DOMContentLoaded', function () {
        togglePaymentDetails();
    });

    function togglePaymentDetails() {
        const selectElement = document.getElementById('paymentEnterprise');
        const selectedValue = selectElement.value;

        document.querySelectorAll('.payment-details').forEach(div => {
            div.style.display = 'none';
        });

        if (selectedValue == "1") {
            document.getElementById('efipay').style.display = 'block';
        } else if (selectedValue == "2") {
            document.getElementById('empresa2Div').style.display = 'block';
        } else if (selectedValue == "3") {
            document.getElementById('empresa3Div').style.display = 'block';
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

        fetch('{{ route('uploadCertificate') }}', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const feedbackMessage = document.getElementById('feedbackMessage');
                if (data.success) {
                    feedbackMessage.innerHTML = 'Certificado enviado com sucesso!';
                } else {
                    feedbackMessage.innerHTML = 'Erro ao enviar o certificado.';
                }
                const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                feedbackModal.show();

                const closeButton = document.getElementById('closeModal');
                const modalCloseButton = document.querySelector('#feedbackModal .btn-close');

                const reloadPage = () => {
                    location.reload(); 
                };

                closeButton.addEventListener('click', reloadPage);
                modalCloseButton.addEventListener('click', reloadPage);
            })
            .catch(error => {
                console.error('Erro:', error);
                const feedbackMessage = document.getElementById('feedbackMessage');
                feedbackMessage.innerHTML = 'Erro ao enviar o certificado. Tente novamente mais tarde.';
                const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                feedbackModal.show();
            });
    }

    function salvarConfiguracoes() {
        if (parseInt(empresaPag) === 1) {
            salvarEfiPay();
        }
    }

    function salvarEfiPay() {

        let formData = {
            client_id_homologacao: $('#client_id_homologacao').val(),
            client_secret_homologacao: $('#client_secret_homologacao').val(),
            client_id_producao: $('#client_id_producao').val(),
            client_secret_producao: $('#client_secret_producao').val(),
            identificador_conta: $('#identificador_conta').val(),
            pix_rota: $('#pix_rota').val(),
            cartao_rota: $('#cartao_rota').val(),
            boleto_rota: $('#boleto_rota').val(),
        };

        $.ajax({
            url: '{{ route("saveEfiConfigPayment") }}', 
            type: "POST", 
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
		    console.log(response); // Verifique se a resposta está correta no console
		    if (response.success) {
		        $('#feedbackMessage').text(response.message);
		        $('#feedbackModal').modal('show');
		    } else {
		        $('#feedbackMessage').text(response.error);
		        $('#feedbackModal').modal('show');
		    }
		},
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON?.message || "Erro ao atualizar usuário.";
                    $('#feedbackMessage').text(errorMessage);
                    $('#feedbackModal').modal('show');
                }
            });
        }


</script>