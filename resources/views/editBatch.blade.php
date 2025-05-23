@include('masks.header', ['css' => 'css/newProduct.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex col-12 justify-content-end align-items-end">
        <button type="button" id="saveProductButton" class="btn btn-success px-5 mt-4">Salvar</button>
    </div>
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Editar Lote</h2>
    </div>
    <div class="d-flex flex-column align-items-center col-12">
        <div class="d-flex flex-column col-10 col-lg-6 mx-4 gap-1">
            <div class="d-flex my-3 col-12">
                <div class="col-4 form-group">
                    <div class="form-check">
                        <input type="checkbox" name="ativo" id="ativo"
                               class="form-check-input" {{ $lote->ativo ? 'checked' : '' }}>
                        <label for="ativo" class="form-check-label">Ativo</label>
                    </div>
                </div>
            </div>
            <div class="d-flex my-3 col-12">
                <div class="col-3 form-group ">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="{{ $lote->nome }}" class="form-control"
                           maxlength="50" required>
                </div>
                <div class="col-3 form-group ps-3">
                    <label for="qtd_lotes">Quantidade de Lotes</label>
                    <select name="qtd_lotes" id="qtd_lotes" class="form-control" required>
                        <option value="">Selecione</option>
                        @php
                            $maiorLoteNumero = $lote->loteParametro()->max('lote_numero');
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $i == $maiorLoteNumero ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-3 form-group ps-3">
                    <label for="tipo_lote">Tipo de Lote</label>
                    <select name="tipo_lote" id="tipo_lote" class="form-control" required>
                        <option value="">Selecione</option>
                        <option value="data" {{ $lote->lote_por_data === true ? 'selected' : '' }}>Data</option>
                        <option value="quantidade" {{ $lote->lote_por_qtd === true ? 'selected' : '' }}>Quantidade
                        </option>
                    </select>
                </div>
                <div class="col-3 form-group ps-3">
                    <label for="tipo_desconto">Tipo de Desconto</label>
                    <select name="tipo_desconto" id="tipo_desconto" class="form-control" required>
                        <option value="">Selecione</option>
                        <option value="1" {{ $lote->tipo_desconto_id == 1 ? 'selected' : '' }}>%</option>
                        <option value="2" {{ $lote->tipo_desconto_id == 2 ? 'selected' : '' }}>R$</option>
                    </select>
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
   const url = window.location.href;
    const loteIdMatch = url.match(/\/editBatch\/(\d+)/);
    const loteId = loteIdMatch ? loteIdMatch[1] : null;

    document.addEventListener('DOMContentLoaded', () => {
        const tipoLote = document.getElementById('tipo_lote');
        const quantidadeLotes = document.getElementById('qtd_lotes');
        const lotesContainer = document.getElementById('lotesContainer');
        const valoresIniciais = @json($lote->loteParametro()->get());
        const produtos = @json($lote->loteProduto()->get());

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

        function criarCamposLote(tipo, quantidade) {
            lotesContainer.innerHTML = '';

            for (let i = 1; i <= quantidade; i++) {
                const loteDiv = document.createElement('div');
                loteDiv.classList.add('lote', 'd-flex', 'flex-column');
                loteDiv.innerHTML = `<div>
                                        <h5>Lote ${i}</h5>
                                    </div>`;

                let valoresLote = valoresIniciais.find(v => v.lote_numero === i);

                const loteId = valoresLote ? valoresLote.lote_parametro_id || '' : '';

                loteDiv.setAttribute('data-lote-id', loteId);

                if (!valoresLote) {
                    valoresLote = {};
                }

                if (tipo === 'data') {
                    loteDiv.innerHTML += `
                    <div class="d-flex gap-3 my-2">
                        <div class="col-3">
                            <label for="dataInicio${i}">Data Início:</label>
                            <input type="date" id="dataInicio${i}" name="dataInicio${i}" class="form-control" value="${valoresLote.data_inicio || ''}">
                        </div>
                        <div class="col-3">
                            <label for="dataFinal${i}">Data Final:</label>
                            <input type="date" id="dataFinal${i}" name="dataFinal${i}" class="form-control" value="${valoresLote.data_final || ''}">
                        </div>
                         <div class="col-3 ps-2">
                            <label for="valorDesconto${i}">Valor Desconto:</label>
                            <input type="number" id="valorDesconto${i}" name="valorDesconto${i}" class="form-control"  value="${valoresLote.valor_desconto || ''}">
                        </div>
                    </div>
                    `;
                } else if (tipo === 'quantidade') {
                    loteDiv.innerHTML += `
                    <div class="d-flex">
                        <div class="col-5">
                            <label for="qtdVendas${i}">Quantidade de Vendas:</label>
                            <input type="number" id="qtdVendas${i}" name="qtdVendas${i}" min="1" class="form-control" value="${valoresLote.qtd_venda || ''}">
                        </div>
                        <div class="col-5 ps-2">
                            <label for="valorDesconto${i}">Valor Desconto:</label>
                            <input type="number" id="valorDesconto${i}" name="valorDesconto${i}" class="form-control"  value="${valoresLote.valor_desconto || ''}">
                        </div>
                    </div>
                    `;
                }

                lotesContainer.appendChild(loteDiv);
                
            }

            ajustarValorDesconto();

            const dynamicDiv = document.getElementById('dynamic');
            if (dynamicDiv) {
                dynamicDiv.style.display = 'block';
            }
        }

        const tipoInicial = tipoLote.value;
        const quantidadeInicial = quantidadeLotes.value;


        if (tipoInicial && quantidadeInicial) {
            criarCamposLote(tipoInicial, quantidadeInicial);
        }
    });
    
    function ajustarValorDesconto() {
    const tipoDescontoSelect = document.getElementById('tipo_desconto');
    const tipoDesconto = tipoDescontoSelect ? tipoDescontoSelect.value : '1'; // Pegando o valor do select

    // Loop pelos campos de valor desconto e ajustando conforme o tipo
    const inputsDesconto = document.querySelectorAll('input[id^="valorDesconto"]');
    inputsDesconto.forEach(input => {
        let valorAtual = input.value;

        if (tipoDesconto === '1') { // Se o tipo de desconto for porcentagem
            // Remove os dois últimos zeros se houver (por exemplo, 100,00 -> 10)
            if (valorAtual && valorAtual.endsWith('00')) {
                valorAtual = valorAtual.slice(0, -2); // Remove os últimos 2 zeros
            }
            
            // Garante que o valor é um número válido (sem ponto final)
            if (valorAtual.endsWith('.')) {
                valorAtual = valorAtual.slice(0, -1); // Remove o ponto final
            }

            input.value = valorAtual; // Atualiza o campo com o valor ajustado
        }
    });
}


    document.addEventListener('DOMContentLoaded', function () {
    let tipoDesconto = document.getElementById('tipo_desconto').value;
    const tipoDescontoSelect = document.getElementById('tipo_desconto');

    function aplicarMascaraReais(valor) {
        valor = valor.replace(/\D/g, "");
        valor = (valor / 100).toFixed(2) + "";
        valor = valor.replace(".", ",");
        return "R$ " + valor;
    }

    function mascararReais(event) {
        event.target.value = aplicarMascaraReais(event.target.value);
    }

    function limitarPorcentagem(event) {
        const valor = parseFloat(event.target.value);
        if (valor > 100) {
            event.target.value = 100;
        } else if (valor < 0) {
            event.target.value = 0;
        }
    }

    function aplicarMascarasNosCamposExistentes() {
        const inputsDesconto = document.querySelectorAll('input[id^="valorDesconto"]');

        inputsDesconto.forEach(input => {
            // Remove eventos antigos (importante para evitar múltiplas execuções)
            input.removeEventListener('input', mascararReais);
            input.removeEventListener('input', limitarPorcentagem);

            const valorAtual = input.value;

            if (tipoDesconto === "1") {
                input.type = "number";
                input.max = "100";
                input.min = "0";
                input.disabled = false;
                input.placeholder = "Máximo 100";

                const valorNumerico = parseInt(valorAtual.replace(/[^0-9]/g, ''), 10);
                input.value = isNaN(valorNumerico) ? '' : valorNumerico;

                input.addEventListener('input', limitarPorcentagem);
            } else if (tipoDesconto === "2") {
                input.type = "text";
                input.disabled = false;
                input.placeholder = "Ex: R$ 10,00";

                const valorNumerico = valorAtual.replace(/[^0-9]/g, '');
                input.value = valorNumerico ? aplicarMascaraReais(valorNumerico) : '';

                input.addEventListener('input', mascararReais);
            } else {
                input.disabled = true;
                input.placeholder = "";
            }
        });
    }

    // Aplica máscara imediatamente ao carregar
    aplicarMascarasNosCamposExistentes();

    // Atualiza as máscaras quando mudar o tipo de desconto
    tipoDescontoSelect.addEventListener('change', function () {
        tipoDesconto = this.value;  // Agora usando 'let', podemos alterar o valor
        
        // Limpar os valores dos inputs antes de aplicar a nova máscara
        const inputsDesconto = document.querySelectorAll('input[id^="valorDesconto"]');
        inputsDesconto.forEach(input => {
            input.value = "";  // Limpar o valor do campo
        });

        // Aplicar a máscara de acordo com o novo tipo de desconto
        aplicarMascarasNosCamposExistentes();
    });
});


    document.addEventListener('DOMContentLoaded', function () {
        const categorias = @json($categorias);
        const produtos = @json($lote->loteProduto);
        const todosProdutos = @json($produtos);


        const dynamicFields = document.getElementById('dynamicFields');
        let fieldIndex = 0;

        function preencherProdutos(categoriaId, index) {
            const produtoSelect = document.getElementById(`produto_${index}`);
            produtoSelect.innerHTML = `<option value="">Selecione</option>`;

            const categoriaIdNum = parseInt(categoriaId);

            const produtosFiltrados = todosProdutos.filter(produto => parseInt(produto.categoria_produto_id) === categoriaIdNum);


            produtosFiltrados.forEach(produto => {
                const option = document.createElement('option');
                option.value = produto.produto_id;
                option.textContent = `${produto.subtitulo}`;

                if (produto.produto_id === produtos.find(p => p.produto_id === produto.produto_id)?.produto_id) {
                    option.selected = true;
                }

                produtoSelect.appendChild(option);
            });

            produtoSelect.disabled = produtosFiltrados.length === 0;
        }


        function preencherCampos() {
            produtos.forEach((produto, index) => {
                const row = document.createElement('div');
                row.className = 'd-flex my-3 align-items-center dynamic-row col-12 gap-1';
                row.innerHTML = `
                <div class="pe-2 mt-4">
                    <button type="button" class="btn btn-danger remove-row-button">Remover</button>
                </div>
                <div class="col-4 form-group">
                    <label for="categoria_${fieldIndex}">Categoria</label>
                    <select name="categoria_produto_id[]" id="categoria_${fieldIndex}" class="form-control categoria-select" data-index="${fieldIndex}">
                        <option value="">Selecione</option>
                        ${categorias.map(categoria => `<option value="${categoria.categoria_produto_id}" ${categoria.categoria_produto_id === produto.produto.categoria_produto_id ? 'selected' : ''}>${categoria.tipo_produto.descricao}</option>`).join('')}
                    </select>
                </div>
                <div class="col-4 form-group ">
                    <label for="produto_${fieldIndex}">Produtos</label>
                    <select name="produto_id[]" id="produto_${fieldIndex}" class="form-control produto-select" disabled>
                        <option value="">Selecione</option>
                    </select>
                </div>
            `;

                dynamicFields.appendChild(row);

                const categoriaSelect = document.getElementById(`categoria_${fieldIndex}`);

                const categoriaId = categoriaSelect.value;
                if (categoriaId) {
                    preencherProdutos(categoriaId, index);
                }

                fieldIndex++;
            });
        }

        function createRow() {
            const row = document.createElement('div');
            row.className = 'd-flex my-3 align-items-center dynamic-row gap-1';
            row.innerHTML = `
            <div class="pe-2 mt-4">
                <button type="button" class="btn btn-danger remove-row-button">Remover</button>
            </div>
            <div class="col-4 form-group">
                <label for="categoria_${fieldIndex}">Categoria</label>
                <select name="categoria_produto_id[]" id="categoria_${fieldIndex}" class="form-control categoria-select" data-index="${fieldIndex}">
                    <option value="">Selecione</option>
                    ${categorias.map(categoria => `<option value="${categoria.categoria_produto_id}">${categoria.tipo_produto.descricao}</option>`).join('')}
                </select>
            </div>
            <div class="col-4 form-group">
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

        preencherCampos();
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
                    body: JSON.stringify({categoria_id: categoriaId}),
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
        const ativo = document.getElementById('ativo').checked;
        const nome = document.getElementById('nome').value;
        const qtdLotes = document.getElementById('qtd_lotes').value;
        const tipoLote = document.getElementById('tipo_lote').value;
        const tipoDesconto = document.getElementById('tipo_desconto').value;
        const dadosLotes = capturarDadosLotes();
        const categoriasProdutos = [];

        if (tipoDesconto == 1) {
            dadosLotes.forEach(lote => {
                if (lote.valorDesconto) {
                    let valor = lote.valorDesconto.toString().replace(',', '.');
                    valor = parseFloat(valor);
                    lote.valorDesconto = isNaN(valor) ? 0 : valor.toFixed(2);
                }
            });
        }

        document.querySelectorAll('.dynamic-row').forEach(row => {
            const categoria = row.querySelector('.categoria-select').value;
            const produto = row.querySelector('.produto-select').value;

            if (categoria && produto) {
                categoriasProdutos.push({categoria_id: categoria, produto_id: produto});
            }
        });

        const formData = {
            loteId,
            ativo,
            nome,
            qtdLotes,
            tipoLote,
            tipoDesconto,
            categoriasProdutos,
            dadosLotes,
        };

        fetch('{{ route("batchUpdate") }}', {
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
            const loteData = {
            lote: index + 1,
                loteId: loteDiv.getAttribute('data-lote-id') || null, // Captura o lote_id
            };

            const dataInicio = loteDiv.querySelector(`#dataInicio${index + 1}`);
            const dataFinal = loteDiv.querySelector(`#dataFinal${index + 1}`);
            const qtdVendas = loteDiv.querySelector(`#qtdVendas${index + 1}`);
            const valorDesconto = loteDiv.querySelector(`#valorDesconto${index + 1}`);

            if (dataInicio && dataFinal) {
                loteData.dataInicio = dataInicio.value;
                loteData.dataFinal = dataFinal.value;
            }
            
            if (qtdVendas) {
                loteData.qtdVendas = qtdVendas.value;
            }

            if (valorDesconto) {
                let valorStr = valorDesconto.value;
                valorStr = valorStr.replace(/[^\d,.-]/g, '').replace(',', '.');
                let valor = parseFloat(valorStr);
                loteData.valorDesconto = isNaN(valor) ? 0 : valor.toFixed(2);
            }

            lotes.push(loteData);
        });

        console.log('Lotes capturados:', lotes);
        return lotes;
    }

</script>

