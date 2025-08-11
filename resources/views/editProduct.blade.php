@include('masks.header', ['css' => 'css/editProduct.css'])

<div class="d-flex flex-column col-11 align-items-center my-4 mx-auto">
    <div class="text-end col-12">
        <button type="button" id="saveProductButton" class="btn btn-success mt-4 px-5">Salvar</button>
    </div>
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Editar Produto</h2>
    </div>
    <div id="productForm" class="col-10 d-flex flex-column">
        <!-- Campo oculto para o produto_id -->
        <input type="hidden" name="produto_id" id="produto_id" value="{{ $produto->produto_id }}">

        <!-- Navegação das Abas -->
        <ul class="nav nav-tabs" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Informações do Produto</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab" aria-controls="products" aria-selected="false">Adicionar Produtos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="prices-tab" data-bs-toggle="tab" data-bs-target="#prices" type="button" role="tab" aria-controls="prices" aria-selected="false">Preços</button>
            </li>
            <li class="nav-item" role="presentation" id="promotions-tab-li">
                <button class="nav-link" id="promotions-tab" data-bs-toggle="tab" data-bs-target="#promotions" type="button" role="tab" aria-controls="promotions" aria-selected="false">Promoções</button>
            </li>
        </ul>

        <!-- Conteúdo das Abas -->
        <div class="tab-content" id="productTabsContent">
            <!-- Aba 1: Informações do Produto -->
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <div class="d-flex gap-3 flex-column justify-content-center mt-4">
                    <div class="d-flex flex-column">
                        <div class="form-check my-3">
                            <input type="checkbox" name="ativo" id="ativo" class="form-check-input" {{ $produto->ativo ? 'checked' : '' }}>
                            <label for="ativo" class="form-check-label">Ativo</label>
                        </div>
                        <div class="form-check mt-1 mb-0">
                            <input type="checkbox" name="bilhete" id="bilhete" class="form-check-input" {{ $produto->bilhete ? 'checked' : '' }}>
                            <label for="bilhete" class="form-check-label">Bilhete</label>
                        </div>
                        <div class="form-check mt-1 ms-2 mb-0">
                            <input type="checkbox" name="produto_fixo" id="produto_fixo" class="form-check-input" {{ $produto->produtos_fixos_combo ? 'checked' : '' }}>
                            <label for="produto_fixo" class="form-check-label">Produtos fixos</label>
                        </div>
                        <div class="form-check my-3">
                            <input type="checkbox" name="produto_base" id="produto_base" class="form-check-input">
                            <label for="produto_base" class="form-check-label">Produtos fixos</label>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="col-6">
                            <div class="form-group my-3">
                                <label for="titulo">Título</label>
                                <input type="text" name="titulo" id="titulo" class="form-cont" maxlength="50" required value="{{ $produto->titulo }}">
                            </div>
                            <div class="form-group my-3">
                                <label for="subtitulo">Subtítulo</label>
                                <input type="text" name="subtitulo" id="subtitulo" class="form-cont" maxlength="50" value="{{ $produto->subtitulo }}">
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
                                <label for="descricao">Descrição</label>
                                <textarea name="descricao" id="descricao" class="form-cont" rows="4">{{ $produto->descricao }}</textarea>
                            </div>
                            <div class="form-group my-3">
                                <label for="termos_condicoes">Termos e condições</label>
                                <textarea name="termos_condicoes" id="termos_condicoes" class="form-cont" rows="4">{{ $produto->termos_condicoes }}</textarea>
                            </div>
                            <div class="form-group my-3">
                                <label for="quantidade_maxima">Quantidade Máxima de Venda</label>
                                <select id="select-quantidade-maxima" class="form-control">
                                    <option value="sem_limite" {{ $produto->venda_qtd_max == -1 ? 'selected' : '' }}>Sem limite</option>
                                    <option value="definir" {{ $produto->venda_qtd_max != -1 ? 'selected' : '' }}>Definir valor</option>
                                </select>
                                <input type="number" class="form-control mt-2" id="quantidade_maxima" name="quantidade_maxima" placeholder="Digite o valor" value="{{ $produto->venda_qtd_max != -1 ? $produto->venda_qtd_max : '' }}" {{ $produto->venda_qtd_max == -1 ? 'disabled' : '' }}>
                            </div>
                            <div class="form-group my-3">
                                <label for="quantidade_maxima_diaria">Quantidade Máxima Diária</label>
                                <select id="select-quantidade-maxima-diaria" class="form-control">
                                    <option value="sem_limite" {{ $produto->venda_qtd_max_diaria == -1 ? 'selected' : '' }}>Sem limite</option>
                                    <option value="definir" {{ $produto->venda_qtd_max_diaria != -1 ? 'selected' : '' }}>Definir valor</option>
                                </select>
                                <input type="number" class="form-control mt-2" id="quantidade_maxima_diaria" name="quantidade_maxima_diaria" placeholder="Digite o valor" value="{{ $produto->venda_qtd_max_diaria != -1 ? $produto->venda_qtd_max_diaria : '' }}" {{ $produto->venda_qtd_max_diaria == -1 ? 'disabled' : '' }}>
                            </div>
                            <div class="form-group my-3">
                                <label for="qtd_entrada_saida">Quantidade Entrada/Saída</label>
                                <input type="number" name="qtd_entrada_saida" id="qtd_entrada_saida" class="form-cont" min="0" value="{{ $produto->qtd_entrada_saida }}">
                            </div>
                        </div>
                        <div class="col-6 my-3">
                            <div class="form-group">
                                <label for="capa">Upload da Capa</label>
                                <input type="file" name="capa" id="capa" class="form-control">
                            </div>
                            <div id="preview-card" class="card mt-3 {{ $produto->url_capa ? '' : 'd-none' }}" style="width: 18rem;">
                                <img id="preview-image" class="card-img-top" src="{{ $produto->url_capa }}" alt="Pré-visualização da Capa">
                                <div class="card-body">
                                    <p class="card-text text-center">Pré-visualização da imagem selecionada</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aba 2: Adicionar Produtos -->
            <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                <div id="dynamic" class="dynamic d-flex flex-column mt-4">
                    <div class="my-3">
                        <h5>Adicionar Produtos</h5>
                    </div>
                    <div class="text-start mt-3">
                        <button type="button" id="addRow" class="btn btn-success">Adicionar</button>
                    </div>
                    <div class="col-12 d-flex flex-column mx-5 my-3">
                        <div id="dynamicFields"></div>
                    </div>
                </div>
            </div>

  <!-- Aba 3: Preços -->
