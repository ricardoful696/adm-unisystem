@include('masks.header', ['css' => 'css/newProduct.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex col-12 justify-content-end align-items-end">
        <button type="button" id="saveProductButton" class="btn btn-success px-5 mt-4">Salvar</button>
    </div>
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Editar Lote</h2>
    </div>
    <div class="d-flex flex-column align-items-center col-12">
        <div class="d-flex flex-column col-10 col-lg-6 mx-4 gap-3">
            <div class="d-flex my-3 col-12">
                <div class="col-4 form-group ">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="{{ $lote->nome }}" class="form-control" maxlength="50" required>
                </div>
                <div class="col-4 form-group ps-3">
                    <label for="qtd_lotes">Quantidade de Lotes</label>
                    <select name="qtd_lotes" id="qtd_lotes" class="form-control" required>
                        <option value="">Selecione</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $lote->qtd_lotes == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-4 form-group ps-3">
                    <label for="tipo_lote">Tipo de Lote</label>
                    <select name="tipo_lote" id="tipo_lote" class="form-control" required>
                        <option value="">Selecione</option>
                        <option value="data" {{ $lote->tipo_lote == 'data' ? 'selected' : '' }}>Data</option>
                        <option value="quantidade" {{ $lote->tipo_lote == 'quantidade' ? 'selected' : '' }}>Quantidade</option>
                    </select>
                </div>
            </div>
            <div class="d-flex my-3">
                <div class="col-6 form-group">
                    <label for="tipo_desconto">Tipo de Desconto</label>
                    <select name="tipo_desconto" id="tipo_desconto" class="form-control" required>
                        <option value="">Selecione</option>
                        <option value="1" {{ $lote->tipo_desconto == 1 ? 'selected' : '' }}>%</option>
                        <option value="2" {{ $lote->tipo_desconto == 2 ? 'selected' : '' }}>R$</option>
                    </select>
                </div>
                <div class="col-6 form-group ps-3">
                    <label for="valor_desconto">Valor Desconto</label>
                    <input type="number" name="valor_desconto" id="valor_desconto" class="form-control" value="{{ $lote->valor_desconto ?? '' }}" {{ $lote->tipo_desconto ? '' : 'disabled' }}>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center col-12">
            <div id="lotesContainer" class="col-5 ">
            </div>
            <div id="dynamic" class="d-flex flex-column col-5" style="display: none !important;">
                <h5>Adicionar Produtos</h5>
                <div class="text-start  mt-3 ">
                    <button type="button" id="addRowButton" class="btn btn-success">Adicionar</button>
                </div>
                <div class="col-12 d-flex flex-column mx-5">
                    <div id="dynamicFields">
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
    document.addEventListener('DOMContentLoaded', () => {
        const tipoLote = document.getElementById('tipo_lote');
        const quantidadeLotes = document.getElementById('qtd_lotes');
        const lotesContainer = document.getElementById('lotesContainer');

        function criarCamposLote(tipo, quantidade) {
            lotesContainer.innerHTML = '';

            for (let i = 1; i <= quantidade; i++) {
                const loteDiv = document.createElement('div');
                loteDiv.classList.add('lote', 'd-flex', 'flex-column');
                loteDiv.innerHTML = `'<div>
                                        <h5>Lote ${i}</h5>
                                    </div>`;

                if (tipo === 'data') {
                    loteDiv.innerHTML += `
                    <div class="d-flex gap-3">
                        <div class="col-5">
                            <label for="dataInicio${i}">Data Início:</label>
                            <input type="date" id="dataInicio${i}" name="dataInicio${i}" class="form-control">
                        </div>
                        <div class="col-5">
                            <label for="dataFinal${i}">Data Final:</label>
                            <input type="date" id="dataFinal${i}" name="dataFinal${i}" class="form-control">
                        </div>
                    </div>
                    `;
                } else if (tipo === 'quantidade') {
                    loteDiv.innerHTML += `
                    <div class="col-10">
                        <label for="qtdVendas${i}">Quantidade de Vendas:</label>
                        <input type="number" id="qtdVendas${i}" name="qtdVendas${i}" min="1" class="form-control">
                    </div>
                    `;
                }

                lotesContainer.appendChild(loteDiv);
            }

            const dynamicDiv = document.getElementById('dynamic');
            if (dynamicDiv) {
                dynamicDiv.style.display = 'block'; 
            }
        }


        tipoLote.addEventListener('change', () => {
            const tipo = tipoLote.value;
            const quantidade = quantidadeLotes.value;

            if (quantidade) criarCamposLote(tipo, quantidade);
        });

        quantidadeLotes.addEventListener('change', () => {
            const tipo = tipoLote.value;
            const quantidade = quantidadeLotes.value;

            if (tipo) criarCamposLote(tipo, quantidade);
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const tipoDescontoSelect = document.getElementById('tipo_desconto');
        const valorDescontoInput = document.getElementById('valor_desconto');

        function aplicarMascaraReais(valor) {
            valor = valor.replace(/\D/g, "");
            valor = (valor / 100).toFixed(2) + "";
            valor = valor.replace(".", ",");
            return "R$ " + valor;
        }

        tipoDescontoSelect.addEventListener('change', function () {
            const tipoDesconto = this.value;

            valorDescontoInput.value = "";
            valorDescontoInput.removeEventListener('input', mascararReais);
            valorDescontoInput.removeEventListener('input', limitarPorcentagem);

            if (tipoDesconto === "1") {
                valorDescontoInput.type = "number";
                valorDescontoInput.max = "100";
                valorDescontoInput.min = "0";
                valorDescontoInput.disabled = false;
                valorDescontoInput.placeholder = "Máximo 100";

                valorDescontoInput.addEventListener('input', limitarPorcentagem);
            } else if (tipoDesconto === "2") {
                valorDescontoInput.type = "text";
                valorDescontoInput.max = "";
                valorDescontoInput.disabled = false;
                valorDescontoInput.placeholder = "Ex: R$ 10,00";

                function mascararReais(event) {
                    event.target.value = aplicarMascaraReais(event.target.value);
                }

                valorDescontoInput.addEventListener('input', mascararReais);
            } else {
                valorDescontoInput.disabled = true; 
                valorDescontoInput.placeholder = "";
            }
        });

        function limitarPorcentagem(event) {
            const valor = parseFloat(event.target.value);
            if (valor > 100) {
                event.target.value = 100;
            } else if (valor < 0) {
                event.target.value = 0;
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const categorias = @json($categorias);
        const dynamicFields = document.getElementById('dynamicFields');
        let fieldIndex = 0;

        function createRow() {
            const row = document.createElement('div');
            row.className = 'd-flex my-3 align-items-center dynamic-row';
            row.innerHTML = `
                <div class="pe-2 mt-4">
                    <button type="button" class="btn btn-danger remove-row-button">Remover</button>
                </div>
                <div class="col-5 form-group">
                    <label for="categoria_${fieldIndex}">Categoria</label>
                    <select name="categoria_produto_id[]" id="categoria_${fieldIndex}" class="form-control categoria-select" data-index="${fieldIndex}">
                        <option value="">Selecione</option>
                        ${categorias.map(categoria => `<option value="${categoria.categoria_produto_id}">${categoria.tipo_produto.descricao}</option>`).join('')}
                    </select>
                </div>
                <div class="col-5 form-group ps-3">
                    <label for="produto_${fieldIndex}">Produtos</label>
                    <select name="produto_id[]" id="produto_${fieldIndex}" class="form-control produto-select" disabled>
                        <option value="">Selecione</option>
                    </select>
                </div>
            `;
            dynamicFields.appendChild(row);

            fieldIndex++;
        }

        document.getElementById('addRowButton').addEventListener('click', createRow);
    });

    document.getElementById('dynamicFields').addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-row-button')) {
            const row = event.target.closest('.dynamic-row');
            if (row) {
                row.remove();
            }
        }
    });

    dynamicFields.addEventListener('change', function (event) {
        if (event.target.classList.contains('categoria-select')) {
            const selectCategoria = event.target;
            const categoriaId = selectCategoria.value;
            const index = selectCategoria.dataset.index;
            const produtoSelect = document.getElementById(`produto_${index}`);

            if (categoriaId) {
                fetch('{{ route("getCategory") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ categoria_id: categoriaId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        produtoSelect.innerHTML = `<option value="">Selecione</option>`;
                        data.forEach(produto => {
                            produtoSelect.innerHTML += `<option value="${produto.produto_id}">${produto.subtitulo}</option>`;
                        });
                        produtoSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Erro ao carregar produtos:', error);
                    });
            } else {
                produtoSelect.innerHTML = `<option value="">Selecione</option>`;
                produtoSelect.disabled = true;
            }
        }
    });

    document.getElementById('saveProductButton').addEventListener('click', function () {
        const nome = document.getElementById('nome').value;
        const qtdLotes = document.getElementById('qtd_lotes').value;
        const tipoLote = document.getElementById('tipo_lote').value;
        const tipoDesconto = document.getElementById('tipo_desconto').value;
        const valorDesconto = document.getElementById('valor_desconto').value;


        function limparValor(valor) {
            const valorLimpo = valor.replace(/[^\d,.-]/g, '').replace(',', '.');
            return parseFloat(valorLimpo) || 0; 
        }

        const valorDescontoNumerico = limparValor(valorDesconto);
        const dadosLotes = capturarDadosLotes();

        const categoriasProdutos = [];
        document.querySelectorAll('.dynamic-row').forEach(row => {
            const categoria = row.querySelector('.categoria-select').value;
            const produto = row.querySelector('.produto-select').value;

            if (categoria && produto) {
                categoriasProdutos.push({ categoria_id: categoria, produto_id: produto });
            }
        });

        const formData = {
            nome,
            qtdLotes,
            tipoLote,
            tipoDesconto,
            valorDesconto: valorDescontoNumerico,
            categoriasProdutos,
            dadosLotes,
        };

        fetch('{{ route("saveBatch") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify(formData),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('feedbackMessage').textContent = data.message;
                    var modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    modal.show();
                    modal._element.addEventListener('hidden.bs.modal', function () {
                        location.reload();
                    });
                } else {
                    document.getElementById('feedbackMessage').textContent = data.message || 'Erro ao salvar o lote.';
                    var modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    modal.show();
                    modal._element.addEventListener('hidden.bs.modal', function () {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                document.getElementById('feedbackMessage').textContent = 'Ocorreu um erro ao enviar os dados.';
                var modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                modal.show();
                modal._element.addEventListener('hidden.bs.modal', function () {
                    location.reload();
                });
            });
    });

    function capturarDadosLotes() {
        const lotes = [];

        document.querySelectorAll('.lote').forEach((loteDiv, index) => {
            const loteData = { lote: index + 1 }; 

            const dataInicio = loteDiv.querySelector(`#dataInicio${index + 1}`);
            const dataFinal = loteDiv.querySelector(`#dataFinal${index + 1}`);

            if (dataInicio && dataFinal) {
                loteData.dataInicio = dataInicio.value;
                loteData.dataFinal = dataFinal.value;
            }

            const qtdVendas = loteDiv.querySelector(`#qtdVendas${index + 1}`);
            if (qtdVendas) {
                loteData.qtdVendas = qtdVendas.value;
            }

            lotes.push(loteData);
        });

        console.log('Lotes capturados:', lotes);
        return lotes;
    }

</script>