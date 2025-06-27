@include('masks.header', ['css' => 'css/newCampaign.css']) 

<div class="d-flex flex-column col-11 align-items-center">
    <div class="d-flex justify-content-center flex-column col-12 col-md-11 align-items-center">
        <div class="d-flex justify-content-end col-12 mt-4">
            <button class="save-btn btn btn-success px-5" id="saveCampaign">Salvar</button>
        </div>
        <div class="my-3">
            <h2>Editar Campanha</h2>
        </div>
        <div class="d-flex justify-content-center my-4 gap-2 col-12">
            <div class="col-6 d-flex flex-column justify-content-start align-items-start">
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end">Nome</label>
                    <input class="col-6 input-style form-cont" type="text" id="descricao" placeholder="BLACK FRIDAY"
                        value="{{  $campanha->nome }}" required>
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end">Data Inicial</label>
                    <input class="col-6 input-style form-cont" type="date" id="dataInicial"
                        value="{{  $campanha->data_inicio }}" required>
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end">Data Final</label>
                    <input class="col-6 input-style form-cont" type="date" id="dataFinal"
                        value="{{  $campanha->data_final }}" required>
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end">Tipo de Desconto</label>
                    <select class="col-6 input-style form-cont" id="tipoDesconto" name="tipoDesconto" required>
                        <option value="%" @selected($campanha->tipo_desconto_id == 1)>%</option>
                        <option value="R$" @selected($campanha->tipo_desconto_id == 2)>R$</option>
                    </select>
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end"></label>
                    <input class="col-6 input-style form-cont" type="text" id="desconto"
                        value="{{  $campanha->valor_desconto }}" required>
                </div>
            </div>
            <div class="col-6 ">
                <div class="d-flex justify-content-end mb-1 gap-2 align-items-center">
                    <label class="text-end">Data da Criação</label>
                    <input class="input-style col-5 form-cont" type="date" id="dataCreation"
                        value="{{  $campanha->data_criacao }}" disabled>
                </div>
                <div class="d-flex justify-content-end mb-1 gap-2 align-items-center">
                    <label class="text-end">Quantidade de ingressos a compartilhar por cupom</label>
                    <input class="input-style col-5 form-cont" type="number" id="qtdTicketsCoupon"
                        value="{{  $campanha->qtd_uso_cupom }}" required>
                </div>
                <div class="d-flex justify-content-end mb-1 gap-2 align-items-center">
                    <label class="text-end">Limite de cupons diário</label>
                    <input class="input-style col-5 form-cont" type="number" id="maxLimitDay"
                        value="{{  $campanha->limite_max_diario }}" required>
                </div>
                <div class="d-flex justify-content-end mb-1 gap-2 align-items-center">
                    <input type="checkbox" id="ativo" class="" {{ $campanha->status ? 'checked' : '' }}>
                    <label class="text-end"> Ativo </label>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column justify-content-start col-12 col-md-10">
            <div class="d-flex flex-column">
                <label class="fw-bold label-row">Regra a Aplicar no Desconto</label>
                <div class="d-flex px-3 my-4 justify-content-start flex-column">
                    <div class="col-10 col-md-4 d-flex align-items-center gap-2">
                        <label class="col-12 col-md-10 col-lg-4 text-end"> Limite mínimo de compra para obter o desconto
                        </label>
                        <input class="col-12 input-style form-cont" type="number" id="minimoCompras"
                            value="{{  $campanha->valor_min_compras }}" required>
                    </div>
                    <div class="col-10 col-md-4 d-flex align-items-center gap-2">
                        <label class="col-12 col-md-10 col-lg-4 text-end"> Limite máximo de desconto </label>
                        <input class="col-12 input-style form-cont" type="number" id="maximoCompras"
                            value="{{  $campanha->valor_max_desconto }}" required>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ajaxResponseModal" tabindex="-1" role="dialog" aria-labelledby="ajaxResponseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxResponseModalLabel">Mensagem</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="ajaxResponseMessage">
                <!-- A mensagem do AJAX será inserida aqui -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