<div class="tab-pane fade" id="prices" role="tabpanel" aria-labelledby="prices-tab">
    <div class="d-flex gap-3 flex-column justify-content-center mt-4" id="prices-content">
        <!-- Preço Único -->
        <div>
            <div class="form-check my-3">
                <input type="checkbox" name="preco_unico" id="preco_unico" class="form-check-input" 
                    {{ $produto->produtoPreco->isNotEmpty() ? 'checked' : '' }}>
                <label for="preco_unico" class="form-check-label">Preço Único</label>
            </div>
            <div class="form-check my-3 col-3">
                <label for="valor_unico" class="form-check-label">Valor Único</label>
                <input type="number" name="valor_unico" id="valor_unico" class="form-cont" 
                    value="{{ $produto->produtoPreco->isNotEmpty() ? $produto->produtoPreco->first()->valor : '' }}" 
                    {{ $produto->produtoPreco->isEmpty() ? 'disabled' : '' }}>
            </div>
        </div>

        <div class="d-flex col-12 gap-3 mt-3">
            <!-- Preço para Datas Específicas -->
            <div class="my-3 pe-3">
                <h5>Preço para Datas Específicas</h5>
                <div id="precos-especificos">
                    @if ($produto->produtoPrecoEspecifico && $produto->produtoPrecoEspecifico->isNotEmpty())
                        @foreach ($produto->produtoPrecoEspecifico as $preco)
                            <div class="d-flex align-items-center mb-2">
                                <input type="date" name="data_inicio[]" class="form-cont me-2 col-4" value="{{ $preco->data_inicio }}">
                                <input type="date" name="data_fim[]" class="form-cont me-2 col-4" value="{{ $preco->data_fim }}">
                                <input type="number" name="precos_especificos[]" class="form-cont col-3" value="{{ $preco->valor }}">
                                <button style="width: 30px;" type="button" class="btn btn-success btn-sm add-date-price ms-2 col-1">+</button>
                            </div>
                        @endforeach
                    @else
                        <div class="d-flex align-items-center mb-2">
                            <input type="date" name="data_inicio[]" class="form-cont me-2 col-4" placeholder="Data Início">
                            <input type="date" name="data_fim[]" class="form-cont me-2 col-4" placeholder="Data Fim">
                            <input type="number" name="precos_especificos[]" class="form-cont col-3" placeholder="Preço">
                            <button style="width: 30px;" type="button" class="btn btn-success btn-sm add-date-price ms-2 col-1">+</button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Preço Padrão -->
            <div class="col-6 preco-por-dia my-3" id="bloco-dias-da-semana">
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
                        @foreach (['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo', 'feriado'] as $dia)
                            @php
                                $precoDia = $produto->produtoPrecoDia->where('dia_semana', $dia)->first();
                            @endphp
                            <div class="d-flex align-items-end gap-3 mt-1">
                                <div class="d-flex justify-content-center col-2 mb-2">
                                    <input type="checkbox" class="checkbox-dia" name="ativo[]" value="{{ $dia }}" 
                                        {{ $precoDia && $precoDia->ativo ? 'checked' : '' }} 
                                        {{ $produto->produtoPreco->isNotEmpty() ? 'disabled' : '' }}>
                                </div>
                                <div class="d-flex flex-column">
                                    <label>{{ ucfirst($dia) }}</label>
                                    <input type="number" class="preco-input form-cont" name="preco_{{ $dia }}" id="preco-{{ $dia }}" 
                                        placeholder="Preço {{ $dia }}" 
                                        value="{{ $precoDia ? $precoDia->valor : '' }}" 
                                        {{ !$precoDia || !$precoDia->ativo || $produto->produtoPreco->isNotEmpty() ? 'disabled' : '' }}>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


          
