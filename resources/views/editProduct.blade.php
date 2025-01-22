@include('masks.header', ['css' => 'css/editProduct.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="text-end col-12">
        <button type="button" id="return" class="btn btn-dark mt-4">Voltar</button>
        <button type="button" id="saveProductButton" class="btn btn-success mt-4 px-5">Salvar</button>
    </div>
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Editar Produto</h2>
    </div>
    <div id="productForm" class="col-10 d-flex flex-column">
        <div class="d-flex gap-3 justify-content-center">
            <div class="col-6">
                <div class="form-group my-3">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-cont" maxlength="50"
                        value="{{ old('titulo', $produto->titulo) }}" required>
                </div>
                <div class="form-group my-3">
                    <label for="subtitulo">Subtítulo</label>
                    <input type="text" name="subtitulo" id="subtitulo" class="form-cont" maxlength="50"
                        value="{{ old('subtitulo', $produto->subtitulo) }}">
                </div>
                <div class="form-group my-3">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao" class="form-cont"
                        rows="4">{{ old('descricao', $produto->descricao) }}</textarea>
                </div>
                <div class="form-group my-3">
                    <label for="categoria_produto_id">Categoria</label>
                    <select name="categoria_produto_id" id="categoria_produto_id" class="form-cont">
                        <option value="">Selecione</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->categoria_produto_id }}" {{ $produto->categoria_produto_id == $categoria->categoria_produto_id ? 'selected' : '' }}>
                                {{ $categoria->tipoProduto->descricao }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group my-3">
                    <label for="url_capa">URL da Capa</label>
                    <input type="url" name="url_capa" id="url_capa" class="form-cont"
                        value="{{ old('url_capa', $produto->url_capa) }}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group my-3">
                    <label for="venda_qtd_min">Quantidade Mínima de Venda</label>
                    <input type="number" name="venda_qtd_min" id="venda_qtd_min" class="form-cont" min="0"
                        value="{{ old('venda_qtd_min', $produto->venda_qtd_min) }}">
                </div>
                <div class="form-group my-3">
                    <label for="venda_qtd_max">Quantidade Máxima de Venda</label>
                    <input type="number" name="venda_qtd_max" id="venda_qtd_max" class="form-cont" min="0"
                        value="{{ old('venda_qtd_max', $produto->venda_qtd_max) }}">
                </div>
                <div class="form-group my-3">
                    <label for="venda_qtd_max_diaria">Quantidade Máxima Diária</label>
                    <input type="number" name="venda_qtd_max_diaria" id="venda_qtd_max_diaria" class="form-cont" min="0"
                        value="{{ old('venda_qtd_max_diaria', $produto->venda_qtd_max_diaria) }}">
                </div>
                <div class="form-group my-3">
                    <label for="qtd_entrada_saida">Quantidade Entrada/Saída</label>
                    <input type="number" name="qtd_entrada_saida" id="qtd_entrada_saida" class="form-cont" min="0"
                        value="{{ old('qtd_entrada_saida', $produto->qtd_entrada_saida) }}">
                </div>
                <div class="form-check my-3">
                    <input type="checkbox" name="venda_individual" id="venda_individual" class="form-check-input" {{ $produto->venda_individual ? 'checked' : '' }}>
                    <label for="venda_individual" class="form-check-label">Venda Individual</label>
                </div>

                <div class="form-check my-3">
                    <input type="checkbox" name="venda_combo" id="venda_combo" class="form-check-input" {{ $produto->venda_combo ? 'checked' : '' }}>
                    <label for="venda_combo" class="form-check-label">Venda em Combo</label>
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
            <div class="modal-body">
                <p id="feedbackMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@include('masks.footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        const produtoId = "{{ $produto->produto_id }}";

        $('#saveProductButton').on('click', function (e) {
            e.preventDefault();

            $('.error-message').remove();

            let formValid = true;

            if ($('#titulo').val().trim() === '') {
                formValid = false;
                showError('#titulo', 'O campo título é obrigatório.');
            }

            if ($('#subtitulo').val().trim() === '') {
                formValid = false;
                showError('#subtitulo', 'O campo subtítulo é obrigatório.');
            }

            if ($('#descricao').val().trim() === '') {
                formValid = false;
                showError('#descricao', 'O campo descrição é obrigatório.');
            }

            if ($('#categoria_produto_id').val().trim() === '') {
                formValid = false;
                showError('#categoria_produto_id', 'Por favor, selecione uma categoria.');
            }

            if ($('#url_capa').val().trim() === '') {
                formValid = false;
                showError('#url_capa', 'O campo URL da capa é obrigatório.');
            }

            if ($('#venda_qtd_min').val().trim() === '') {
                formValid = false;
                showError('#venda_qtd_min', 'Informe a quantidade mínima de venda.');
            }

            if ($('#venda_qtd_max').val().trim() === '') {
                formValid = false;
                showError('#venda_qtd_max', 'Informe a quantidade máxima de venda.');
            }

            if ($('#venda_qtd_max_diaria').val().trim() === '') {
                formValid = false;
                showError('#venda_qtd_max_diaria', 'Informe a quantidade máxima diária.');
            }

            if (formValid) {
                var formData = {
                    produtoId: produtoId,
                    titulo: $('#titulo').val(),
                    subtitulo: $('#subtitulo').val(),
                    descricao: $('#descricao').val(),
                    categoria_produto_id: $('#categoria_produto_id').val(),
                    url_capa: $('#url_capa').val(),
                    venda_qtd_min: $('#venda_qtd_min').val(),
                    venda_qtd_max: $('#venda_qtd_max').val(),
                    venda_qtd_max_diaria: $('#venda_qtd_max_diaria').val(),
                    venda_individual: $('#venda_individual').prop('checked') ? 1 : 0,
                    venda_combo: $('#venda_combo').prop('checked') ? 1 : 0,
                    _token: $('input[name="_token"]').val()
                };

                $.ajax({
                    url: '{{ route("productUpdate") }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            showModal('Produto salvo com sucesso!', 'success');
                        } else {
                            showModal('Erro ao salvar o produto.', 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        showModal('Ocorreu um erro: ' + error, 'error');
                    }
                });
            }
        });

        $('#feedbackModal').on('hidden.bs.modal', function () {
            window.location.href = "{{ route('productView') }}";
        });

        function showError(inputId, message) {
            $(inputId).addClass('is-invalid');
            $(inputId).after('<div class="error-message text-danger">' + message + '</div>');
        }

        function showModal(message, type) {
            document.getElementById('feedbackMessage').innerText = message;

            const modalTitle = document.getElementById('feedbackModalLabel');
            if (type === 'success') {
                modalTitle.innerText = 'Sucesso';
                modalTitle.classList.remove('text-danger');
                modalTitle.classList.add('text-success');
            } else if (type === 'error') {
                modalTitle.innerText = 'Erro';
                modalTitle.classList.remove('text-success');
                modalTitle.classList.add('text-danger');
            }

            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show();
        }


        document.getElementById('return').addEventListener('click', function () {
            window.location.href = "{{ route('productView') }}";
        });
    });
</script>