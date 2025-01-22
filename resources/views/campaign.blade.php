@include('masks.header', ['css' => 'css/campaign.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Campanhas</h2>
    </div>

    <!-- Tabela de Campanhas com DataTables -->
    <div class=" justify-content-center col-10">
        <table id="campaignsTable" class="display my-4">
            <thead>
                <tr>
                    <th>Nome da Campanha</th>
                    <th>Data de Criação</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campanhas as $campanha)
                    <tr class="campaign-row">
                        <td>{{ $campanha->nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($campanha->data_criacao)->format('d/m/Y') }}</td>
                        <td>{{ $campanha->status ? 'Ativa' : 'Inativa' }}</td>
                        <td>
                            <button class="expand-btn btn btn-edit btn-success" data-campanha-id="{{ $campanha->campanha_id }}" >Editar</button>
                            <button class="btn btn-primary btn-detalhes" data-campanha-id="{{ $campanha->campanha_id }}" data-bs-toggle="modal" data-bs-target="#campaignModal">Detalhes</button>
                            <button class="btn btn-warning btn-coupons" data-campanha-id="{{ $campanha->campanha_id }}" data-bs-toggle="modal" data-bs-target="#couponsModal">Cupons</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- Modal para cada campanha -->
<div class="modal fade" id="campaignModal" tabindex="-1" role="dialog" aria-labelledby="campaignModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="campaignModalLabel">Detalhes da Campanha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex" id="campaignModalContent">
                <!-- Conteúdo será carregado aqui via Ajax -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Cupons -->
<div class="modal fade" id="couponsModal" tabindex="-1" aria-labelledby="couponsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponsModalLabel">Cupons</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="couponsContent">
                    <p>Carregando cupons...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('masks.footer')

<script>
$(document).ready(function () {
    $('#campaignsTable').DataTable({
        language: { url: '/js/pt-BR.json' }
    });

    $('.btn-edit').on('click', function () {
        var campanhaId = $(this).data('campanha-id');
        window.location.href = '/editCampaign/' + campanhaId;
    });

    $(document).on('click', '.btn-detalhes', function () {
    var campanhaId = $(this).data('campanha-id'); 

    // Limpar o conteúdo do modal antes de carregar os dados
    $('#campaignModalContent').html('<p>Carregando detalhes...</p>');

    $.ajax({
        url: '/getCampaignDetails',
        method: 'POST',
        data: {
            campanhaId: campanhaId,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            console.log(data)
            if (data.success) {
                var content = `
                    <div class="col-12 col-md-6">
                        <h5>Informações</h5>
                        <p><strong>Data de Início:</strong> ${data.campanha.data_inicio}</p>
                        <p><strong>Data de Final:</strong> ${data.campanha.data_final}</p>
                        <p><strong>Status:</strong> ${data.campanha.status ? 'Ativa' : 'Inativa'}</p>
                        <p><strong>Quantidade de Cupons:</strong> ${data.campanha.qtd_cupons}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <h5>Resumo</h5>
                        <p><strong>Sigla:</strong> ${data.campanha.sigla_inicial_cupom}</p>
                        <p><strong>Tipo Desconto:</strong> ${data.tipoDesconto}</p>
                        <p><strong>Desconto:</strong> ${data.campanha.valor_desconto}</p>
                        <p><strong>Cupons Utilizados:</strong> ${data.cuponsUtilizados}</p>
                    </div>
                `;
                // Atualizar o conteúdo do modal
                $('#campaignModalContent').html(content);
            } else {
                $('#campaignModalContent').html('<p>Erro ao carregar os detalhes da campanha.</p>');
            }
            // Exibir o modal após o conteúdo ser carregado
            $('#campaignModal').modal('show');
        },
        error: function () {
            $('#campaignModalContent').html('<p>Erro ao carregar os detalhes da campanha. Tente novamente.</p>');
        }
    });
});



    // Evento de clique para o botão de cupons
    $(document).on('click', '.btn-coupons', function () {
        var campanhaId = $(this).data('campanha-id'); 
        console.log(campanhaId);

        $('#couponsContent').html('<p>Carregando cupons...</p>');

        $.ajax({
            url: '/getCoupons',
            method: 'POST',
            data: {
                campanhaId: campanhaId,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
            },
            success: function (data) {
                if (data.coupons && data.coupons.length > 0) {
                    var table = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Chave</th>
                                    <th>Quantidade de Uso</th>
                                    <th>Ativo</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    data.coupons.forEach(coupon => {
                        table += `
                            <tr>
                                <td>${coupon.chave}</td>
                                <td>${coupon.qtd_uso}</td>
                                <td>${coupon.ativo ? 'Sim' : 'Não'}</td>
                            </tr>
                        `;
                    });
                    table += '</tbody></table>';
                    $('#couponsContent').html(table);
                } else {
                    $('#couponsContent').html('<p>Não há cupons para esta campanha.</p>');
                }
            },
            error: function () {
                $('#couponsContent').html('<p>Erro ao carregar cupons. Tente novamente.</p>');
            }
        });
    });
});

</script>
