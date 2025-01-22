@include('masks.header', ['css' => 'css/calendar.css']) 

<div class="main p-3">
    <div class="d-flex flex-column justify-content-center">
        <div class="text-end col-11">
            <button type="button" id="saveProductButton" class="btn btn-success px-5 mt-4">Salvar</button>
        </div>
        <div class="text-center my-4">
            <h1>Gerenciamento de Calendário de Vendas</h1>
        </div>
        <div class="d-flex justify-content-around col-12">
            <div class="col-3 mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <select id="categoria" class="form-cont">
                    <option value="">Selecione uma Categoria</option>
                    <option value="categoriaGeral">Geral</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->categoria_produto_id }}">{{ $categoria->tipoProduto->descricao }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="div-produto" class="col-3 mb-4">
                <label for="produto" class="form-label">Produto</label>
                <select id="produto" class="form-cont">
                    <option value="">Selecione um Produto</option>
                </select>
            </div>
            <div id="div-tipo_preco" class="col-3 mb-4">
                <label for="tipo_preco" class="form-label">Tipo de Preço</label>
                <select id="tipo_preco" class="form-cont">
                    <option value="">Selecione um Produto</option>
                    <option value="valor_unico">Valor Unico </option>
                    <option value="dia_semana">Dias da Semana</option>
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-center px-5 gap-5">
            <div id="dia_semana" class="col-12 col-md-3 preco-por-dia mt-2 align-self-start" style="display:none;">
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
                                <input type="number" class="class-input" id="preco-segunda">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="terca" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Terça</label>
                                <input type="number" class="class-input" id="preco-terca">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="quarta" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Quarta</label>
                                <input type="number" class="class-input" id="preco-quarta">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="quinta" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Quinta</label>
                                <input type="number" class="class-input" id="preco-quinta">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="sexta" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Sexta</label>
                                <input type="number" class="class-input" id="preco-sexta">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="sabado" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Sábado</label>
                                <input type="number" class="class-input" id="preco-sabado">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="domingo" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Domingo</label>
                                <input type="number" class="class-input" id="preco-domingo">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="feriado" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Feriado</label>
                                <input type="number" class="class-input" id="preco-feriado">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="geral" class="col-12 col-md-3 preco-geral mt-2 align-self-start" style="display:none;">
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
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="terca" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Terça</label>
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="quarta" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Quarta</label>
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="quinta" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Quinta</label>
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="sexta" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Sexta</label>
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="sabado" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Sábado</label>
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="domingo" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Domingo</label>
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="feriado" checked>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Feriado</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="valor" class="col-12 col-md-3 preco-por-dia mt-2 align-self-start" style="display:none;">
                <div class="d-flex flex-column">
                    <label class="fw-bold"> Valor Único</label>
                    <input type="number" class="class-input col-5" id="valor_unico">
                </div>
            </div>
            <div id="calendar" class="col-10 col-md-9 justify-content-center ">
            </div>
        </div>
    </div>
</div>

<!-- Modal response-->
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
                <button type="button" class="btn btn-primary" id="modalCloseButton" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update-->
<div class="modal fade" id="modalCalendario" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Definir Parâmetros do Dia</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCalendario">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" class="form-cont ">
                            <option value="1">Disponível</option>
                            <option value="0">Indisponível</option>
                        </select>
                    </div>
                    <div id="valor_esp" class="form-group mt-2">
                        <label>Valor Especifico</label>
                        <input type="number" class="class-input" id="valor_especifico">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="salvarParametros">Salvar</button>
            </div>
        </div>
    </div>
</div>