<!-- Aba 4: Promoções -->
<div class="tab-pane fade" id="promotions" role="tabpanel" aria-labelledby="promotions-tab">
    <div class="d-flex flex-column mt-4">
        <div class="my-3">
            <h5>Promoções</h5>
            <p>Defina descontos ou preços promocionais para períodos específicos.</p>
        </div>

        <div class="form-group my-3">
            <div class="d-flex align-items-end">
                <div class="me-3" style="width: 20%;">
                    <label for="nome_promocao">Nome da Promoção</label>
                    <input type="text" id="nome_promocao" name="nome_promocao" class="form-cont" 
                           placeholder="Ex.: Promoção de Aniversário" 
                           value="{{ old('nome_promocao', $produto->promocaoRelacionada->nome ?? '') }}">
                </div>
                <div style="width: 20%;">
                    <label for="maximo_disponivel">Máximo Disponível</label>
                    <input type="number" id="maximo_disponivel" name="maximo_disponivel" class="form-cont" 
                           placeholder="Ex.: 100" min="1" 
                           value="{{ isset($produto->promocaoRelacionada->maximo_disponivel) ? $produto->promocaoRelacionada->maximo_disponivel : '' }}">
                </div>
            </div>
        </div>

        <div class="form-group my-3">
            <label for="descricao_promocao">Descrição da Promoção</label>
            <textarea id="descricao_promocao" name="descricao_promocao" class="form-cont" 
                      placeholder="Ex.: Desconto especial para compras antecipadas..." rows="3">{{ isset($produto->promocaoRelacionada->descricao) ? $produto->promocaoRelacionada->descricao : '' }}</textarea>
        </div>

        <div class="form-group my-3">
            <div class="d-flex align-items-end">
                <div class="me-3" style="width: 20%;">
                    <label for="data_inicio">Data Início da Promoção</label>
                    <input type="date" id="data_inicio" name="data_inicio" class="form-cont" 
                           value="{{ isset($produto->promocaoRelacionada->data_inicio) ? $produto->promocaoRelacionada->data_inicio : '' }}">
                </div>
                <div style="width: 20%;">
                    <label for="data_fim">Data Fim da Promoção</label>
                    <input type="date" id="data_fim" name="data_fim" class="form-cont" 
                           value="{{ isset($produto->promocaoRelacionada->data_final) ? $produto->promocaoRelacionada->data_final : '' }}">
                </div>
            </div>
        </div>

        <div class="form-group my-3">
            <label for="promocao_tipo">Tipo de Promoção</label>
            <select id="promocao_tipo" name="promocao_tipo_id" class="form-cont">
                <option value="">Selecione um tipo de promoção</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ isset($produto->promocaoRelacionada->promocao_tipo_id) && $produto->promocaoRelacionada->promocao_tipo_id == $i ? 'selected' : '' }}>
                        {{ ['Promoção por Nível', 'Compre e Ganhe', 'Compra Antecipada', 'Desconto Fixo', 'Data Específica'][$i - 1] }}
                    </option>
                @endfor
            </select>
        </div>

        <div id="promocao_fields" class="mt-4">
            @if (isset($produto->promocaoRelacionada->promocao_tipo_id))
                @switch($produto->promocaoRelacionada->promocao_tipo_id)
                    @case(1)
                        <h6>Promoção por Nível</h6>
                        <div id="niveis" class="mt-3 col-10">
                            @foreach ($produto->promocaoRelacionada->promocaoNivel ?? [] as $nivel)
                                <div class="d-flex align-items-center mb-2 nivel-row col-10">
                                    <input type="number" name="quantidade_min_nivel[]" class="form-cont me-2" placeholder="Quantidade Mínima" min="1" value="{{ $nivel->quantidade_min }}">
                                    <input type="number" name="quantidade_max_nivel[]" class="form-cont me-2" placeholder="Quantidade Máxima" min="1" value="{{ $nivel->quantidade_max }}">
                                    <input type="number" name="desconto_percentual_nivel[]" class="form-cont me-2" placeholder="Desconto (%)" min="0" max="100" value="{{ $nivel->desconto_percentual }}">
                                    <button type="button" class="btn btn-success add-nivel col-1" style="width: 60px;">+</button>
                                </div>
                            @endforeach
                        </div>
                        @break

                    @case(2)
                        <h6>Compre e Ganhe</h6>
                        <div id="compre-ganhe">
                            @foreach ($produto->promocaoRelacionada->promocaoCompreGanhe ?? [] as $item)
                                <div class="d-flex align-items-end mb-2 compre-ganhe-row">
                                    <div class="me-2" style="width: 200px;">
                                        <label>Quantidade Comprada</label>
                                        <input type="number" name="quantidade_comprada[]" class="form-cont" placeholder="Quantidade Comprada" min="1" value="{{ $item->quantidade_compra }}">
                                    </div>
                                    <div class="me-2" style="width: 200px;">
                                        <label>Quantidade Grátis</label>
                                        <input type="number" name="quantidade_gratis[]" class="form-cont" placeholder="Quantidade Grátis" min="1" value="{{ $item->quantidade_gratis }}">
                                    </div>
                                    <div class="me-2" style="width: 300px;">
                                        <label>Produto Ganho</label>
                                        <select name="produto_id_ganho[]" class="form-cont">
                                            <option value="">Selecione o Produto Ganho</option>
                                            @foreach ($produtos as $produtoItem)
                                                <option value="{{ $produtoItem->produto_id }}" {{ $item->produto_id_gratis == $produtoItem->produto_id ? 'selected' : '' }}>
                                                    {{ $produtoItem->subtitulo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="ms-2">
                                        <button type="button" class="btn btn-success add-compre" style="width: 60px;">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @break

                    @case(3)
                        <h6>Compra Antecipada</h6>
                        <div id="antecedencia" class="mt-3">
                            @foreach ($produto->promocaoRelacionada->promocaoCompraAntecipada ?? [] as $antecipada)
                                <div class="d-flex align-items-center mb-2 antecedencia-row col-10">
                                    <input type="number" name="dias_antecedencia_min_antecipada[]" class="form-cont me-2" placeholder="Dias Mínimos" min="1" value="{{ $antecipada->dias_antecedencia_min }}">
                                    <input type="number" name="dias_antecedencia_max_antecipada[]" class="form-cont me-2" placeholder="Dias Máximos" min="1" value="{{ $antecipada->dias_antecedencia_max }}">
                                    <input type="number" name="desconto_percentual_antecipada[]" class="form-cont me-2" placeholder="Desconto (%)" min="0" max="100" value="{{ $antecipada->desconto_percentual }}">
                                    <button type="button" class="btn btn-success add-antecedencia" style="width: 60px;">+</button>
                                </div>
                            @endforeach
                        </div>
                        @break

                    @case(4)
                        <h6>Desconto Fixo</h6>
                        <div id="desconto-fixo">
                            @foreach ($produto->promocaoRelacionada->promocaoDescontoFixo ?? [] as $desconto)
                                <div class="d-flex align-items-end mb-2 desconto-fixo-row">
                                    <div class="me-2" style="width: 200px;">
                                        <label>Quantidade Mínima</label>
                                        <input type="number" name="quantidade_min_fixa[]" class="form-cont" placeholder="Quantidade Mínima" min="1" value="{{ $desconto->quantidade_min }}">
                                    </div>
                                    <div class="me-2" style="width: 200px;">
                                        <label>Desconto (%)</label>
                                        <input type="number" name="desconto_fixo[]" class="form-cont" placeholder="Desconto (%)" min="0" max="100" value="{{ $desconto->desconto_percentual }}">
                                    </div>
                                    <div class="ms-2">
                                        <button type="button" class="btn btn-success add-desconto-fixo" style="width: 60px;">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @break

                    @case(5)
                        <h6>Data Específica</h6>
                        <div id="data-especifica">
                            @foreach ($produto->promocaoRelacionada->promocaoDataEspecifica ?? [] as $data)
                                <div class="d-flex align-items-end mb-2 data-especifica-row">
                                    <div class="me-2" style="width: 200px;">
                                        <label>Data</label>
                                        <input type="date" name="data_especifica[]" class="form-cont" value="{{ $data->data }}">
                                    </div>
                                    <div class="me-2" style="width: 200px;">
                                        <label>Desconto (%)</label>
                                        <input type="number" name="desconto_data_especifica[]" class="form-cont" min="0" max="100" value="{{ $data->desconto_percentual }}">
                                    </div>
                                    <div class="ms-2">
                                        <button type="button" class="btn btn-success add-data-especifica" style="width: 60px;">+</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @break
                @endswitch
            @endif
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

<div class="modal fade" id="feedbackModalError" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabelError">Mensagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="feedbackMessageError"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@include('masks.footer')

<script>
// Dados do produto passados pelo Laravel
const produtoData = @json($produto);
console.log(produtoData);
const categorias = @json($categorias);
const produtos = @json($produtos);
const tipoId = produtoData.promocao_relacionada ? produtoData.promocao_relacionada.promocao_tipo_id : null;

// Função para habilitar/desabilitar a aba de promoções com base no checkbox "bilhete"
function togglePromotionsTab() {
    const bilheteChecked = $('#bilhete').is(':checked');
    const promotionsTabLi = $('#promotions-tab-li');
    const promotionsTab = $('#promotions-tab');
    if (bilheteChecked) {
        promotionsTabLi.removeClass('disabled');
        promotionsTab.attr('data-bs-toggle', 'tab');
    } else {
        promotionsTabLi.addClass('disabled');
        promotionsTab.removeAttr('data-bs-toggle');
        promotionsTab.removeClass('active');
        $('#promotions').removeClass('show active');
        $('#info-tab').addClass('active');
        $('#info').addClass('show active');
    }
}
document.addEventListener('DOMContentLoaded', function () {
        $('#produto_base').on('change', function () {
            if ($(this).is(':checked')) {
                // Desmarca bilhete e produto_fixo quando produto_base é marcado
                $('#bilhete').prop('checked', false);
                $('#produto_fixo').prop('checked', false);
                // Atualiza a aba de promoções
                togglePromotionsTab();
            }
        });

// Função para habilitar/desabilitar a aba de preços com base no checkbox "Produto Fixo"
function togglePricesTab() {
    const produtoFixoChecked = $('#produto_fixo').is(':checked');
    const pricesTabLi = $('#prices-tab-li');
    const pricesTab = $('#prices-tab');

    if (produtoFixoChecked) {
        pricesTabLi.removeClass('disabled');
        pricesTab.removeClass('disabled');
    } else {
        pricesTabLi.addClass('disabled');
        pricesTab.addClass('disabled');

        pricesTab.removeClass('active');
        $('#prices').removeClass('show active');

        // Volta para a aba info
        $('#info-tab').addClass('active');
        $('#info').addClass('show active');
    }
}
        // Adiciona evento para o checkbox bilhete
        $('#bilhete').on('change', function () {
            if ($(this).is(':checked')) {
                // Desmarca produto_base quando bilhete é marcado
                $('#produto_base').prop('checked', false);
            }
            // Atualiza a aba de promoções
            togglePromotionsTab();
        });

          $('#produto_fixo').on('change', function () {
            if ($(this).is(':checked')) {
                // Desmarca produto_base quando bilhete é marcado
                $('#produto_base').prop('checked', false);
                $('#bilhete').prop('checked', true);

            }
            // Atualiza a aba de promoções
            togglePromotionsTab();
        });
    });

// Função para habilitar/desabilitar a aba de preços com base no checkbox "Produto Fixo"
function togglePricesTab() {
    const produtoFixoChecked = $('#produto_fixo').is(':checked');
    const pricesTabLi = $('#prices-tab-li');
    const pricesTab = $('#prices-tab');

    if (produtoFixoChecked) {
        pricesTabLi.removeClass('disabled');
        pricesTab.removeClass('disabled');
    } else {
        pricesTabLi.addClass('disabled');
        pricesTab.addClass('disabled');

        pricesTab.removeClass('active');
        $('#prices').removeClass('show active');

        // Volta para a aba info
        $('#info-tab').addClass('active');
        $('#info').addClass('show active');
    }
}

// Função para renderizar os campos dinâmicos com base no tipo de promoção
function renderPromocaoFields(tipoId) {
    const promocaoFields = $('#promocao_fields');
    const produtosDisponiveis = @json($produtos);
    promocaoFields.empty();

    if (!tipoId) return;

    let html = '';
    if (tipoId === '1') {
        html = `
            <h6>Promoção por Nível</h6>
            <p>Defina descontos com base na quantidade de ingressos comprados.</p>
            <div id="niveis" class="mt-3 col-10">
                <div class="d-flex align-items-center mb-2 nivel-row col-10">
                    <input type="number" name="quantidade_min_nivel[]" class="form-control me-2" placeholder="Quantidade Mínima" min="1">
                    <input type="number" name="quantidade_max_nivel[]" class="form-control me-2" placeholder="Quantidade Máxima" min="1">
                    <input type="number" name="desconto_percentual_nivel[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100">
                    <button type="button" class="btn btn-success add-nivel col-1" style="width: 60px;">+</button>
                </div>
            </div>`;
    } else if (tipoId === '2') {
        html = `
            <h6>Compre e Ganhe</h6>
            <p>Defina a quantidade de ingressos necessária para ganhar outro ingresso.</p>
            <div id="compre-ganhe">
                <div class="d-flex align-items-end mb-2 compre-ganhe-row">
                    <div class="me-2" style="width: 200px;">
                        <label for="quantidade_comprada">Quantidade Comprada</label>
                        <input type="number" name="quantidade_comprada[]" id="quantidade_comprada" class="form-control" placeholder="Quantidade Comprada" min="1">
                    </div>
                    <div class="me-2" style="width: 200px;">
                        <label for="quantidade_gratis">Quantidade Grátis</label>
                        <input type="number" name="quantidade_gratis[]" id="quantidade_gratis" class="form-control" placeholder="Quantidade Grátis" min="1">
                    </div>
                    <div class="me-2" style="width: 300px;">
                        <label for="produto_id_ganho">Produto Ganho</label>
                        <select name="produto_id_ganho[]" id="produto_id_ganho" class="form-control">
                            <option value="">Selecione o Produto Ganho</option>
                            @foreach ($produtos as $produto)
                                <option value="{{ $produto->produto_id }}">{{ $produto->subtitulo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn btn-success add-compre" style="width: 60px;">+</button>
                    </div>
                </div>
            </div>`;
    } else if (tipoId === '3') {
        html = `
            <h6>Compra Antecipada</h6>
            <p>Defina descontos com base no número de dias de antecedência da compra.</p>
            <div id="antecedencia" class="mt-3">
                <div class="d-flex align-items-center mb-2 antecedencia-row col-10">
                    <input type="number" name="dias_antecedencia_min_antecipada[]" class="form-control me-2" placeholder="Dias Mínimos" min="1">
                    <input type="number" name="dias_antecedencia_max_antecipada[]" class="form-control me-2" placeholder="Dias Máximos" min="1">
                    <input type="number" name="desconto_percentual_antecipada[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100">
                    <button type="button" class="btn btn-success add-antecedencia" style="width: 60px;">+</button>
                </div>
            </div>`;
    } else if (tipoId === '4') {
        html = `
            <h6>Desconto Fixo</h6>
            <p>Defina um desconto fixo para uma quantidade mínima de ingressos.</p>
            <div id="desconto-fixo">
                <div class="d-flex align-items-end mb-2 desconto-fixo-row">
                    <div class="me-2" style="width: 200px;">
                        <label for="quantidade_min_fixa">Quantidade Mínima</label>
                        <input type="number" name="quantidade_min_fixa[]" id="quantidade_min_fixa" class="form-control" placeholder="Quantidade Mínima" min="1">
                    </div>
                    <div class="me-2" style="width: 200px;">
                        <label for="desconto_fixo">Desconto (%)</label>
                        <input type="number" name="desconto_fixo[]" id="desconto_fixo" class="form-control" placeholder="Desconto (%)" min="0" max="100">
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn btn-success add-fixo" style="width: 60px;">+</button>
                    </div>
                </div>
            </div>`;
    } else if (tipoId === '5') {
        html = `
            <h6>Data Específica</h6>
            <p>Defina descontos para uma data específica com base na antecedência da compra.</p>
            <div class="mb-3">
                <label for="data_evento">Data do Evento</label>
                <input type="date" name="data_evento" id="data_evento" class="form-control" style="width: 20% !important;">
            </div>
            <div id="data_especifica" class="mt-3">
                <div class="d-flex align-items-end mb-2 data-especifica-row">
                    <div class="me-2" style="width: 150px;">
                        <label for="dias_antecedencia_min_data">Dias Mínimos</label>
                        <input type="number" name="dias_antecedencia_min_data[]" id="dias_antecedencia_min_data" class="form-control" placeholder="Dias Mínimos" min="1">
                    </div>
                    <div class="me-2" style="width: 150px;">
                        <label for="dias_antecedencia_max_data">Dias Máximos</label>
                        <input type="number" name="dias_antecedencia_max_data[]" id="dias_antecedencia_max_data" class="form-control" placeholder="Dias Máximos" min="1">
                    </div>
                    <div class="me-2" style="width: 150px;">
                        <label for="desconto_data_especifica">Desconto (%)</label>
                        <input type="number" name="desconto_data_especifica[]" id="desconto_data_especifica" class="form-control" placeholder="Desconto (%)" min="0" max="100">
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn btn-success add-data-especifica" style="width: 60px;">+</button>
                    </div>
                </div>
            </div>`;
    }

    promocaoFields.html(html);

}
function renderPromocaoFields2(tipoId, produtoData) {
    const promocaoFields = $('#promocao_fields');
    const produtosDisponiveis = @json($produtos);
    promocaoFields.empty();

    let html = '';

    // Verifica o tipo de promoção e gera os campos correspondentes
    if (String(tipoId) === '1') {  // Promoção por Nível
        html = `
            <h6>Promoção por Nível</h6>
            <p>Defina descontos com base na quantidade de ingressos comprados.</p>
            <div id="niveis" class="mt-3 col-10">
                <div class="d-flex align-items-center mb-2 nivel-row col-10">
                    <input type="number" name="quantidade_min_nivel[]" class="form-control me-2" placeholder="Quantidade Mínima" min="1">
                    <input type="number" name="quantidade_max_nivel[]" class="form-control me-2" placeholder="Quantidade Máxima" min="1">
                    <input type="number" name="desconto_percentual_nivel[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100">
                    <button type="button" class="btn btn-success add-nivel col-1" style="width: 60px;">+</button>
                </div>
            </div>`;
        // Preenche os campos com dados já existentes (se houver)
        if (produtoData.promocao_relacionada.promocao_nivel) {
            produtoData.promocao_relacionada.promocao_nivel.forEach(nivel => {
                $('#niveis').append(`
                    <div class="d-flex align-items-center mb-2 nivel-row col-10">
                        <input type="number" name="quantidade_min_nivel[]" class="form-control me-2" placeholder="Quantidade Mínima" min="1" value="${nivel.quantidade_min}">
                        <input type="number" name="quantidade_max_nivel[]" class="form-control me-2" placeholder="Quantidade Máxima" min="1" value="${nivel.quantidade_max}">
                        <input type="number" name="desconto_percentual_nivel[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100" value="${nivel.desconto_percentual}">
                        <button type="button" class="btn btn-success add-nivel col-1" style="width: 60px;">+</button>
                    </div>
                `);
            });
        }
    } else if (String(tipoId) === '2') {  // Compre e Ganhe
        html = `
            <h6>Compre e Ganhe</h6>
            <p>Defina a quantidade de ingressos necessária para ganhar outro ingresso.</p>
            <div id="compre-ganhe">
                <div class="d-flex align-items-end mb-2 compre-ganhe-row">
                    <div class="me-2" style="width: 200px;">
                        <label for="quantidade_comprada">Quantidade Comprada</label>
                        <input type="number" name="quantidade_comprada[]" id="quantidade_comprada" class="form-control" placeholder="Quantidade Comprada" min="1">
                    </div>
                    <div class="me-2" style="width: 200px;">
                        <label for="quantidade_gratis">Quantidade Grátis</label>
                        <input type="number" name="quantidade_gratis[]" id="quantidade_gratis" class="form-control" placeholder="Quantidade Grátis" min="1">
                    </div>
                    <div class="me-2" style="width: 300px;">
                        <label for="produto_id_ganho">Produto Ganho</label>
                        <select name="produto_id_ganho[]" id="produto_id_ganho" class="form-control">
                            <option value="">Selecione o Produto Ganho</option>`;
        // Popula o select com produtos existentes
        produtosDisponiveis.forEach(produto => {
            html += `<option value="${produto.produto_id}">${produto.titulo}</option>`;
        });
        html += `</select></div>
                    <div class="ms-2">
                        <button type="button" class="btn btn-success add-compre" style="width: 60px;">+</button>
                    </div>
                </div>
            </div>`;
        if (produtoData.promocao_relacionada.promocao_compre_ganhe) {
            produtoData.promocao_relacionada.promocao_compre_ganhe.forEach(item => {
                $('#compre-ganhe').append(`
                    <div class="d-flex align-items-end mb-2 compre-ganhe-row">
                        <div class="me-2" style="width: 200px;">
                            <label for="quantidade_comprada">Quantidade Comprada</label>
                            <input type="number" name="quantidade_comprada[]" class="form-control" placeholder="Quantidade Comprada" min="1" value="${item.quantidade_compra}">
                        </div>
                        <div class="me-2" style="width: 200px;">
                            <label for="quantidade_gratis">Quantidade Grátis</label>
                            <input type="number" name="quantidade_gratis[]" class="form-control" placeholder="Quantidade Grátis" min="1" value="${item.quantidade_gratis}">
                        </div>
                        <div class="me-2" style="width: 300px;">
                            <label for="produto_id_ganho">Produto Ganho</label>
                            <select name="produto_id_ganho[]" class="form-control">
                                <option value="${item.produto_id_gratis}">${item.produto_id_gratis}</option>
                            </select>
                        </div>
                        <div class="ms-2">
                            <button type="button" class="btn btn-success add-compre" style="width: 60px;">+</button>
                        </div>
                    </div>
                `);
            });
        }
    } else if (String(tipoId) === '3') {  // Compra Antecipada
        html = `
            <h6>Compra Antecipada</h6>
            <p>Defina descontos com base no número de dias de antecedência da compra.</p>
            <div id="antecedencia" class="mt-3">
                <div class="d-flex align-items-center mb-2 antecedencia-row col-10">
                    <input type="number" name="dias_antecedencia_min_antecipada[]" class="form-control me-2" placeholder="Dias Mínimos" min="1">
                    <input type="number" name="dias_antecedencia_max_antecipada[]" class="form-control me-2" placeholder="Dias Máximos" min="1">
                    <input type="number" name="desconto_percentual_antecipada[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100">
                    <button type="button" class="btn btn-success add-antecedencia" style="width: 60px;">+</button>
                </div>
            </div>`;
        if (produtoData.promocao_relacionada.promocao_compra_antecipada) {
            produtoData.promocao_relacionada.promocao_compra_antecipada.forEach(item => {
                $('#antecedencia').append(`
                    <div class="d-flex align-items-center mb-2 antecedencia-row col-10">
                        <input type="number" name="dias_antecedencia_min_antecipada[]" class="form-control me-2" placeholder="Dias Mínimos" min="1" value="${item.dias_antecedencia_min}">
                        <input type="number" name="dias_antecedencia_max_antecipada[]" class="form-control me-2" placeholder="Dias Máximos" min="1" value="${item.dias_antecedencia_max}">
                        <input type="number" name="desconto_percentual_antecipada[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100" value="${item.desconto_percentual}">
                        <button type="button" class="btn btn-success add-antecedencia" style="width: 60px;">+</button>
                    </div>
                `);
            });
        }
    } else if (String(tipoId) === '4') {  // Desconto Fixo
        console.log("Dados de Desconto Fixo:", produtoData.promocao_relacionada.promocao_desconto_fixo);

    html = `
        <h6>Desconto Fixo</h6>
        <p>Defina um desconto fixo para uma quantidade mínima de ingressos.</p>
        <div id="desconto-fixo">
        </div>`;

    // Verificar se existe dados para 'promocao_desconto_fixo'
    if (produtoData.promocao_relacionada.promocao_desconto_fixo && produtoData.promocao_relacionada.promocao_desconto_fixo.length > 0) {
        produtoData.promocao_relacionada.promocao_desconto_fixo.forEach(item => {
            console.log("Item de Desconto Fixo:", item);  // Verificar cada item sendo adicionado

            $('#desconto-fixo').append(`
                <div class="d-flex align-items-end mb-2 desconto-fixo-row">
                    <div class="me-2" style="width: 200px;">
                        <label for="quantidade_min_fixa">Quantidade Mínima</label>
                        <input type="number" name="quantidade_min_fixa[]" class="form-control" min="1" value="${item.quantidade_min || ''}">
                    </div>
                    <div class="me-2" style="width: 200px;">
                        <label for="desconto_fixo">Desconto (%)</label>
                        <input type="number" name="desconto_fixo[]" class="form-control" max="100" value="${item.desconto_percentual || ''}">
                    </div>
                    <div class="ms-2">
                        <button type="button" class="btn btn-success add-fixo" style="width: 60px;">+</button>
                    </div>
                </div>
            `);
        });
    } else {
        console.log("Não existem dados de promoção de desconto fixo.");
    }
    } else if (String(tipoId) === '5') {  // Data Específica
        html = `
            <h6>Promoção por Data Específica</h6>
            <p>Defina descontos em dias específicos.</p>
            <div id="data-especifica">
                <div class="d-flex align-items-center mb-2 data-especifica-row">
                    <input type="date" name="data_especifica[]" class="form-control me-2">
                    <input type="number" name="desconto_data_especifica[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100">
                    <button type="button" class="btn btn-success add-data-especifica" style="width: 60px;">+</button>
                </div>
            </div>`;
        if (produtoData.promocao_relacionada.promocao_data_especifica) {
            produtoData.promocao_relacionada.promocao_data_especifica.forEach(item => {
                $('#data-especifica').append(`
                    <div class="d-flex align-items-center mb-2 data-especifica-row">
                        <input type="date" name="data_especifica[]" class="form-control me-2" value="${item.data}">
                        <input type="number" name="desconto_data_especifica[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100" value="${item.desconto_percentual}">
                        <button type="button" class="btn btn-success add-data-especifica" style="width: 60px;">+</button>
                    </div>
                `);
            });
        }
    }
    // Adiciona o HTML gerado ao contêiner
    promocaoFields.html(html);
}


  

// Habilitar/desabilitar quantidade máxima
$('#select-quantidade-maxima').on('change', function () {
    const shouldEnable = $(this).val() === 'definir';
    $('#quantidade_maxima').prop('disabled', !shouldEnable);
    if (!shouldEnable) $('#quantidade_maxima').val('');
});

$('#select-quantidade-maxima-diaria').on('change', function () {
    const shouldEnable = $(this).val() === 'definir';
    $('#quantidade_maxima_diaria').prop('disabled', !shouldEnable);
    if (!shouldEnable) $('#quantidade_maxima_diaria').val('');
});

// Pré-visualização da imagem
document.getElementById('capa').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewImage = document.getElementById('preview-image');
            const previewCard = document.getElementById('preview-card');
            previewImage.src = e.target.result;
            previewCard.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});
    console.log(produtoData);
    console.log(produtoData.combo); 
    
