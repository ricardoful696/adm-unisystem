@include('masks.header', ['css' => 'css/configuration.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex col-12 my-4">
        <div class="d-flex flex-column justify-content-center col-12 px-4">
            <div class="col-12 d-flex flex-column justify-content-center">
                <div class="d-flex col-11 justify-content-end align-items-end my-4">
                    <button id="saveButton" style="width: 120px;" class="btn btn-success">Salvar</button>
                </div>
                <h4 class="text-center">Configurações da Empresa</h4>
                <div class="d-flex justify-content-centera align-items-center col-12">
                    <div class="d-flex flex-column col-5">
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Usa Voucher</label>
                            <select class="input-style col-5 form-cont" id="useVoucher">
                                <option value="true" {{ $config->usa_voucher == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->usa_voucher == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Validação do Cadastro por Email</label>
                            <select class="input-style col-5 form-cont" id="emailValidation">
                                <option value="true" {{ $config->validacao_email == 1 ? 'selected' : '' }}>Habilitado
                                </option>
                                <option value="false" {{ $config->validacao_email == 0 ? 'selected' : '' }}>Desabilitado
                                </option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Aceita Cupom Desconto</label>
                            <select class="input-style col-5 form-cont" id="acceptDiscountCoupon">
                                <option value="true" {{ $config->cupom_desconto == 1 ? 'selected' : '' }}>Sim</option>
                                <option value="false" {{ $config->cupom_desconto == 0 ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Valor Max Diário de Vendas</label>
                            <div class="d-flex gap-1">
                                <input class="col-1 " type="checkbox" id="maxDaySaleCheckbox" {{ $config->valor_max_diario_venda_ativo ? 'checked' : '' }}>
                                <input class="input-style col-11 form-cont" type="number" id="maxDaySale"
                                    value="{{ $config->valor_max_diario_venda ?? '' }}" disabled>
                            </div>
                        </div>
                        <div class="d-flex col-12 flex-column justify-content-center align-items-center mb-1 gap-2">
                            <label class="text-start">Valor Max Diário por Visitante de Compras</label>
                            <div class="d-flex gap-1">
                                <input class="col-1 " type="checkbox" id="maxVisitorBuyCheckbox" {{ $config->valor_max_diario_compra_visitante_ativo ? 'checked' : '' }}>
                                <input class="input-style col-11 form-cont" type="number" id="maxVisitorBuy"
                                    value="{{ $config->valor_max_diario_compra_visitante ?? '' }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex col-6 flex-column justify-content-center align-items-center mb-1 gap-2">
                        <label class="text-start">Política de Privacidade</label>
                        <textarea style="height: 200px;" class="input-style col-12 form-cont"
                            id="privacyPolicy">{{ $config->politica_privacidade ?? '' }}</textarea>
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

<script>
    $(document).ready(function () {
        $('#maxDaySaleCheckbox').on('change', function () {
            $('#maxDaySale').prop('disabled', !this.checked);
        });

        $('#maxVisitorBuyCheckbox').on('change', function () {
            $('#maxVisitorBuy').prop('disabled', !this.checked);
        });

        $('#saveButton').on('click', function () {
            let maxDaySale = $('#maxDaySale').val();
            let maxVisitorBuy = $('#maxVisitorBuy').val();
            let emailValidation = $('#emailValidation').val();
            let privacyPolicy = $('#privacyPolicy').val();
            let useVoucher = $('#useVoucher').val();
            let acceptDiscountCoupon = $('#acceptDiscountCoupon').val();
            let maxDaySaleActive = $('#maxDaySaleCheckbox').prop('checked');
            let maxVisitorBuyActive = $('#maxVisitorBuyCheckbox').prop('checked');

            $.ajax({
                url: '{{ route('saveConfiguration') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    maxDaySale: maxDaySale,
                    maxVisitorBuy: maxVisitorBuy,
                    maxDaySaleActive: maxDaySaleActive,
                    maxVisitorBuyActive: maxVisitorBuyActive,
                    emailValidation: emailValidation,
                    privacyPolicy: privacyPolicy,
                    useVoucher: useVoucher,
                    acceptDiscountCoupon: acceptDiscountCoupon,
                },
                success: function (response) {
                    let message = response.success ?
                        'Configurações salvas com sucesso!' :
                        'Erro ao salvar as configurações!';
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

            // Recarregar página ao fechar o modal
            $('#feedbackModal').on('hidden.bs.modal', function () {
                location.reload();
            });
        }
    });
</script>