@include('masks.header', ['css' => 'css/product.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Produtos</h2>
    </div>

    <!-- Select para filtrar produtos -->
    <div class="form-group mb-3 col-10">
        <label for="filtroStatus" class="form-label">Filtrar Produtos</label>
        <select id="filtroStatus" class="form-select">
            <option value="ativo" selected>Ativos</option>
            <option value="inativo">Inativos</option>
        </select>
    </div>

    <!-- Tabela de Produtos Ativos -->
    <div class="justify-content-center col-10" id="containerAtivos">
        <table id="productsTableAtivos" class="display my-4">
            <thead>
                <tr>
                    <th>Principal</th>
                    <th>Categoria</th>
                    <th>Título</th>
                    <th>Subtítulo</th>
                    <th>Ativo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                    <tr>
                        <td><input class="principal-checkbox" data-produto-id="{{ $produto->produto_id }}" type="checkbox" {{ $produto->principal ? 'checked' : '' }}></td>
                        <td>{{ $produto->categoriaProduto->tipoProduto->descricao }}</td>
                        <td>{{ $produto->titulo }}</td>
                        <td>{{ $produto->subtitulo }}</td>
                        <td>{{ $produto->ativo ? 'Ativo' : 'Inativo' }}</td>
                        <td>
                            <div class="d-flex w-100">
                                <button class="btn btn-success btn-edit me-1" style="width: 40%;" data-produto-id="{{ $produto->produto_id }}">
                                    Editar
                                </button>
                                <button class="btn btn-primary" style="width: 60%;" data-bs-toggle="modal" data-bs-target="#detailsModal-ativo-{{ $produto->id }}">
                                    Detalhes
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Ativo -->
                    <div class="modal fade" id="detailsModal-ativo-{{ $produto->id }}" tabindex="-1" aria-labelledby="detailsModalLabel-{{ $produto->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detalhes do Produto - {{ $produto->titulo }}</h5>
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

    <!-- Tabela de Produtos Inativos -->
    <div class="justify-content-center col-10" id="containerInativos" style="display: none;">
        <table id="productsTableInativos" class="display my-4">
            <thead>
                <tr>
                    <th>Principal</th>
                    <th>Categoria</th>
                    <th>Título</th>
                    <th>Subtítulo</th>
                    <th>Ativo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtosInativos as $produto)
                    <tr>
                        <td><input class="principal-checkbox" data-produto-id="{{ $produto->produto_id }}" type="checkbox" {{ $produto->principal ? 'checked' : '' }} disabled></td>
                        <td>{{ $produto->categoriaProduto->tipoProduto->descricao }}</td>
                        <td>{{ $produto->titulo }}</td>
                        <td>{{ $produto->subtitulo }}</td>
                        <td>{{ $produto->ativo ? 'Ativo' : 'Inativo' }}</td>
                        <td>
                            <button class="btn btn-success btn-edit" data-produto-id="{{ $produto->produto_id }}">Editar</button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailsModal-inativo-{{ $produto->id }}">Detalhes</button>
                        </td>
                    </tr>

                    <!-- Modal Inativo -->
                    <div class="modal fade" id="detailsModal-inativo-{{ $produto->id }}" tabindex="-1" aria-labelledby="detailsModalLabel-{{ $produto->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detalhes do Produto - {{ $produto->titulo }}</h5>
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
    $(document).ready(function () {
        const tableAtivos = $('#productsTableAtivos').DataTable({
            language: {
                url: '/js/pt-BR.json'
            }
        });

        const tableInativos = $('#productsTableInativos').DataTable({
            language: {
                url: '/js/pt-BR.json'
            }
        });

        $('#filtroStatus').on('change', function () {
            const filtro = $(this).val();

            if (filtro === 'ativo') {
                $('#containerAtivos').show();
                $('#containerInativos').hide();
            } else {
                $('#containerAtivos').hide();
                $('#containerInativos').show();
            }
        });

        // Redirecionar para edição
        $('.btn-edit').on('click', function () {
            const produtoId = $(this).data('produto-id');
            window.location.href = '/editProduct/' + produtoId;
        });

        // Marcar produto como principal
        $('.principal-checkbox').on('change', function () {
            const produtoId = $(this).data('produto-id');

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
                    success: function () {
                        console.log('Produto marcado como principal com sucesso.');
                    },
                    error: function () {
                        alert('Erro ao marcar o produto como principal.');
                    }
                });
            }
        });
    });
</script>
