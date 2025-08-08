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
        </div>
        <div class="d-flex justify-content-center px-5 gap-5">
            <div id="dia_semana" class="col-12 col-md-3 preco-por-dia mt-2 align-self-start d-none">
                <div class="d-flex">
                    <div class="me-3">
                        <label class="fw-bold">Ativo</label>
                    </div>
                    <div>
                        <label class="fw-bold">Dias</label>
                    </div>
                </div>
                <div class="d-flex gap-2" id="dias-semana">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="segunda" {{ !empty($parametrosMapeados['segunda']) ? 'checked' : '' }}>
                            </div>
                            <div class="d-flex flex-column">
                                <label>Segunda-Feira</label>
                                <input type="number" class="class-input" id="preco-segunda">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="terca" {{ !empty($parametrosMapeados['terca']) ? 'checked' : '' }}>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Terça-Feira</label>
                                <input type="number" class="class-input" id="preco-terca">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="quarta" {{ !empty($parametrosMapeados['quarta']) ? 'checked' : '' }}>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Quarta-Feira</label>
                                <input type="number" class="class-input" id="preco-quarta">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="quinta" {{ !empty($parametrosMapeados['quinta']) ? 'checked' : '' }}>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Quinta-Feira</label>
                                <input type="number" class="class-input" id="preco-quinta">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="sexta" {{ !empty($parametrosMapeados['sexta']) ? 'checked' : '' }}>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Sexta-Feira</label>
                                <input type="number" class="class-input" id="preco-sexta">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="sabado" {{ !empty($parametrosMapeados['sabado']) ? 'checked' : '' }}>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Sábado</label>
                                <input type="number" class="class-input" id="preco-sabado">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="domingo" {{ !empty($parametrosMapeados['domingo']) ? 'checked' : '' }}>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Domingo</label>
                                <input type="number" class="class-input" id="preco-domingo">
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-3 mt-1">
                            <div class="d-flex justify-content-center col-2 mb-2">
                                <input type="checkbox" class="checkbox-dia" name="ativo[]" value="feriado" {{ !empty($parametrosMapeados['feriado']) ? 'checked' : '' }}>
                            </div>
                            <div class="d-flex flex-column">
                                <label> Feriado</label>
                                <input type="number" class="class-input" id="preco-feriado">
                            </div>
                        </div>
                    </div>
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
    function esconderInputsNumber() {
        const inputs = document.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.style.display = 'none';
        });
    }

    function mostrarInputsNumber() {
        const inputs = document.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.style.display = 'block';
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const empresa = "{{ $empresa }}";
        const categoriaSelect = document.getElementById('categoria');
        const produtoSelect = document.getElementById('produto');
        const divDiaSemana = document.getElementById('dia_semana');
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
                divDiaSemana.classList.remove('d-none');
                allCalendarEdit();
                esconderInputsNumber()
            } else if (categoriaId) {
                document.querySelector('#div-produto label').style.display = 'block';
                document.querySelector('#div-produto select').style.display = 'block';
                divDiaSemana.classList.add('d-none');
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
                            // $('#dia_semana').hide();
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
        let geral = $('#categoria').val();

        let diasAtivos = {};
        let tipoGeral = false;
        const mapaDias = {
            segunda: 'segunda-feira',
            terca: 'terça-feira',
            quarta: 'quarta-feira',
            quinta: 'quinta-feira',
            sexta: 'sexta-feira',
            sabado: 'sábado',
            domingo: 'domingo',
            feriado: 'feriado'
        };

        if (geral !== 'categoriaGeral') {
            if (!produtoId) {
                showModal('Por favor, selecione um produto.', 'error');
                return;
            }
        } else if (geral === 'categoriaGeral') {
            $('#dias-semana input.checkbox-dia').each(function () {
                const dia = $(this).val();  // pega o valor: "segunda", "terca", etc.
                const diaFormatado = mapaDias[dia]; // converte para "Segunda-Feira", "Terça-Feira", etc.
                const marcado = $(this).is(':checked'); // true ou false
                diasAtivos[diaFormatado] = marcado;
            });
            tipoGeral = true;
        }

        var formData = {
            produto_id: produtoId,
            dias_ativos: diasAtivos,
            tipo_geral: tipoGeral,
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