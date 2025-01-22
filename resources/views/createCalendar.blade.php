@include('masks.header', ['css' => 'css/createCalendar.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex col-12 justify-content-end align-items-end">
            <button type="button" id="saveEnterprise" class="btn btn-success px-5 mt-4">Salvar</button>
        </div>
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Nova Empresa</h2>
    </div>

    <div class="d-flex flex-column align-items-center col-12">
        <div class="d-flex col-12 justify-content-center gap-5">
            <div class="d-flex">
                <select id="empresa" class="form-cont">
                    <option value="">Selecione uma empresa</option>
                    @foreach ($empresas as $empresa)
                        @if ($empresa->empresa_id != 3) 
                            <option value="{{ $empresa->empresa_id }}">{{ $empresa->nome_fantasia }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="d-flex">
                <select id="ano_fim" class="form-cont">
                    <option value="">Selecione o ano final</option>
                    @php
                        $currentYear = now()->year;  
                    @endphp
                    @for ($i = 0; $i <= 15; $i++)
                        <option value="{{ $currentYear + $i }}">{{ $currentYear + $i }}</option>
                    @endfor
                </select>
            </div>
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
    $(document).ready(function () {
        $('#saveEnterprise').click(function () {
            var empresaId = $('#empresa').val();
            var anoFim = $('#ano_fim').val();  

            if (empresaId === "" || anoFim === "") {
                var errorModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                $('#feedbackMessage').text('Por favor, preencha todos os campos.');
                errorModal.show();
                return;
            }

            $.ajax({
                url: '/newCalendarSave',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    empresaId: empresaId,
                    ano_fim: anoFim  
                },
                success: function (response) {
                    var successModal = new bootstrap.Modal(document.getElementById('feedbackModal'));

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
                error: function (xhr, status, error) {
                    var errorModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    $('#feedbackMessage').text('Erro ao salvar empresa.');
                    errorModal.show();
                }
            });
        });
    });
</script>