@include('masks.footer')



<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        document.getElementById('dataCreation').value = formattedDate;
    });

    $(document).ready(function () {
        const campanhaId = "{{ $campanha->campanha_id}}";

        const tipoDesconto = $('#tipoDesconto').val();
        changeField(tipoDesconto);

        // Monitore alterações no tipo de desconto
        $('#tipoDesconto').change(function () {
            changeField($(this).val());
        });

        function changeField(tipoDesconto) {
            console.log("Tipo de Desconto:", tipoDesconto);
            if (tipoDesconto === '%') {
                $('#desconto').attr('max', 100);
                $('#desconto').attr('placeholder', 'Máximo 100');
                $('#desconto').unmask();
            } else {
                $('#desconto').removeAttr('max');
                $('#desconto').attr('placeholder', 'Valor em reais');
                $('#desconto').mask('000.000.000,00', { reverse: true });
            }
        }

        $('#saveCampaign').click(function () {
            var ativo = $('#ativo').is(':checked');
            var descricao = $('#descricao').val();
            var dataInicial = $('#dataInicial').val();
            var dataFinal = $('#dataFinal').val();
            var tipoDesconto = $('#tipoDesconto').val();
            var desconto = $('#desconto').val();
            var qtdTicketsCoupon = $('#qtdTicketsCoupon').val();
            var maxLimitDay = $('#maxLimitDay').val();
            var minimoCompras = $('#minimoCompras').val();
            var maximoCompras = $('#maximoCompras').val();

            const campanhaId = "{{ $campanha->campanha_id}}";

            const campos = [
                { id: 'descricao', label: 'Nome' },
                { id: 'dataInicial', label: 'Data Inicial' },
                { id: 'dataFinal', label: 'Data Final' },
                { id: 'tipoDesconto', label: 'Tipo de Desconto' },
                { id: 'desconto', label: 'Desconto' },
                { id: 'qtdTicketsCoupon', label: 'Qtd de Ingressos por Cupom' },
                { id: 'maxLimitDay', label: 'Limite Diário de Cupons' },
                { id: 'minimoCompras', label: 'Limite Mínimo de Compras' },
                { id: 'maximoCompras', label: 'Limite Máximo de Desconto' }
            ];

            const camposVazios = campos.filter(c => {
                const valor = $(`#${c.id}`).val();
                return !valor || valor.trim() === '';
            });

            if (camposVazios.length > 0) {
                const lista = camposVazios.map(c => `• ${c.label}`).join('\n');

                $('#ajaxResponseModalLabel').text('Campos obrigatórios');
                $('#ajaxResponseMessage').html(`
                    Os seguintes campos precisam ser preenchidos:<br><br>
                    <pre style="white-space: pre-wrap;">${lista}</pre>
                `);
                $('#ajaxResponseModal').modal('show');
                return;
            }

            $.ajax({
                url: '/campaignUpdate',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    ativo: ativo,
                    campanhaId: campanhaId,
                    descricao: descricao,
                    dataInicial: dataInicial,
                    dataFinal: dataFinal,
                    tipoDesconto: tipoDesconto,
                    desconto: desconto,
                    qtdTicketsCoupon: qtdTicketsCoupon,
                    maxLimitDay: maxLimitDay,
                    minimoCompras: minimoCompras,
                    maximoCompras: maximoCompras,
                },
                success: function (response) {
                    $('#ajaxResponseModalLabel').text('Sucesso');
                    $('#ajaxResponseMessage').text('Campanha salva com sucesso!');
                    $('#ajaxResponseModal').modal('show');

                    $('#ajaxResponseModal').on('hidden.bs.modal', function () {
                        location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    $('#ajaxResponseModalLabel').text('Erro');
                    $('#ajaxResponseMessage').text('Erro ao salvar a campanha: ' + error);
                    $('#ajaxResponseModal').modal('show');
                }
            });
        });
    });
</script>