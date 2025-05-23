@include('masks.header', ['css' => 'css/calendar.css']) 

<div class="main p-3">
    <form id="reportForm" action="{{ route('generateReport') }}" method="POST" target="_blank">
        @csrf
        <div class="d-flex flex-column justify-content-center">
            <div class="text-end col-11">
                <button type="button" id="saveProductButton" class="btn btn-success px-5 mt-4">Gerar</button>
            </div>
            <div class="text-center my-4">
                <h1>Relatório de Vendas</h1>
            </div>
            <div class="d-flex justify-content-around col-12">
                <div class="d-flex flex-column">
                    <div class="mb-4">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" id="data_inicio" class="form-control">
                    </div>
                    <div class="mb-4">
                        <label for="data_final" class="form-label">Data Final</label>
                        <input type="date" id="data_final" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
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
                <div id="div-produto" class="mb-4">
                    <label for="produto" class="form-label">Produto</label>
                    <select id="produto" class="form-cont">
                        <option value="">Selecione um Produto</option>
                    </select>
                </div>
            </div>
            <input type="hidden" id="hidden_data_inicio" name="data_inicio">
            <input type="hidden" id="hidden_data_final" name="data_final">
            <input type="hidden" id="hidden_categoria" name="categoria_id">
            <input type="hidden" id="hidden_produto" name="produto_id">
        </div>
    </form>
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
        const empresa = "{{ $empresa }}";
        const categoriaSelect = document.getElementById('categoria');
        const produtoSelect = document.getElementById('produto');

        categoriaSelect.addEventListener('change', function () {
            const categoriaId = this.value;
            produtoSelect.innerHTML = '<option value="">Carregando...</option>';
            produtoSelect.disabled = true;

            if (categoriaId === 'categoriaGeral') {
                document.querySelector('#div-produto label').classList.add('disabled');
                document.querySelector('#div-produto select').disabled = true;
                produtoSelect.innerHTML = '<option value="">Disabilitado</option>';
            } else if (categoriaId) {
                document.querySelector('#div-produto label').classList.remove('disabled');
                document.querySelector('#div-produto select').disabled = false;
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

    });

    document.getElementById('saveProductButton').addEventListener('click', function () {
        const dataInicio = document.getElementById('data_inicio').value;
        const dataFinal = document.getElementById('data_final').value;
        const categoriaId = document.getElementById('categoria').value;
        const produtoId = document.getElementById('produto').value;
        console.log(dataInicio);

        // Verifica se as datas e a categoria estão preenchidas
        if (!dataInicio || !dataFinal || !categoriaId) {
            alert('Preencha todas as datas e selecione uma categoria.');
            return;
        }

        document.getElementById('hidden_data_inicio').value = dataInicio;
        document.getElementById('hidden_data_final').value = dataFinal;
        document.getElementById('hidden_categoria').value = categoriaId;
        document.getElementById('hidden_produto').value = produtoId;

        // Envia o formulário para abrir em uma nova aba
        document.getElementById('reportForm').submit();
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

</script>