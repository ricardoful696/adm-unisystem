@include('masks.header', ['css' => 'css/enterpriseDev.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Nova Empresa</h2>
    </div>

    <div class="d-flex flex-column align-items-center col-12">
        <div class="d-flex col-12 justify-content-center gap-5">
            <div class="d-flex">
                <div class="">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" name="cnpj" id="cnpj" class="form-control" maxlength="25" required>
                </div>
            </div>
            <div class="d-flex">
                <div class=" ">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" maxlength="100" required>
                </div>
            </div>
            <div class="d-flex">
                <div class="">
                    <label for="nome_fantasia">Nome Fantasia</label>
                    <input type="text" name="nome_fantasia" id="nome_fantasia" class="form-control" maxlength="100">
                </div>
            </div>
        </div>
        <div class="d-flex col-8 justify-content-end align-items-end">
            <button type="button" id="saveEnterprise" class="btn btn-primary mt-4">Salvar Empresa</button>
        </div>
    </div>
</div>

<!-- MODAL -->
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
    $(document).ready(function() {
        $('#saveEnterprise').click(function() {
            var nome = $('#nome').val();
            var nome_fantasia = $('#nome_fantasia').val();
            var cnpj = $('#cnpj').val();

            if (!nome || !nome_fantasia || !cnpj) {
                var errorModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                $('#feedbackMessage').text('Todos os campos devem ser preenchidos corretamente.');
                errorModal.show();
                return;
            }

            $.ajax({
                url: '/saveEnterpriseDev',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    nome,
                    nome_fantasia,
                    cnpj,
                },
                success: function(response) {
                    var successModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    
                    // Verifica se a resposta indica sucesso
                    if (response.success) {
                        $('#feedbackMessage').text(response.message);
                        successModal.show();
                    } else {
                        var errorModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                        $('#feedbackMessage').text('Erro ao salvar empresa.');
                        errorModal.show();
                    }
                    successModal._element.addEventListener('hidden.bs.modal', function () {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    var errorModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    $('#feedbackMessage').text('Erro ao salvar empresa.');
                    errorModal.show();
                }
            });
        });
    });
</script>


