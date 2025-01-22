@include('masks.header', ['css' => 'css/product.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Produtos</h2>
    </div>

    <!-- Tabela de Produtos com DataTables -->
    <div class="justify-content-center col-10">
        <table id="productsTable" class="display my-4">
            <thead>
                <tr>
                    <th>Principal</t> 
                    <th>Categoria</th>
                    <th>Título</th>
                    <th>Subtítulo</th>
                    <th>Ativo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                    <tr class="product-row">
                        <td><input class="principal-checkbox" data-produto-id="{{$produto->produto_id}}" type="checkbox" value="{{$produto->produto_id}}" {{ $produto->principal ? 'checked' : '' }}>
                        </td>
                        <td>{{ $produto->categoriaProduto->tipoProduto->descricao }}</td>
                        <td>{{ $produto->titulo }}</td>
                        <td>{{ $produto->subtitulo }}</td>
                        <td>{{ $produto->ativo ? 'Ativo' : 'Inativo' }}</td>
                        <td>
                            <button class="btn btn-success btn-edit" data-produto-id="{{ $produto->produto_id }}">
                                Editar
                            </button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailsModal-{{ $produto->id }}">
                                Detalhes
                            </button>
                        </td>
                    </tr>

                    <!-- Modal de Detalhes -->
                    <div class="modal fade" id="detailsModal-{{ $produto->id }}" tabindex="-1" aria-labelledby="detailsModalLabel-{{ $produto->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailsModalLabel-{{ $produto->id }}">
                                        Detalhes do Produto - {{ $produto->titulo }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Categoria:</strong> {{ $produto->categoriaProduto->tipoProduto->descricao }}</p>
                                    <p><strong>Título:</strong> {{ $produto->titulo }}</p>
                                    <p><strong>Subtítulo:</strong> {{ $produto->subtitulo }}</p>
                                    <p><strong>Status:</strong> {{ $produto->ativo ? 'Ativo' : 'Inativo' }}</p>
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
            var produtoId = $(this).data('produto-id');  // Obtém o produto_id do botão

            window.location.href = '/editProduct/' + produtoId;
        });

        $('.principal-checkbox').on('change', function() {
            var produtoId = $(this).data('produto-id');
            console.log(produtoId);

            if ($(this).prop('checked')) {
                $('.principal-checkbox').not(this).prop('checked', false);
                
                $.ajax({
                    url: '/primaryProductSave', 
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        produtoId: produtoId
                    },
                    success: function(response) {
                        console.log('Produto marcado como principal com sucesso.');
                    },
                    error: function() {
                        alert('Erro ao marcar o produto como principal.');
                    }
                });
            }
        });
    });
</script>
