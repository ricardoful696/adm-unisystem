@include('masks.header', ['css' => 'css/enterpriseData.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex col-11 justify-content-end mt-4">
        <button type="button" id="salvarAlteracoes" class="btn btn-success px-5">Salvar</button>
    </div>
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Dados da Empresa</h2>
    </div>

    <div id="data" class="d-flex flex-column col-10">
        <div class="form-check my-3">
            <input type="checkbox" class="form-check-input" id="ativo" name="ativo" {{ $empresa->ativo ? 'checked' : '' }}>
            <label for="ativo" class="form-check-label">Empresa Ativa</label>
        </div>
        <div class="d-flex justify-content-center gap-3">
            <div class="col-5">
                <div class="form-group my-3">
                    <label for="nome">Nome da Empresa</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="{{ $empresa->nome }}" required>
                </div>
                <div class="form-group my-3">
                    <label for="nome_fantasia">Nome Fantasia</label>
                    <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia"
                        value="{{ $empresa->nome_fantasia }}">
                </div>
                <div class="form-group my-3">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj" value="{{ $empresa->cnpj }}" required>
                </div>
                <div class="form-group my-3">
                    <label for="telefone1">Telefone 1</label>
                    <input type="text" class="form-control" id="telefone1" name="telefone1"
                        value="{{ $empresa->telefone1 }}">
                </div>
                <div class="form-group my-3">
                    <label for="telefone2">Telefone 2</label>
                    <input type="text" class="form-control" id="telefone2" name="telefone2"
                        value="{{ $empresa->telefone2 }}">
                </div>
                <div class="form-group my-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $empresa->email }}"
                        required>
                </div>
            </div>
            <div class="col-5">
                <div class="form-group my-3">
                    <label for="cep">CEP</label>
                    <input type="text" class="form-control" id="cep" name="cep" value="{{ $empresa->cep }}">
                </div>
                <div class="form-group my-3">
                    <label for="estado">Estado</label>
                    <input type="text" class="form-control" id="estado" name="estado" value="{{ $empresa->estado }}">
                </div>
                <div class="form-group my-3">
                    <label for="cidade">Cidade</label>
                    <input type="text" class="form-control" id="cidade" name="cidade" value="{{ $empresa->cidade }}">
                </div>
                <div class="form-group my-3">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control" id="endereco" name="endereco"
                        value="{{ $empresa->endereco }}">
                </div>
                <div class="form-group my-3">
                    <label for="endereco_numero">Número</label>
                    <input type="text" class="form-control" id="endereco_numero" name="endereco_numero"
                        value="{{ $empresa->endereco_numero }}">
                </div>
                <div class="form-group my-3">
                    <label for="endereco_complemento">Complemento</label>
                    <input type="text" class="form-control" id="endereco_complemento" name="endereco_complemento"
                        value="{{ $empresa->endereco_complemento }}">
                </div>
            </div>
        </div>

        <div class="d-flex flex-column">
            <div class="d-flex justify-content-center gap-3">
                <div class="col-5">
                    <div class="form-group my-3">
                        <label for="url_capa">URL da Capa</label>
                        <input type="url" class="form-control" id="url_capa" name="url_capa">
                    </div>
                    <div class="form-group my-3">
                        <label for="url_site">Site</label>
                        <input type="url" class="form-control" id="url_site" name="url_site"
                            value="{{ $empresa->url_site }}">
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group my-3">
                        <label for="url_facebook">Facebook</label>
                        <input type="url" class="form-control" id="url_facebook" name="url_facebook"
                            value="{{ $empresa->url_facebook }}">
                    </div>
                    <div class="form-group my-3">
                        <label for="url_instagram">Instagram</label>
                        <input type="url" class="form-control" id="url_instagram" name="url_instagram"
                            value="{{ $empresa->url_instagram }}">
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

<style>
    .error-message {
        color: red;
        font-size: 0.875rem;
    }
</style>

<script>
    $(document).ready(function () {
        $('#salvarAlteracoes').click(function (e) {
            e.preventDefault();

            $('.error-message').remove(); // Limpa mensagens de erro anteriores

            const isAtivo = $('#ativo').is(':checked');
            let isValid = true;

            const camposObrigatorios = [
                { id: 'nome', label: 'Nome da Empresa' },
                { id: 'nome_fantasia', label: 'Nome Fantasia' },
                { id: 'cnpj', label: 'CNPJ' },
                { id: 'telefone1', label: 'Telefone 1' },
                { id: 'email', label: 'Email' },
                { id: 'cep', label: 'CEP' },
                { id: 'estado', label: 'Estado' },
                { id: 'cidade', label: 'Cidade' },
                { id: 'endereco', label: 'Endereço' },
                { id: 'endereco_numero', label: 'Número' },
            ];

            // Se estiver ativo, valida campos obrigatórios
            if (isAtivo) {
                camposObrigatorios.forEach(field => {
                    const valor = $(`#${field.id}`).val().trim();
                    if (!valor) {
                        isValid = false;
                        $(`#${field.id}`).after(`<div class="error-message">O campo ${field.label} é obrigatório.</div>`);
                    }
                });

                const email = $('#email').val().trim();
                if (email && !isValidEmail(email)) {
                    isValid = false;
                    $('#email').after(`<div class="error-message">Digite um e-mail válido.</div>`);
                }

                const cnpj = $('#cnpj').val().trim();
                if (cnpj && !isValidCNPJ(cnpj)) {
                    isValid = false;
                    $('#cnpj').after(`<div class="error-message">CNPJ inválido.</div>`);
                }
            }

            if (!isValid) return; // Interrompe o envio se houver erro

            const dados = {
                ativo: isAtivo,
                nome: $('#nome').val(),
                nome_fantasia: $('#nome_fantasia').val(),
                cnpj: $('#cnpj').val(),
                telefone1: $('#telefone1').val(),
                telefone2: $('#telefone2').val(),
                email: $('#email').val(),
                cep: $('#cep').val(),
                estado: $('#estado').val(),
                cidade: $('#cidade').val(),
                endereco: $('#endereco').val(),
                endereco_numero: $('#endereco_numero').val(),
                endereco_complemento: $('#endereco_complemento').val(),
                url_capa: $('#url_capa').val(),
                url_site: $('#url_site').val(),
                url_facebook: $('#url_facebook').val(),
                url_instagram: $('#url_instagram').val(),
                _token: '{{ csrf_token() }}',
            };

            $.ajax({
                url: '{{ route("enterpriseDataUpdate") }}',
                method: 'POST',
                data: dados,
                success: function (response) {
                    if (response.message) {
                        showModal(response.message, 'success');
                    } else {
                        showModal('Erro desconhecido ao salvar os dados.', 'error');
                    }
                },
                error: function (xhr, status, error) {
                    const responseJSON = xhr.responseJSON;
                    if (responseJSON && responseJSON.error) {
                        showModal(responseJSON.error, 'error');
                    } else {
                        showModal('Ocorreu um erro inesperado: ' + error, 'error');
                    }
                }
            });
        });

        function showModal(message, type) {
            document.getElementById('feedbackMessage').innerText = message;

            const modalTitle = document.getElementById('feedbackModalLabel');
            if (type === 'success') {
                modalTitle.innerText = 'Sucesso';
                modalTitle.classList.remove('text-danger');
                modalTitle.classList.add('text-success');
            } else {
                modalTitle.innerText = 'Erro';
                modalTitle.classList.remove('text-success');
                modalTitle.classList.add('text-danger');
            }

            const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
            feedbackModal.show();
        }

        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        function isValidCNPJ(cnpj) {
            // cnpj = cnpj.replace(/[^\d]+/g, '');
            // if (cnpj === '') return false;
            // if (cnpj.length !== 14) return false;
            // if (/^(\d)\1+$/.test(cnpj)) return false;

            // let tamanho = cnpj.length - 2;
            // let numeros = cnpj.substring(0, tamanho);
            // let digitos = cnpj.substring(tamanho);
            // let soma = 0;
            // let pos = tamanho - 7;
            // for (let i = tamanho; i >= 1; i--) {
            //     soma += numeros.charAt(tamanho - i) * pos--;
            //     if (pos < 2) pos = 9;
            // }
            // let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            // if (resultado !== parseInt(digitos.charAt(0))) return false;

            // tamanho = tamanho + 1;
            // numeros = cnpj.substring(0, tamanho);
            // soma = 0;
            // pos = tamanho - 7;
            // for (let i = tamanho; i >= 1; i--) {
            //     soma += numeros.charAt(tamanho - i) * pos--;
            //     if (pos < 2) pos = 9;
            // }
            // resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            // if (resultado !== parseInt(digitos.charAt(1))) return false;

            return true;
        }
    });
</script>
