@include('masks.header', ['css' => 'css/myAccount.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="text-end col-11">
        <button type="button" id="saveUserButton" class="btn btn-success px-5 mt-4">Salvar</button>
    </div>
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Editar Usuário</h2>
    </div>

    <div id="userForm" class="col-10 d-flex flex-column">
        <div class="d-flex gap-3 justify-content-center">
            <div class="col-6">
                <div class="form-group my-3">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" maxlength="50"
                        value="{{ old('nome', $usuario->nome) }}" required>
                </div>

                <div class="form-group my-3">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" name="sobrenome" id="sobrenome" class="form-control" maxlength="50"
                        value="{{ old('sobrenome', $usuario->sobrenome) }}">
                </div>

                <div class="form-group my-3">
                    <label for="documento">Documento</label>
                    <input type="text" name="documento" id="documento" class="form-control" maxlength="25"
                        value="{{ old('documento', $usuario->documento) }}">
                </div>

                <div class="form-group my-3">
                    <label for="sexo">Sexo</label>
                    <select name="sexo" id="sexo" class="form-control">
                        <option value="Masculino" {{ old('sexo', $usuario->sexo) == 'Masculino' ? 'selected' : '' }}>
                            Masculino</option>
                        <option value="Feminino" {{ old('sexo', $usuario->sexo) == 'Feminino' ? 'selected' : '' }}>
                            Feminino</option>
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group my-3">
                    <label for="telefone">Telefone</label>
                    <input type="text" name="telefone" id="telefone" class="form-control" maxlength="25"
                        value="{{ old('telefone', $usuario->telefone) }}">
                </div>

                <div class="form-group my-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" maxlength="50"
                        value="{{ old('email', $usuario->email) }}">
                </div>

                <div class="form-group my-3">
                    <label for="data_nascimento">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" class="form-control"
                        value="{{ old('data_nascimento', $usuario->data_nascimento) }}">
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
        $('#saveUserButton').click(function () {
            let formData = {
                nome: $('#nome').val(),
                sobrenome: $('#sobrenome').val(),
                documento: $('#documento').val(),
                sexo: $('#sexo').val(),
                telefone: $('#telefone').val(),
                email: $('#email').val(),
                data_nascimento: $('#data_nascimento').val(),
            };

            $.ajax({
                url: "{{ route('myAccountUpdate') }}",
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#feedbackMessage').text(response.message);
                    $('#feedbackModal').modal('show');
                },
                error: function (xhr) {
                    let errorMessage = xhr.responseJSON?.message || "Erro ao atualizar usuário.";
                    $('#feedbackMessage').text(errorMessage);
                    $('#feedbackModal').modal('show');
                }
            });
        });
    });
</script>