@include('masks.footer')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tipoPrecoSelect = document.getElementById('tipo_preco');
        const divDiaSemana = document.getElementById('dia_semana');
        const divValorUnico = document.getElementById('valor');

        function atualizarVisibilidade() {
            const valorSelecionado = tipoPrecoSelect.value;

            if (valorSelecionado === 'dia_semana') {
                divDiaSemana.style.display = 'block';
                divValorUnico.style.display = 'none';
            } else if (valorSelecionado === 'valor_unico') {
                divDiaSemana.style.display = 'none';
                divValorUnico.style.display = 'block';
            } else {
                divDiaSemana.style.display = 'none';
                divValorUnico.style.display = 'none';
            }
        }

        tipoPrecoSelect.addEventListener('change', atualizarVisibilidade);

        atualizarVisibilidade();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const empresa = "{{ $empresa }}";
        const categoriaSelect = document.getElementById('categoria');
        const produtoSelect = document.getElementById('produto');
        const tipoPrecoSelect = document.getElementById('tipo_preco');
        var calendarEl = document.getElementById('calendar');
        var calendar;

        categoriaSelect.addEventListener('change', function () {
            const categoriaId = this.value;
            produtoSelect.innerHTML = '<option value="">Carregando...</option>';
            produtoSelect.disabled = true;

            if (categoriaId === 'categoriaGeral') {
                document.querySelector('#div-produto label').style.display = 'none';
                document.querySelector('#div-produto select').style.display = 'none';
                document.querySelector('#div-tipo_preco label').style.display = 'none';
                document.querySelector('#div-tipo_preco select').style.display = 'none';
                allCalendarEdit();
            } else if (categoriaId) {
                document.querySelector('#div-produto label').style.display = 'block';
                document.querySelector('#div-produto select').style.display = 'block';
                document.querySelector('#div-tipo_preco label').style.display = 'block';
                document.querySelector('#div-tipo_preco select').style.display = 'block';
                $.ajax({
                    url: `/getProduct/${categoriaId}`,
                    type: 'GET',
                    success: function (data) {
                        let options = '<option value="">Selecione um produto</option>';
                        data.forEach(produto => {
                            options += `<option value="${produto.produto_id}">${produto.subtitulo}</option>`;
                        });
                        produtoSelect.innerHTML = options;
                        produtoSelect.disabled = false;
                    },
                    error: function () {
                        alert('Erro ao carregar os produtos.');
                        produtoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                    }
                });
            } else {
                produtoSelect.innerHTML = '<option value="">Selecione uma categoria primeiro</option>';
                produtoSelect.disabled = true;
            }
        });

        produtoSelect.addEventListener('change', function () {
            const produtoId = this.value;

            if (produtoId) {
                if (calendar) {
                    calendar.destroy();
                }

                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'pt-br',
                    buttonText: {
                        today: 'Hoje'
                    },
                    events: function (fetchInfo, successCallback, failureCallback) {
                        $.ajax({
                            url: `/getCalendario/${produtoId}`,
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                successCallback(data.events);
                                if (data.valor_unico !== null) {
                                    $('#tipo_preco').val('valor_unico');
                                    $('#geral').hide();
                                    $('#valor').show();
                                    $('#dia_semana').hide();
                                    $('#valor input').val(data.valor_unico);
                                } else {
                                    $('#tipo_preco').val('dia_semana');
                                    $('#geral').hide();
                                    $('#dia_semana').show();
                                    $('#valor').hide();

                                    if (data.valor_semana && Array.isArray(data.valor_semana)) {
                                        data.valor_semana.forEach(item => {
                                            const diaSemana = item.dia_semana;
                                            const valor = item.valor;

                                            if (diaSemana === 'segunda') {
                                                if (valor === null) {
                                                    $('input[name="ativo[]"][value="segunda"]').prop('checked', false);
                                                    $('#preco-segunda').prop('disabled', true).val('');
                                                } else {
                                                    $('input[name="ativo[]"][value="segunda"]').prop('checked', true);
                                                    $('#preco-segunda').val(valor);
                                                }
                                            } else if (diaSemana === 'terca') {
                                                if (valor === null) {
                                                    $('input[name="ativo[]"][value="terca"]').prop('checked', false);
                                                    $('#preco-terca').prop('disabled', true).val('');
                                                } else {
                                                    $('input[name="ativo[]"][value="terca"]').prop('checked', true);
                                                    $('#preco-terca').val(valor);
                                                }
                                            } else if (diaSemana === 'quarta') {
                                                if (valor === null) {
                                                    $('input[name="ativo[]"][value="quarta"]').prop('checked', false);
                                                    $('#preco-quarta').prop('disabled', true).val('');
                                                } else {
                                                    $('input[name="ativo[]"][value="quarta"]').prop('checked', true);
                                                    $('#preco-quarta').val(valor);
                                                }
                                            } else if (diaSemana === 'quinta') {
                                                if (valor === null) {
                                                    $('input[name="ativo[]"][value="quinta"]').prop('checked', false);
                                                    $('#preco-quinta').prop('disabled', true).val('');
                                                } else {
                                                    $('input[name="ativo[]"][value="quinta"]').prop('checked', true);
                                                    $('#preco-quinta').val(valor);
                                                }
                                            } else if (diaSemana === 'sexta') {
                                                if (valor === null) {
                                                    $('input[name="ativo[]"][value="sexta"]').prop('checked', false);
                                                    $('#preco-sexta').prop('disabled', true).val('');
                                                } else {
                                                    $('input[name="ativo[]"][value="sexta"]').prop('checked', true);
                                                    $('#preco-sexta').val(valor);
                                                }
                                            } else if (diaSemana === 'sabado') {
                                                if (valor === null) {
                                                    $('input[name="ativo[]"][value="sabado"]').prop('checked', false);
                                                    $('#preco-sabado').prop('disabled', true).val('');
                                                } else {
                                                    $('input[name="ativo[]"][value="sabado"]').prop('checked', true);
                                                    $('#preco-sabado').val(valor);
                                                }
                                            } else if (diaSemana === 'domingo') {
                                                if (valor === null) {
                                                    $('input[name="ativo[]"][value="domingo"]').prop('checked', false);
                                                    $('#preco-domingo').prop('disabled', true).val('');
                                                } else {
                                                    $('input[name="ativo[]"][value="domingo"]').prop('checked', true);
                                                    $('#preco-domingo').val(valor);
                                                }
                                            } else if (diaSemana === 'feriado') {
                                                if (valor === null) {
                                                    $('input[name="ativo[]"][value="feriado"]').prop('checked', false);
                                                    $('#preco-feriado').prop('disabled', true).val('');
                                                } else {
                                                    $('input[name="ativo[]"][value="feriado"]').prop('checked', true);
                                                    $('#preco-feriado').val(valor);
                                                }
                                            }
                                        });
                                    }
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Erro ao carregar os eventos:', error);
                                failureCallback();
                            }
                        });
                    },
                    editable: true,
                    selectable: true,
                    eventClick: function (info) {
                        $('#modalCalendario').modal('show');
                        var event = info.event;
                        var calendarioId = event.id;
                        var dataSelecionada = event.start.toISOString().split('T')[0];
                        var produtoId = produtoSelect.value;
                        $('#produtoId').val(produtoId);
                        $('#dataSelecionada').val(dataSelecionada);
                        $('#status').val(event.extendedProps.status ? 1 : 0);

                        document.querySelector('#valor_especifico').style.display = 'block';

                        $('#salvarParametros').off('click').on('click', function () {
                            var status = $('#status').val() === '1';
                            var valor = $('#valor_especifico').val();
                            var geral = false;

                            if (status && (valor === '' || valor === null)) {
                                alert('O valor não pode ser vazio quando o status está ativo!');
                                return; 
                            }

                            $.ajax({
                                url: `/updateCalendario/${event.id}`,
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    geral: geral,
                                    calendarioId: calendarioId,
                                    produtoId: produtoId,
                                    status: status,
                                    valor: valor,
                                    dataSelecionada: dataSelecionada
                                },
                                success: function () {
                                    calendar.refetchEvents();
                                    $('#modalCalendario').modal('hide');
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.error("Erro ao salvar: ", textStatus, errorThrown);
                                }
                            });
                        });
                    }
                });

                calendar.render();
                changeColor(calendar);
            }
        });

        function allCalendarEdit(categoriaId) {
            if (calendar) {
                calendar.destroy();
            }

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                buttonText: {
                    today: 'Hoje'
                },
                events: function (fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: `/getAllCalendar`,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            console.log(data);
                            successCallback(data.events);
                            $('#geral').hide();
                            $('#dia_semana').hide();
                            $('#valor').hide();

                        },
                        error: function (xhr, status, error) {
                            console.error('Erro ao carregar os eventos:', error);
                            failureCallback();
                        }
                    });
                },
                editable: true,
                selectable: true,
                eventClick: function (info) {
                    $('#modalCalendario').modal('show');
                    var event = info.event;
                    var calendarioId = event.id;
                    var dataSelecionada = event.start.toISOString().split('T')[0];
                    var produtoId = produtoSelect.value;

                    $('#produtoId').val(produtoId);
                    $('#dataSelecionada').val(dataSelecionada);
                    $('#status').val(event.extendedProps.status ? 1 : 0);

                    document.querySelector('#valor_especifico').style.display = 'none';

                    $('#salvarParametros').off('click').on('click', function () {
                        var status = $('#status').val() === '1';
                        var valor = $('#valor_especifico').val();
                        var geral = true;

                        $.ajax({
                            url: `/updateCalendario/${event.id}`,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                geral: geral,
                                calendarioId: calendarioId,
                                produtoId: produtoId,
                                status: status,
                                valor: valor,
                                dataSelecionada: dataSelecionada
                            },
                            success: function () {
                                calendar.refetchEvents();
                                $('#modalCalendario').modal('hide');
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.error("Erro ao salvar: ", textStatus, errorThrown);
                            }
                        });
                    });
                }
            });

            calendar.render();
            changeColor(calendar);
        }

    });





    $('#saveProductButton').on('click', function (e) {
        e.preventDefault();

        let produtoId = $('#produto').val();
        let tipoPreco = $('#tipo_preco').val();
        let valor_unico = null;
        let precoPorDia = null;

        console.log(produtoId);
        console.log(tipoPreco);

        if (!produtoId) {
            showModal('Por favor, selecione um produto.', 'error');
            return;
        }

        if (tipoPreco == 'dia_semana') {
            precoPorDia = {
                segunda: parseFloat($('#preco-segunda').val()).toFixed(2),
                terca: parseFloat($('#preco-terca').val()).toFixed(2),
                quarta: parseFloat($('#preco-quarta').val()).toFixed(2),
                quinta: parseFloat($('#preco-quinta').val()).toFixed(2),
                sexta: parseFloat($('#preco-sexta').val()).toFixed(2),
                sabado: parseFloat($('#preco-sabado').val()).toFixed(2),
                domingo: parseFloat($('#preco-domingo').val()).toFixed(2),
                feriado: parseFloat($('#preco-feriado').val()).toFixed(2)
            };
        } else {
            let preco_unico = $('#preco_unico').prop('checked') ? 1 : 0;
            valor_unico = parseFloat($('#valor_unico').val()).toFixed(2);
            console.log('passo');
            console.log(valor_unico);
        }

        var formData = {
            produto_id: produtoId,
            tipo_preco: tipoPreco,
            valor_unico: valor_unico,
            preco_por_dia: precoPorDia,
            _token: $('input[name="_token"]').val()
        };

        $.ajax({
            url: '{{ route("updateCalendarProduct") }}',
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
    });

    function showModal(message, type) {
        $('#feedbackMessage').text(message);

        if (type === 'success') {
            $('#feedbackModalLabel').text('Sucesso');
        } else {
            $('#feedbackModalLabel').text('Erro');
        }

        $('#feedbackModal').modal('show');

        $('#modalCloseButton').off('click').on('click', function () {
            location.reload();
        });
    }

    $('.checkbox-dia').on('change', function () {
        let index = $('.checkbox-dia').index(this);
        let input = $('.class-input').eq(index);

        if ($(this).is(':checked')) {
            input.prop('disabled', false);
        } else {
            input.prop('disabled', true);
            input.val('');
        }
    });

    function changeColor(calendar) {
        const events = calendar.getEvents();

        events.forEach(event => {
            if (event.extendedProps.status) {
                const dateStr = event.start.toISOString().split('T')[0];
                const dayElement = document.querySelector(`.fc-daygrid-day[data-date="${dateStr}"]`);

                if (dayElement) {
                    dayElement.style.backgroundColor = 'green';
                    dayElement.style.color = 'white';
                }
            }
        });
    }
</script>