document.addEventListener('DOMContentLoaded', function () {
    const dynamicFields = document.getElementById('dynamicFields');
    let fieldIndex = 0;

    function createRow(categoriaId = '', produtoId = '', quantidade = 1) {
        const row = document.createElement('div');
        row.className = 'd-flex my-3 align-items-center dynamic-row';
        row.innerHTML = `
            <div class="pe-2 mt-4">
                <button type="button" class="btn btn-danger remove-row-button">Remover</button>
            </div>
            <div class="col-4 form-group">
                <label for="categoria_${fieldIndex}">Categoria</label>
                <select name="categoria_produto_id[]" id="categoria_${fieldIndex}" class="form-control categoria-select" data-index="${fieldIndex}">
                    <option value="">Selecione</option>
                    ${categorias.map(categoria => `<option value="${categoria.categoria_produto_id}" ${categoria.categoria_produto_id == categoriaId ? 'selected' : ''}>${categoria.tipo_produto.descricao}</option>`).join('')}
                </select>
            </div>
            <div class="col-4 form-group ps-3">
                <label for="produto_${fieldIndex}">Produtos</label>
                <select name="produto_id[]" id="produto_${fieldIndex}" class="form-control produto-select" ${categoriaId ? '' : 'disabled'}>
                    <option value="">Selecione</option>
                    ${categoriaId ? produtos.filter(p => p.categoria_produto_id == categoriaId).map(p => `<option value="${p.produto_id}" ${p.produto_id == produtoId ? 'selected' : ''}>${p.subtitulo}</option>`).join('') : ''}
                </select>
            </div>
            <div class="col-3 ps-3">
                <label>Quantidade</label>
                <div class="input-group" style="max-width: 150px;">
                    <button type="button" class="btn btn-danger decrement" style="width: 40px;">-</button>
                    <input type="number" name="quantidade[]" value="${quantidade}" min="1" class="form-control text-center quantidade-input" style="max-width: 50px !important;">
                    <button type="button" class="btn btn-success increment" style="width: 40px;">+</button>
                </div>
            </div>
        `;
        dynamicFields.appendChild(row);
        fieldIndex++;
    }

    // Verifique se 'produtoData.combo' existe e é um array válido
    if (produtoData.combo && Array.isArray(produtoData.combo)) {
        produtoData.combo.forEach(item => {
            createRow(item.produto.categoria_produto_id, item.produto_id, item.qtd_produto);
        });
    } else {
        console.log("combo não existe ou não é um array válido.");
    }

    

    document.getElementById('addRow').addEventListener('click', () => createRow());

    // Inicializa o estado da aba de promoções
    togglePromotionsTab();

});



