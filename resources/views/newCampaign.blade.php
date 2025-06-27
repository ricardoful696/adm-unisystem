@include('masks.header', ['css' => 'css/newCampaign.css']) 

<div class="d-flex flex-column col-11 align-items-center">
    <div class="d-flex justify-content-end col-12 mt-4">
        <button class="save-btn btn btn-success px-5" id="saveCampaign">Salvar</button>
    </div>
    <div class="d-flex justify-content-center flex-column col-12 col-md-10  align-items-center">
        <div class="my-3">
            <h2>Nova Campanha</h2>
        </div>
        <div class="d-flex justify-content-center my-4 gap-2 col-12">
            <div class="col-6 d-flex flex-column justify-content-start align-items-start">
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end">Nome</label>
                    <input class="col-6 input-style form-cont" type="text" id="descricao" placeholder="BLACK FRIDAY">
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end">Data Inicial</label>
                    <input class="col-6 input-style form-cont" type="date" id="dataInicial">
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end">Data Final</label>
                    <input class="col-6 input-style form-cont" type="date" id="dataFinal">
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end">Tipo de Desconto</label>
                    <select class="col-6 input-style form-cont" id="tipoDesconto">
                        <option value="%">%</option>
                        <option value="R$">R$</option>
                    </select>
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-3 text-end">Desconto</label>
                    <input class="col-6 input-style form-cont" type="text" id="desconto">
                </div>
            </div>
            <div class="col-6 d-flex flex-column align-items-start">
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-4 text-end">Data da Criação</label>
                    <input class="col-6 input-style form-cont" type="date" id="dataCreation" disabled>
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-4 text-end">Quantidade de cupons a emitir</label>
                    <input class="col-6 input-style form-cont" type="number" id="qtdCoupons" placeholder="100">
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-4 text-end">Quantidade de ingressos por cupom</label>
                    <input class="col-6 input-style form-cont" type="number" id="qtdTicketsCoupon" placeholder="1">
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-4 text-end">Limite de cupons diário</label>
                    <input class="col-6 input-style form-cont" type="number" id="maxLimitDay" placeholder="100">
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-4 text-end">Qtd de dígitos do cupom</label>
                    <input class="col-6 input-style form-cont" type="number" id="numberLenghtCoupon" placeholder="6">
                </div>
                <div class="d-flex mb-1 gap-2 align-items-center col-12">
                    <label class="col-4 text-end">Cupons já impressos?</label>
                    <select class="col-6 input-style form-cont" id="printCoupon">
                        <option value="sim">Sim</option>
                        <option value="nao">Não</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column justify-content-start col-12 col-md-10">
            <label class="fw-bold label-row" for="sigla">Adicionar uma Sigla da Campanha ao Código </label>
            <div class="d-flex flex-column my-3 px-3">
                <input class="col-2 form-cont" type="text" id="sigla" placeholder="BFD">
            </div>
            <div class="d-flex flex-column my-3 col-12">
                <label class="fw-bold label-row">Aplicar a uma Categoria de Ingressos Específica <strong>( EM
                        DESENVOLVIMENTO)</strong></label>
                <div class="my-4 px-3">
                    <div class="d-flex justify-content-start flex-column">
                        <div class="col-6 col-md-4 d-flex align-items-center gap-2">
                            <label class="col-12 col-md-10 col-lg-4 text-end" >Categoria do ingresso</label>
                            <select class="col-12 input-style form-cont" id="categoriaIngresso" disabled>
                                <option>COMBO 2 ADULTOS - FDS</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-4 d-flex align-items-center gap-2">
                            <label class="col-12 col-md-10 col-lg-4 text-end" for="codigo">Código</label>
                            <input class="col-12 input-style form-cont" type="text" id="codigo" placeholder="50" disabled>
                        </div>
                        <div class="col-10 col-md-4 d-flex align-items-center gap-2">
                            <label class="col-12 col-md-10 col-lg-4 text-end" for="quantidadeDisponivel">Qtd. Disponível Por Categoria</label>
                            <input class="col-12 input-style form-cont" type="number" id="quantidadeDisponivel" placeholder="1" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column">
                <label class="fw-bold label-row">Regra a Aplicar no Desconto</label>
                <div class="d-flex px-3 my-4 justify-content-start flex-column">
                    <div class="col-10 col-md-4 d-flex align-items-center gap-2">
                        <label class="col-12 col-md-10 col-lg-4 text-end">Limite mínimo de compra para obter o desconto</label>
                        <input class="col-12 input-style form-cont" type="number" id="minimoCompras">
                    </div>
                    <div class="col-10 col-md-4 d-flex align-items-center gap-2">
                        <label class="col-12 col-md-10 col-lg-4 text-end" for="minimoCompras">Limite máximo de desconto</label>
                        <input class="col-12 input-style form-cont" type="number" id="maximoCompras">
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

        changeField($('#tipoDesconto').val());

        $('#tipoDesconto').change(function () {
            changeField($(this).val());
        });

        function changeField(tipoDesconto) {
            if (tipoDesconto === '%') {
                $('#desconto').attr('max', 100);
                $('#desconto').val('');
                $('#desconto').attr('placeholder', 'Máximo 100');
                $('#desconto').unmask();
            } else {
                $('#desconto').removeAttr('max');
                $('#desconto').val('');
                $('#desconto').attr('placeholder', 'Valor em reais');
                $('#desconto').mask('000.000.000,00', { reverse: true });
            }
        }


        $('#saveCampaign').click(function () {
            var descricao = $('#descricao').val();
            var dataInicial = $('#dataInicial').val();
            var dataFinal = $('#dataFinal').val();
            var tipoDesconto = $('#tipoDesconto').val();
            var desconto = $('#desconto').val();
            var dataCreation = $('#dataCreation').val();
            var qtdCoupons = $('#qtdCoupons').val();
            var qtdTicketsCoupon = $('#qtdTicketsCoupon').val();
            var maxLimitDay = $('#maxLimitDay').val();
            var numberLenghtCoupon = $('#numberLenghtCoupon').val();
            var printCoupon = $('#printCoupon').val();
            var sigla = $('#sigla').val();
            var categoriaIngresso = $('#categoriaIngresso').val();
            var codigo = $('#codigo').val();
            var quantidadeDisponivel = $('#quantidadeDisponivel').val();
            var minimoCompras = $('#minimoCompras').val();
            var maximoCompras = $('#maximoCompras').val();

            // Campos obrigatórios
            const requiredFields = [
                { id: 'descricao', value: descricao },
                { id: 'dataInicial', value: dataInicial },
                { id: 'dataFinal', value: dataFinal },
                { id: 'tipoDesconto', value: tipoDesconto },
                { id: 'desconto', value: desconto },
                { id: 'qtdCoupons', value: qtdCoupons },
                { id: 'qtdTicketsCoupon', value: qtdTicketsCoupon },
                { id: 'maxLimitDay', value: maxLimitDay },
                { id: 'numberLenghtCoupon', value: numberLenghtCoupon },
                { id: 'sigla', value: sigla },
                { id: 'minimoCompras', value: minimoCompras },
                { id: 'maximoCompras', value: maximoCompras }
            ];

            const emptyFields = requiredFields.filter(f => !f.value || f.value.trim() === '');

            if (emptyFields.length > 0) {
                let fieldNames = emptyFields.map(f => `• ${$(`#${f.id}`).prev('label').text()}`).join('\n');
                $('#ajaxResponseModalLabel').text('Campos obrigatórios');
                $('#ajaxResponseMessage').text('Preencha todos os campos obrigatórios:\n\n' + fieldNames);
                $('#ajaxResponseModal').modal('show');
                return;
            }

            $.ajax({
                url: '/saveCampaign',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    descricao: descricao,
                    dataInicial: dataInicial,
                    dataFinal: dataFinal,
                    tipoDesconto: tipoDesconto,
                    desconto: desconto,
                    dataCreation: dataCreation,
                    qtdCoupons: qtdCoupons,
                    qtdTicketsCoupon: qtdTicketsCoupon,
                    maxLimitDay: maxLimitDay,
                    numberLenghtCoupon: numberLenghtCoupon,
                    sigla: sigla,
                    categoriaIngresso: categoriaIngresso,
                    codigo: codigo,
                    quantidadeDisponivel: quantidadeDisponivel,
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