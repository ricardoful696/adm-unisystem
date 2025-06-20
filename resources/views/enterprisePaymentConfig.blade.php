@include('masks.header', ['css' => 'css/configuration.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex col-12 my-4">
        <div class="d-flex flex-column justify-content-center col-12 px-4">
            <div class="col-12 d-flex flex-column justify-content-center mt-4">
                <div class="d-flex col-11 justify-content-end align-items-end ">
                    <button id="saveButton"  class="btn btn-success px-5">Salvar</button>
                </div>
                <h4 class="text-center">Configurações de Pagamento</h4>
                <div class="d-flex col-12 gap-3 justify-content-center align-items-center">
                    <div class="d-flex flex-column col-5">
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Chave Pix</label>
                            <input class="input-style col-5 form-cont" type="text" id="pixKey"
                                value="{{ $config->chave_pix ?? '' }}">
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Aceita Pix</label>
                            <select class="input-style col-5 form-cont" id="acceptPix">
                                <option value="true" {{ $config->aceita_pix == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->aceita_pix == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Aceita Cartão</label>
                            <select class="input-style col-5 form-cont" id="acceptCard">
                                <option value="true" {{ $config->aceita_cartao == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->aceita_cartao == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Aceita Boleto</label>
                            <select class="input-style col-5 form-cont" id="acceptBoleto">
                                <option value="true" {{ $config->aceita_boleto == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->aceita_boleto == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Quantidade Max de Parcelas no Cartão</label>
                            <select class="input-style col-5 form-cont" id="maxCardInstallments">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $config->parcelas_cartao_max == $i ? 'selected' : '' }}>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-column col-5">
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Aceita Mastercard</label>
                            <select class="input-style col-5 form-cont" id="acceptMastercard">
                                <option value="true" {{ $config->cartao_mastercard == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->cartao_mastercard == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Aceita Cielo</label>
                            <select class="input-style col-5 form-cont" id="acceptCielo">
                                <option value="true" {{ $config->cartao_cielo == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->cartao_cielo == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Aceita Visa</label>
                            <select class="input-style col-5 form-cont" id="acceptVisa">
                                <option value="true" {{ $config->cartao_visa == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->cartao_visa == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Aceita Amex</label>
                            <select class="input-style col-5 form-cont" id="acceptAmex">
                                <option value="true" {{ $config->cartao_amex == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->cartao_amex == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Aceita Elo</label>
                            <select class="input-style col-5 form-cont" id="acceptElo">
                                <option value="true" {{ $config->cartao_elo == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->cartao_elo == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
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
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


@include('masks.footer')

<style>
    .error-message {
        color: red;
        font-size: 0.875rem;
        margin-top: 4px;
    }
</style>


<script>
    $(document).ready(function () {

        $('#saveButton').on('click', function () {
            // Limpa mensagens de erro anteriores
            $('.error-message').remove();

            let isValid = true;

            const pixKey = $('#pixKey').val().trim();
            const acceptPix = $('#acceptPix').val();
            const acceptCard = $('#acceptCard').val();
            const acceptBoleto = $('#acceptBoleto').val();
            const acceptMastercard = $('#acceptMastercard').val();
            const acceptCielo = $('#acceptCielo').val();
            const acceptVisa = $('#acceptVisa').val();
            const acceptAmex = $('#acceptAmex').val();
            const acceptElo = $('#acceptElo').val();
            const maxCardInstallments = $('#maxCardInstallments').val();

            // 🔥 Validação da chave Pix
            if (!pixKey) {
                $('#pixKey').after('<div class="error-message">O campo Chave Pix é obrigatório.</div>');
                isValid = false;
            }

            // ⚠️ Se houver erro, não executa o Ajax
            if (!isValid) return;

            // Envia os dados se tudo estiver válido
            $.ajax({
                url: '{{ route('savePaymentConfiguration') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    pixKey: pixKey,
                    acceptPix: acceptPix,
                    acceptCard: acceptCard,
                    acceptBoleto: acceptBoleto,
                    acceptMastercard: acceptMastercard,
                    acceptCielo: acceptCielo,
                    acceptVisa: acceptVisa,
                    acceptAmex: acceptAmex,
                    acceptElo: acceptElo,
                    maxCardInstallments: maxCardInstallments
                },
                success: function (response) {
                    const message = response.success
                        ? 'Configurações salvas com sucesso!'
                        : 'Erro ao salvar as configurações!';
                    showModal(message);
                },
                error: function (xhr, status, error) {
                    console.error('Erro: ', error);
                    showModal('Ocorreu um erro. Tente novamente.');
                }
            });
        });

        function showModal(message) {
            $('#feedbackMessage').text(message);
            $('#feedbackModal').modal('show');

            $('#feedbackModal').on('hidden.bs.modal', function () {
                location.reload();
            });
        }

        // ✅ Remove erro ao começar a digitar no campo Pix
        $('#pixKey').on('input', function () {
            $(this).next('.error-message').remove();
        });
    });
</script>
