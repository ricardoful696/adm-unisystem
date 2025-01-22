@include('masks.header', ['css' => 'css/newCampaign.css'])

<div class="d-flex flex-column col-11 align-items-center">
    <div class="d-flex justify-content-center flex-column col-12 col-md-10 align-items-center">
        <div class="my-5">
            <h2>Categorias</h2>
        </div>
        <div class="d-flex justify-content-center my-4 col-6 ">
            <input type="text" id="newCategoryInput" class="form-control" placeholder="Nova Categoria">
            <button class="btn btn-success ms-2" id="addCategoryButton">Adicionar</button>
        </div>
        <div class="d-flex flex-column justify-content-center my-4 col-10">
            <h5 class="text-center">Categorias Existentes</h5>
            <ul id="categoriesList" class="list-group col-6 align-self-center">
                @foreach($categorias as $categoria)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <input type="text" class="form-control category-name" value="{{ $categoria->tipoProduto->descricao }}" data-id="{{ $categoria->tipoProduto->tipo_produto_id }}" disabled>
                        <button class="btn btn-warning btn-sm ms-2 editCategoryButton">Editar</button>
                        <button class="btn btn-success btn-sm ms-2 saveCategoryButton" style="display:none;">Salvar</button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@include('masks.footer')

<script>
    $(document).ready(function () {
        $('#addCategoryButton').on('click', function () {
            const categoryName = $('#newCategoryInput').val();

            if (!categoryName.trim()) {
                bootbox('Digite um nome para a categoria.');
                return;
            }

            $.ajax({
                url: '/categorySave', 
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    nome: categoryName
                },
                success: function (response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        bootbox('Erro ao adicionar categoria. Tente novamente.');
                    }
                },
                error: function () {
                    bootbox('Erro ao adicionar categoria. Verifique sua conexão ou tente novamente.');
                }
            });
        });

        $('.editCategoryButton').on('click', function () {
            const listItem = $(this).closest('li');
            const inputField = listItem.find('.category-name');
            const saveButton = listItem.find('.saveCategoryButton');
            const editButton = $(this);

            inputField.prop('disabled', false);
            saveButton.show();
            editButton.hide();
        });

        $('.saveCategoryButton').on('click', function () {
            const listItem = $(this).closest('li');
            const categoryId = listItem.find('.category-name').data('id');
            const newCategoryName = listItem.find('.category-name').val().trim();

            if (!newCategoryName) {
                bootbox('Digite um nome para a categoria.');
                return;
            }

            $.ajax({
                url: '/categoryEdit', 
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: categoryId,
                    nome: newCategoryName
                },
                success: function (response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        bootbox('Erro ao editar categoria. Tente novamente.');
                    }
                },
                error: function () {
                    bootbox('Erro ao editar categoria. Verifique sua conexão ou tente novamente.');
                }
            });
        });
    });
</script>