// Incrementar/decrementar quantidade
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('increment')) {
        const input = e.target.closest('.input-group').querySelector('.quantidade-input');
        input.value = parseInt(input.value) + 1;
    }

    if (e.target.classList.contains('decrement')) {
        const input = e.target.closest('.input-group').querySelector('.quantidade-input');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    if (e.target.classList.contains('remove-row-button')) {
        e.target.closest('.dynamic-row').remove();
    }
});

// Carregar produtos por categoria
document.getElementById('dynamicFields').addEventListener('change', function (event) {
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

$(document).ready(function () {
    // Listener para o checkbox "bilhete"
    $('#bilhete').on('change', togglePromotionsTab);

    // Listener para o select de tipo de promoção
    $('#promocao_tipo').on('change', function () {
        const tipoId = $(this).val();
        renderPromocaoFields(tipoId);
    });

    // Listeners para adicionar/remover linhas dinâmicas
    $(document).on('click', '.add-nivel', function () {
        const newNivel = `
            <div class="d-flex align-items-center mb-2 nivel-row col-10">
                <input type="number" name="quantidade_min_nivel[]" class="form-control me-2" placeholder="Quantidade Mínima" min="1">
                <input type="number" name="quantidade_max_nivel[]" class="form-control me-2" placeholder="Quantidade Máxima" min="1">
                <input type="number" name="desconto_percentual_nivel[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100">
                <button type="button" class="btn btn-danger remove-nivel col-1" style="width: 60px;">-</button>
            </div>`;
        $('#niveis').append(newNivel);
    });

    $(document).on('click', '.remove-nivel', function () {
        $(this).closest('.nivel-row').remove();
    });

    $(document).on('click', '.add-compre', function () {
        const newCompreGanhe = `
            <div class="d-flex align-items-end mb-2 compre-ganhe-row">
                <div class="me-2" style="width: 200px;">
                    <input type="number" name="quantidade_comprada[]" class="form-control" placeholder="Quantidade Comprada" min="1">
                </div>
                <div class="me-2" style="width: 200px;">
                    <input type="number" name="quantidade_gratis[]" class="form-control" placeholder="Quantidade Grátis" min="1">
                </div>
                <div class="me-2" style="width: 300px;">
                    <select name="produto_id_ganho[]" class="form-control">
                        <option value="">Selecione o Produto Ganho</option>
                        @foreach ($produtos as $produto)
                            <option value="{{ $produto->produto_id }}">{{ $produto->subtitulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ms-2">
                    <button type="button" class="btn btn-danger remove-compre" style="width: 60px;">-</button>
                </div>
            </div>`;
        $('#compre-ganhe').append(newCompreGanhe);
    });

    $(document).on('click', '.remove-compre', function () {
        $(this).closest('.compre-ganhe-row').remove();
    });

    $(document).on('click', '.add-antecedencia', function () {
        const newAntecedencia = `
            <div class="d-flex align-items-center mb-2 antecedencia-row col-10">
                <input type="number" name="dias_antecedencia_min_antecipada[]" class="form-control me-2" placeholder="Dias Mínimos" min="1">
                <input type="number" name="dias_antecedencia_max_antecipada[]" class="form-control me-2" placeholder="Dias Máximos" min="1">
                <input type="number" name="desconto_percentual_antecipada[]" class="form-control me-2" placeholder="Desconto (%)" min="0" max="100">
                <button type="button" class="btn btn-danger remove-antecedencia" style="width: 60px;">-</button>
            </div>`;
        $('#antecedencia').append(newAntecedencia);
    });

    $(document).on('click', '.remove-antecedencia', function () {
        $(this).closest('.antecedencia-row').remove();
    });

    $(document).on('click', '.add-fixo', function () {
        const newFixo = `
            <div class="d-flex align-items-end mb-2 desconto-fixo-row">
                <div class="me-2" style="width: 200px;">
                    <input type="number" name="quantidade_min_fixa[]" class="form-control" placeholder="Quantidade Mínima" min="1">
                </div>
                <div class="me-2" style="width: 200px;">
                    <input type="number" name="desconto_fixo[]" class="form-control" placeholder="Desconto (%)" min="0" max="100">
                </div>
                <div class="ms-2">
                    <button type="button" class="btn btn-danger remove-fixo" style="width: 60px;">-</button>
                </div>
            </div>`;
        $('#desconto-fixo').append(newFixo);
    });

    $(document).on('click', '.remove-fixo', function () {
        if ($('.desconto-fixo-row').length > 1) {
            $(this).closest('.desconto-fixo-row').remove();
        }
    });

    $(document).on('click', '.add-data-especifica', function () {
        const newDataEspecifica = `
            <div class="d-flex align-items-end mb-2 data-especifica-row">
                <div class="me-2" style="width: 150px;">
                    <input type="number" name="dias_antecedencia_min_data[]" class="form-control" placeholder="Dias Mínimos" min="1">
                </div>
                <div class="me-2" style="width: 150px;">
                    <input type="number" name="dias_antecedencia_max_data[]" class="form-control" placeholder="Dias Máximos" min="1">
                </div>
                <div class="me-2" style="width: 150px;">
                    <input type="number" name="desconto_data_especifica[]" class="form-control" placeholder="Desconto (%)" min="0" max="100">
                </div>
                <div class="ms-2">
                    <button type="button" class="btn btn-danger remove-data-especifica" style="width: 60px;">-</button>
                </div>
            </div>`;
        $('#data_especifica').append(newDataEspecifica);
    });

    $(document).on('click', '.remove-data-especifica', function () {
        $(this).closest('.data-especifica-row').remove();
    });

    $('.checkbox-dia').each(function (index) {
        let input = $('.preco-input').eq(index);
        if (!$(this).is(':checked')) {
            input.prop('disabled', true);
        }
    });

    $('.checkbox-dia').on('change', function () {
        let index = $('.checkbox-dia').index(this);
        let input = $('.preco-input').eq(index);
        if ($(this).is(':checked')) {
            input.prop('disabled', false);
        } else {
            input.prop('disabled', true);
            input.val('');
        }
    });

    function togglePriceFields() {
    if ($('#preco_unico').is(':checked')) {
        // Ativa o campo de Valor Único
        $('#valor_unico').prop('disabled', false);
        
        // Desativa os campos de Preço Padrão (dias específicos e checkboxes)
        $('#preco-segunda, #preco-terca, #preco-quarta, #preco-quinta, #preco-sexta, #preco-sabado, #preco-domingo, #preco-feriado').prop('disabled', true);
        $('.checkbox-dia').prop('disabled', true);
        $('#bloco-dias-da-semana').hide();
        
    } else {
        // Desativa o campo de Valor Único
        $('#valor_unico').prop('disabled', true);
        
        // Ativa os campos de Preço Padrão (dias específicos e checkboxes)
        $('#preco-segunda, #preco-terca, #preco-quarta, #preco-quinta, #preco-sexta, #preco-sabado, #preco-domingo, #preco-feriado').prop('disabled', false);
        $('.checkbox-dia').prop('disabled', false);
        $('#bloco-dias-da-semana').show();
    }
}

    togglePriceFields();
    $('#preco_unico').on('change', function () {
        togglePriceFields();
    });

    $(document).on('click', '.add-date-price', function () {
        let newField = `
        <div class="d-flex align-items-center mb-2">
            <input type="date" name="data_inicio[]" class="form-cont me-2 col-4" placeholder="Data Início">
            <input type="date" name="data_fim[]" class="form-cont me-2 col-4" placeholder="Data Fim">
            <input type="number" name="precos_especificos[]" class="form-cont col-3" placeholder="Preço">
            <button style="width: 30px;" type="button" class="btn btn-danger btn-sm remove-date-price ms-2 col-1">-</button>
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
        let quantidadeMaxima = $('#select-quantidade-maxima').val() === 'sem_limite' ? -1 : $('#quantidade_maxima').val();
        let quantidadeMaximaDiaria = $('#select-quantidade-maxima-diaria').val() === 'sem_limite' ? -1 : $('#quantidade_maxima_diaria').val();

        if (quantidadeMaxima === -1) {
            $('#quantidade_maxima').val('');
        }
        if (quantidadeMaximaDiaria === -1) {
            $('#quantidade_maxima_diaria').val('');
        }

        if ($('#titulo').val().trim() === '') {
            formValid = false;
            showError('#titulo', 'O campo título é obrigatório.');
            $('#info-tab').tab('show');
        }

        if ($('#subtitulo').val().trim() === '') {
            formValid = false;
            showError('#subtitulo', 'O campo subtítulo é obrigatório.');
            $('#info-tab').tab('show');
        }

        if ($('#termos_condicoes').val().trim() === '') {
            formValid = false;
            showError('#termos_condicoes', 'O campo termos e condições é obrigatório.');
            $('#info-tab').tab('show');
        }

        if ($('#capa')[0].files.length === 0) {
            formValid = false;
            showError('#capa', 'O upload da capa é obrigatório.');
            $('#info-tab').tab('show');
        }

        if ($('#categoria_produto_id').val().trim() === '') {
            formValid = false;
            showError('#categoria_produto_id', 'Por favor, selecione uma categoria.');
            $('#info-tab').tab('show');
        }

        if ($('#qtd_entrada_saida').val().trim() === '') {
            formValid = false;
            showError('#qtd_entrada_saida', 'O campo quantidade de entrada e saída é obrigatório.');
            $('#info-tab').tab('show');
        }

        // Validação dos produtos adicionados
        const linhasProdutos = $('#dynamicFields .dynamic-row');

        if (linhasProdutos.length > 0) {
            let produtosValidos = true;

            linhasProdutos.each(function () {
                const categoriaSelect = $(this).find('select[name="categoria_produto_id[]"]');
                const produtoSelect = $(this).find('select[name="produto_id[]"]');

                const categoriaSelecionada = categoriaSelect.val();
                const produtoSelecionado = produtoSelect.val();

                // Valida Categoria
                if (!categoriaSelecionada || categoriaSelecionada === '') {
                    produtosValidos = false;

                    categoriaSelect.addClass('is-invalid');

                    if (categoriaSelect.next('.invalid-feedback').length === 0) {
                        categoriaSelect.after('<div class="invalid-feedback">Selecione uma categoria ou remova a linha.</div>');
                    }
                } else {
                    categoriaSelect.removeClass('is-invalid');
                    categoriaSelect.next('.invalid-feedback').remove();
                }

                // Valida Produto
                if (!produtoSelecionado || produtoSelecionado === '') {
                    produtosValidos = false;

                    produtoSelect.addClass('is-invalid');

                    if (produtoSelect.next('.invalid-feedback').length === 0) {
                        produtoSelect.after('<div class="invalid-feedback">Selecione um produto ou remova a linha.</div>');
                    }
                } else {
                    produtoSelect.removeClass('is-invalid');
                    produtoSelect.next('.invalid-feedback').remove();
                }
            });

            if (!produtosValidos) {
                formValid = false;
                $('#products-tab').tab('show');
            }
        }

        if ($('#produto_fixo').is(':checked')) {
            if ($('#preco_unico').is(':checked') && $('#valor_unico').val().trim() === '') {
                formValid = false;
                showError('#valor_unico', 'O campo valor único é obrigatório.');
                $('#prices-tab').tab('show');
            } else if (!$('#preco_unico').is(':checked')) {
                let temPrecoPorData = false;
                let temPrecoPorDia = false;

                // Verifica preços por datas específicas
                $('#precos-especificos .d-flex').each(function () {
                    const dataInicio = $(this).find('input[name="data_inicio[]"]').val();
                    const dataFim = $(this).find('input[name="data_fim[]"]').val();
                    const preco = $(this).find('input[name="precos_especificos[]"]').val();

                    if (dataInicio && dataFim && preco && parseFloat(preco) > 0) {
                        temPrecoPorData = true;
                    }
                });

                // Verifica preços dos dias ativos
                $('.checkbox-dia:checked').each(function () {
                    const inputPreco = $(this).closest('.d-flex').find('.preco-input');

                    if (inputPreco.val() && parseFloat(inputPreco.val()) > 0) {
                        temPrecoPorDia = true;
                    }
                });

                // Se nenhum dos dois estiver preenchido corretamente, gera erro
                if (!temPrecoPorData && !temPrecoPorDia) {
                    formValid = false;
                    showError(
                        '#prices-content',
                        'Preencha pelo menos um preço por data específica ou um preço por dia da semana ativo.'
                    );
                    showModalError(
                        'Preencha pelo menos um preço por data específica ou um preço por dia da semana ativo.',
                        'Erro'
                    )
                    $('#prices-tab').tab('show');
                }
            }
        }

        const categoriasProdutos = [];
        document.querySelectorAll('.dynamic-row').forEach(row => {
            const categoria = row.querySelector('.categoria-select').value;
            const produto = row.querySelector('.produto-select').value;
            const quantidade = row.querySelector('.quantidade-input')?.value || 1;

            if (categoria && produto) {
                categoriasProdutos.push({
                    categoria_id: categoria,
                    produto_id: produto,
                    quantidade: parseInt(quantidade)
                });
            }
        });

        let promocoes = [];
        const nomePromocao = $('#nome_promocao').val();
        const descricaoPromocao = $('#descricao_promocao').val();
        const maximoDisponivel = $('#maximo_disponivel').val();
        const dataInicio = $('#data_inicio').val();
        const dataFim = $('#data_fim').val();
        const promocaoTipoId = $('#promocao_tipo').val();
        if (promocaoTipoId && $('#bilhete').is(':checked')) {
            if (promocaoTipoId === '1') {
                let niveis = [];
                $('input[name="quantidade_min_nivel[]"]').each(function (index) {
                    const quantidadeMin = $(this).val();
                    const quantidadeMax = $('input[name="quantidade_max_nivel[]"]').eq(index).val();
                    const desconto = $('input[name="desconto_percentual_nivel[]"]').eq(index).val();
                    if (quantidadeMin && quantidadeMax && desconto) {
                        niveis.push({
                            quantidade_min: parseInt(quantidadeMin),
                            quantidade_max: parseInt(quantidadeMax),
                            desconto_percentual: parseFloat(desconto)
                        });
                    }
                });
                if (niveis.length > 0) {
                    promocoes.push({
                        promocao_tipo_id: promocaoTipoId,
                        nome_promocao: nomePromocao,
                        descricao_promocao: descricaoPromocao,
                        maximo_disponivel: parseInt(maximoDisponivel) || null,
                        data_inicio: dataInicio,
                        data_fim: dataFim,
                        niveis: niveis
                    });
                }
            } else if (promocaoTipoId === '2') {
                let compreGanhe = [];
                $('input[name="quantidade_comprada[]"]').each(function (index) {
                    const quantidadeComprada = $(this).val();
                    const quantidadeGratis = $('input[name="quantidade_gratis[]"]').eq(index).val();
                    const produtoIdGanho = $('select[name="produto_id_ganho[]"]').eq(index).val();
                    if (quantidadeComprada && produtoIdGanho) {
                        compreGanhe.push({
                            quantidade_comprada: parseInt(quantidadeComprada),
                            quantidade_gratis: parseInt(quantidadeGratis),
                            produto_id_ganho: parseInt(produtoIdGanho)
                        });
                    }
                });
                if (compreGanhe.length > 0) {
                    promocoes.push({
                        promocao_tipo_id: promocaoTipoId,
                        nome_promocao: nomePromocao,
                        descricao_promocao: descricaoPromocao,
                        maximo_disponivel: parseInt(maximoDisponivel) || null,
                        data_inicio: dataInicio,
                        data_fim: dataFim,
                        compre_ganhe: compreGanhe
                    });
                }
            } else if (promocaoTipoId === '3') {
                let antecipadas = [];
                $('input[name="dias_antecedencia_min_antecipada[]"]').each(function (index) {
                    const diasMin = $(this).val();
                    const diasMax = $('input[name="dias_antecedencia_max_antecipada[]"]').eq(index).val();
                    const desconto = $('input[name="desconto_percentual_antecipada[]"]').eq(index).val();
                    if (diasMin && diasMax && desconto) {
                        antecipadas.push({
                            dias_antecedencia_min: parseInt(diasMin),
                            dias_antecedencia_max: parseInt(diasMax),
                            desconto_percentual: parseFloat(desconto)
                        });
                    }
                });
                if (antecipadas.length > 0) {
                    promocoes.push({
                        promocao_tipo_id: promocaoTipoId,
                        nome_promocao: nomePromocao,
                        descricao_promocao: descricaoPromocao,
                        maximo_disponivel: parseInt(maximoDisponivel) || null,
                        data_inicio: dataInicio,
                        data_fim: dataFim,
                        antecipadas: antecipadas
                    });
                }
            } else if (promocaoTipoId === '4') {
                let descontosFixos = [];
                $('input[name="quantidade_min_fixa[]"]').each(function (index) {
                    const quantidadeMin = $(this).val();
                    const desconto = $('input[name="desconto_fixo[]"]').eq(index).val();
                    if (quantidadeMin && desconto) {
                        descontosFixos.push({
                            quantidade_min: parseInt(quantidadeMin),
                            desconto_percentual: parseFloat(desconto)
                        });
                    }
                });
                if (descontosFixos.length > 0) {
                    promocoes.push({
                        promocao_tipo_id: promocaoTipoId,
                        nome_promocao: nomePromocao,
                        descricao_promocao: descricaoPromocao,
                        maximo_disponivel: parseInt(maximoDisponivel) || null,
                        data_inicio: dataInicio,
                        data_fim: dataFim,
                        descontos_fixos: descontosFixos
                    });
                }
            } else if (promocaoTipoId === '5') {
                const dataEvento = $('#data_evento').val();
                let dataEspecificas = [];
                $('input[name="dias_antecedencia_min_data[]"]').each(function (index) {
                    const diasMin = $(this).val();
                    const diasMax = $('input[name="dias_antecedencia_max_data[]"]').eq(index).val();
                    const desconto = $('input[name="desconto_data_especifica[]"]').eq(index).val();
                    if (diasMin && diasMax && desconto) {
                        dataEspecificas.push({
                            dias_antecedencia_min: parseInt(diasMin),
                            dias_antecedencia_max: parseInt(diasMax),
                            desconto_percentual: parseFloat(desconto)
                        });
                    }
                });
                if (dataEvento && dataEspecificas.length > 0) {
                    promocoes.push({
                        promocao_tipo_id: promocaoTipoId,
                        nome_promocao: nomePromocao,
                        descricao_promocao: descricaoPromocao,
                        maximo_disponivel: parseInt(maximoDisponivel) || null,
                        data_inicio: dataInicio,
                        data_fim: dataFim,
                        data_evento: dataEvento,
                        data_especificas: dataEspecificas
                    });
                }
            }
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
                if (dataInicio && dataFim && preco) {
                    datasPrecosEspecificos.push({
                        data_inicio: dataInicio,
                        data_fim: dataFim,
                        preco: preco
                    });
                }
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

            let formData = new FormData();
            formData.append('produto_id', $('#produto_id').val());
            formData.append('ativo', $('#ativo').prop('checked') ? 1 : 0);
            formData.append('produto_fixo', $('#produto_fixo').prop('checked') ? 1 : 0);
            formData.append('bilhete', $('#bilhete').prop('checked') ? 1 : 0);
            formData.append('titulo', $('#titulo').val());
            formData.append('subtitulo', $('#subtitulo').val());
            formData.append('descricao', $('#descricao').val());
            formData.append('categoria_produto_id', $('#categoria_produto_id').val());
            formData.append('termos', $('#termos_condicoes').val());
            if ($('#capa')[0].files[0]) {
                formData.append('capa', $('#capa')[0].files[0]);
            }
            formData.append('venda_qtd_max', quantidadeMaxima);
            formData.append('venda_qtd_max_diaria', quantidadeMaximaDiaria);
            formData.append('qtd_entrada_saida', $('#qtd_entrada_saida').val());
            formData.append('datasPrecosEspecificos', JSON.stringify(datasPrecosEspecificos));
            formData.append('preco_por_dia', JSON.stringify(precoPorDia));
            formData.append('valor_unico', valor_unico);
            formData.append('categorias_produtos', JSON.stringify(categoriasProdutos));
            formData.append('promocoes', JSON.stringify(promocoes));
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: '{{ route("productUpdate") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        showModal('Produto atualizado com sucesso!', 'success');
                    } else {
                        showModal('Erro ao atualizar o produto.', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    showModal('Ocorreu um erro: ' + error, 'error');
                }
            });
        }
    });

    $('#feedbackModal').on('hidden.bs.modal', function () {
        window.location.href = '{{ route("productView") }}';
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

    function showModalError(message, type) {
        const modalTitle = type === 'success' ? 'Sucesso' : 'Erro';
        const modalClass = type === 'success' ? 'text-success' : 'text-danger';
        $('#feedbackModalLabelError').text(modalTitle).removeClass('text-success text-danger').addClass(modalClass);
        $('#feedbackMessageError').text(message);
        $('#feedbackModalError').modal('show');
    }
});
</script>