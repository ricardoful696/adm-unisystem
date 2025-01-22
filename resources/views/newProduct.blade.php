@include('masks.header', ['css' => 'css/newProduct.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="text-end col-12">
        <button type="button" id="saveProductButton" class="btn btn-success mt-4 px-5">Salvar</button>
    </div>
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Novo Produto</h2>
    </div>
    <div id="productForm" class="col-10 d-flex flex-column">
        <div class="d-flex gap-3 flex-column justify-content-center">
            <div class="d-flex gap-3 ">
                <div class="col-6">
                    <div class="form-group my-3">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo" class="form-cont" maxlength="50" required>
                    </div>

                    <div class="form-group my-3">
                        <label for="subtitulo">Subtítulo</label>
                        <input type="text" name="subtitulo" id="subtitulo" class="form-cont" maxlength="50">
                    </div>

                    <div class="form-group my-3">
                        <label for="descricao">Descrição</label>
                        <textarea name="descricao" id="descricao" class="form-cont" rows="4"></textarea>
                    </div>

                    <div class="form-group my-3">
                        <label for="categoria_produto_id">Categoria</label>
                        <select name="categoria_produto_id" id="categoria_produto_id" class="form-cont">
                            <option value="">Selecione</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->categoria_produto_id }}">
                                    {{ $categoria->tipoProduto->descricao }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group my-3">
                        <label for="url_capa">URL da Capa</label>
                        <input type="url" name="url_capa" id="url_capa" class="form-cont">
                    </div>
                    <div class="form-group my-3">
                        <label for="venda_qtd_min">Quantidade Mínima de Venda</label>
                        <input type="number" name="venda_qtd_min" id="venda_qtd_min" class="form-cont" min="0">
                    </div>
                    <div class="form-group my-3">
                        <label for="venda_qtd_max">Quantidade Máxima de Venda</label>
                        <input type="number" name="venda_qtd_max" id="venda_qtd_max" class="form-cont" min="0">
                    </div>
                    <div class="form-group my-3">
                        <label for="venda_qtd_max_diaria">Quantidade Máxima Diária</label>
                        <input type="number" name="venda_qtd_max_diaria" id="venda_qtd_max_diaria" class="form-cont"
                            min="0">
                    </div>
                    <div class="form-group my-3">
                        <label for="qtd_entrada_saida">Quantidade Entrada/Saída</label>
                        <input type="number" name="qtd_entrada_saida" id="qtd_entrada_saida" class="form-cont" min="0">
                    </div>
                    <div class="form-check my-3">
                        <input type="checkbox" name="venda_individual" id="venda_individual" class="form-check-input">
                        <label for="venda_individual" class="form-check-label">Venda Individual</label>
                    </div>
                    <div class="form-check my-3">
                        <input type="checkbox" name="venda_combo" id="venda_combo" class="form-check-input">
                        <label for="venda_combo" class="form-check-label">Venda em Combo</label>
                    </div>
                </div>
            </div>
            <div>
                <div class="form-check my-3">
                    <input type="checkbox" name="preco_unico" id="preco_unico" class="form-check-input">
                    <label for="preco_unico" class="form-check-label">Preço Único</label>
                </div>
                <div class="form-check my-3 col-3">
                    <label for="valor_unico" class="form-check-label">Valor Único</label>
                    <input type="number" name="valor_unico" id="valor_unico" class="form-cont">
                </div>
            </div>
        </div>
        <div class="d-flex col-12 gap-3 line mt-3">
            <div class="mt-2 pe-3">
                <h5>Preço para Datas Específicas</h5>
                <div id="precos-especificos">
                    <div class="d-flex align-items-center mb-2">
                        <input type="date" name="data_inicio[]" class="form-cont me-2 col-4" placeholder="Data Início">
                        <input type="date" name="data_fim[]" class="form-cont me-2 col-4" placeholder="Data Fim">
                        <input type="number" name="precos_especificos[]" class="form-cont col-3" placeholder="Preço">
                        <button id="btn-add" style="width: 30px;" type="button"
                            class="btn btn-success btn-sm add-date-price ms-2 col-1">+</button>
                    </div>
                </div>
            </div>
            <div class="col-6 preco-por-dia mt-2">
                <h5>Preço Padrão</h5>
                <div class="d-flex">
                    <div class="me-3">
                        <label class="fw-bold">Ativo</label>
                    </div>
                    <div>
                        <label class="fw-bold">Dias</label>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="segunda" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label>Segunda</label>
                                <input type="number" class="preco-input" id="preco-segunda" placeholder="Preço segunda">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="terca" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Terça</label>
                                <input type="number" class="preco-input" id="preco-terca" placeholder="Preço terça">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="quarta" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Quarta</label>
                                <input type="number" class="preco-input" id="preco-quarta" placeholder="Preço quarta">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="quinta" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Quinta</label>
                                <input type="number" class="preco-input" id="preco-quinta" placeholder="Preço quinta">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="sexta" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Sexta</label>
                                <input type="number" class="preco-input" id="preco-sexta" placeholder="Preço sexta">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="sabado" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Sábado</label>
                                <input type="number" class="preco-input" id="preco-sabado" placeholder="Preço sábado">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="domingo" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Domingo</label>
                                <input type="number" class="preco-input" id="preco-domingo" placeholder="Preço domingo">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="feriado" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Feriado</label>
                                <input type="number" class="preco-input" id="preco-feriado" placeholder="Preço feriado">
                            </div>
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
        $('.checkbox-dia').each(function (index) {
            let input = $('.preco-input').eq(index);
            if (!$(this).is(':checked')) {
                input.prop('disabled', true);
            }
        });

        // Adiciona evento de mudança nos checkboxes
        $('.checkbox-dia').on('change', function () {
            let index = $('.checkbox-dia').index(this);
            let input = $('.preco-input').eq(index);

            if ($(this).is(':checked')) {
                input.prop('disabled', false);
            } else {
                input.prop('disabled', true);
                input.val(''); // Opcional: limpa o valor do input quando desabilitado
            }
        });

        function togglePriceFields() {
            if ($('#preco_unico').is(':checked')) {
                $('#valor_unico').prop('disabled', false);
                $('input[name="datas_especificas[]"], input[name="precos_especificos[]"]').prop('disabled', true);
                $('#preco-segunda, #preco-terca, #preco-quarta, #preco-quinta, #preco-sexta, #preco-sabado, #preco-domingo, #preco-feriado').prop('disabled', true);
                $('#btn-add').prop('disabled', true);
            } else {
                $('#valor_unico').prop('disabled', true);
                $('input[name="datas_especificas[]"], input[name="precos_especificos[]"]').prop('disabled', false);
                $('#preco-segunda, #preco-terca, #preco-quarta, #preco-quinta, #preco-sexta, #preco-sabado, #preco-domingo, #preco-feriado').prop('disabled', false);
                $('#btn-add').prop('disabled', false);
            }
        }

        togglePriceFields();

        $('#preco_unico').on('change', function () {
            togglePriceFields();
        });


        $(document).on('click', '.add-date-price', function () {
            let newField = `
            <div class="d-flex align-items-center mb-2">
                <input type="date" name="data_inicio[]" class="form-cont me-2" placeholder="Data Início">
                <input type="date" name="data_fim[]" class="form-cont me-2" placeholder="Data Fim">
                <input type="number" name="precos_especificos[]" class="form-cont" placeholder="Preço">
                <button style="width: 50px;" type="button" class="btn btn-danger btn-sm remove-date-price ms-2">-</button>
            </div>`;
            $('#precos-especificos').append(newField);
        });

        $(document).on('click', '.remove-date-price', function () {
            $(this).closest('div').remove();
        });

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

            if ($('#qtd_entrada_saida').val().trim() === '') {
                formValid = false;
                showError('#qtd_entrada_saida', 'Informe a quantidade de entrada e saida.');
            }

            if (formValid) {
                let valor_unico = null;
                let preco_unico = $('#preco_unico').prop('checked') ? 1 : 0;
                if (preco_unico === 1) {
                    valor_unico = $('#valor_unico').val();
                }

                let datasPrecosEspecificos = [];

                $('input[name="data_inicio[]"]').each(function (index) {
                    let dataInicio = $(this).val();
                    let dataFim = $('input[name="data_fim[]"]').eq(index).val();
                    let preco = $('input[name="precos_especificos[]"]').eq(index).val();

                    datasPrecosEspecificos.push({
                        data_inicio: dataInicio,
                        data_fim: dataFim,
                        preco: preco
                    });
                });

                let precoPorDia = {
                    segunda: $('#preco-segunda').val(),
                    terca: $('#preco-terca').val(),
                    quarta: $('#preco-quarta').val(),
                    quinta: $('#preco-quinta').val(),
                    sexta: $('#preco-sexta').val(),
                    sabado: $('#preco-sabado').val(),
                    domingo: $('#preco-domingo').val(),
                    feriado: $('#preco-feriado').val()
                };

                var formData = {
                    titulo: $('#titulo').val(),
                    subtitulo: $('#subtitulo').val(),
                    descricao: $('#descricao').val(),
                    categoria_produto_id: $('#categoria_produto_id').val(),
                    url_capa: $('#url_capa').val(),
                    venda_qtd_min: $('#venda_qtd_min').val(),
                    venda_qtd_max: $('#venda_qtd_max').val(),
                    venda_qtd_max_diaria: $('#venda_qtd_max_diaria').val(),
                    qtd_entrada_saida: $('#qtd_entrada_saida').val(),
                    venda_individual: $('#venda_individual').prop('checked') ? 1 : 0,
                    venda_combo: $('#venda_combo').prop('checked') ? 1 : 0,
                    datasPrecosEspecificos: datasPrecosEspecificos,
                    preco_por_dia: precoPorDia,
                    valor_unico: valor_unico,
                    _token: $('input[name="_token"]').val()
                };

                $.ajax({
                    url: '{{ route("saveProduct") }}',
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
            location.reload();
        });

        function showError(inputSelector, message) {
            $(inputSelector).after('<span class="error-message" style="color: red; font-size: 0.9em;">' + message + '</span>');
        }

        function showModal(message, type) {
            const modalTitle = type === 'success' ? 'Sucesso' : 'Erro';
            const modalClass = type === 'success' ? 'text-success' : 'text-danger';

            $('#feedbackModalLabel').text(modalTitle).removeClass('text-success text-danger').addClass(modalClass);
            $('#feedbackMessage').text(message);
            $('#feedbackModal').modal('show');
        }
    });
</script>