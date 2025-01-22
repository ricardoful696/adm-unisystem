@include('masks.header', ['css' => 'css/product.css'])


<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Lotes</h2>
    </div>

    <!-- Tabela de Produtos com DataTables -->
    <div class="justify-content-center col-10">
        <table id="productsTable" class="display my-4">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Qtd Lotes</th>
                    <th>Tipo Desconto</th>
                    <th>Valor Desconto</th>
                    <th>Ativo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotes as $lote)
                    <tr class="lote-row">
                        <td>{{ $lote->nome }}</td>
                        <td>{{ $lote->loteParametro->count() }}</td> 
                        <td>
                            @if($lote->tipo_desconto_id == 1)
                                %
                            @elseif($lote->tipo_desconto_id == 2)
                                R$
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $lote->valor_desconto }}</td> <!-- Acesse o primeiro item -->
                        <td>{{ $lote->ativo ? 'Ativo' : 'Inativo' }}</td>
                        <td class="d-flex row" >
                            <button class="btn btn-success btn-edit col-12 col-md-6" data-lote-id="{{ $lote->lote_id }}">
                                Editar
                            </button>
                            <button class="btn btn-details btn-primary col-12 col-md-6" data-bs-toggle="modal" data-bs-target="#detailsModal-{{ $lote->lote_id }}">
                                Detalhes
                            </button>
                        </td>
                    </tr>

                    <!-- Modal de Detalhes -->
                    <div class="modal fade " id="detailsModal-{{ $lote->lote_id }}" tabindex="-1" aria-labelledby="detailsModalLabel-{{ $lote->lote_id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content ">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailsModalLabel-{{ $lote->lote_id }}">
                                        Detalhes do lote - {{ $lote->nome }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                   
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('masks.footer')

<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            language: {
                url: '/js/pt-BR.json'
            }
        });

        $('.btn-edit').on('click', function() {
            var loteId = $(this).data('lote-id');  // Obtém o produto_id do botão

            window.location.href = '/editBatch/' + loteId;
        });

        $('.btn-primary').on('click', function () {
            var loteId = $(this).data('bs-target').replace('#detailsModal-', '');

            $.ajax({
                url: "{{ route('getDetails') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}", 
                    lote_id: loteId
                },
                success: function (response) {
                    var modalBody = $('#detailsModal-' + loteId).find('.modal-body');
                    
                    modalBody.empty();

                    if (response.produtos.lote_produto && response.produtos.lote_produto.length > 0) {
                        modalBody.append('<h5>Produtos Relacionados</h5>');
                        response.produtos.lote_produto.forEach(function (loteProduto) {
                            var produto = loteProduto.produto;
                            modalBody.append(`
                                <div class="col-12">
                                    <p><strong>Produto:</strong> ${produto.titulo || '-'}</p>
                                    <p><strong>Subtítulo:</strong> ${produto.subtitulo || '-'}</p>
                                </div>
                                <hr>
                            `);
                        });
                    } else {
                        modalBody.append('<p><strong>Nenhum produto relacionado encontrado.</strong></p>');
                    }

                    if (response.loteParametro && response.loteParametro.length > 0) {
                        modalBody.append('<h5>Parâmetros do Lote</h5>');

                        var table = `
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Lote</th>
                                        <th>Data Início</th>
                                        <th>Data Final</th>
                                        <th>Quantidade de Venda</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                        response.loteParametro.forEach(function (parametro) {
                            table += `
                                <tr>
                                    <td>${parametro.lote_numero || '-'}</td>
                                    <td>${parametro.data_inicio || '-'}</td>
                                    <td>${parametro.data_final || '-'}</td>
                                    <td>${parametro.qtd_venda || '-'}</td>
                                </tr>
                            `;
                        });

                        table += `
                                </tbody>
                            </table>
                        `;

                        modalBody.append(table);
                    } else {
                        modalBody.append('<p><strong>Nenhum parâmetro do lote encontrado.</strong></p>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Erro ao obter os detalhes do lote:", error);
                    alert("Erro ao carregar os detalhes do lote. Tente novamente.");
                }
            });
        });

    });
</script>
