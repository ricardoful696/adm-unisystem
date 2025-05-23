@include('masks.header', ['css' => 'css/userDev.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Novo Revendedor</h2>
    </div>

    <div class="d-flex flex-column align-items-center col-12">
        <div class="d-flex col-12 justify-content-center gap-5">
            <div class="d-flex">
                <div class=" ">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" maxlength="100" required>
                </div>
            </div>
            <div class="d-flex">
                <div class="">
                    <label for="login">Login</label>
                    <input type="text" name="login" id="login" class="form-control" maxlength="100">
                </div>
            </div>
            <div class="d-flex flex-column">
                <label for="empresa_id" class="me-2">Empresa</label>
                <select name="empresa_id" id="empresa_id" class="form-select" required>
                    <option value="">Selecione uma empresa</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->empresa_id }}">{{ $empresa->nome_fantasia }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex col-8 justify-content-end align-items-end">
            <button type="button" id="saveUser"  class="btn btn-primary mt-4">Salvar Revendedor</button>
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
        $('#empresa_id').change(function() {
            var empresa_id = $(this).val();
            console.log(empresa_id); // Verifique no console
        });
        $('#saveUser').click(function() { 
            var nome = $('#nome').val();
            var login = $('#login').val();
            var empresa_id = $('#empresa_id').val(); 
            var tipo_usuario_id = 4;

            if (!nome || !login || !empresa_id) {
                var errorModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                $('#feedbackMessage').text('Todos os campos devem ser preenchidos corretamente.');
                errorModal.show();
                return;
            }

            $.ajax({
                url: '/saveUserDev',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    nome: nome,
                    login: login,
                    empresa_id: empresa_id,
                    tipo_usuario_id
                },
                success: function(response) {
                    var successModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    
                    if (response.success) {
                        $('#feedbackMessage').text(response.message);
                        successModal.show();
                    } else {
                        var errorModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                        $('#feedbackMessage').text('Erro ao salvar revendedor.');
                        errorModal.show();
                    }
                    successModal._element.addEventListener('hidden.bs.modal', function () {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    var errorModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    $('#feedbackMessage').text('Erro ao salvar revendedor.');
                    errorModal.show();
                }
            });
        });
    });
</script>



