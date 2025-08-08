@include('masks.header', ['css' => 'css/enterpriseData.css'])

<div class="d-flex flex-column col-11 align-items-center my-4">
    <div class="d-flex justify-content-center col-10">
        <h2 class="my-4">Layout</h2>
    </div>

    <div id="data" class="d-flex flex-column col-10">
        <div class="container mt-5">
            <div class="row">
                @foreach ($colunas as $campo)
                    @php
                        $imgPath = isset($empresa->empresaImg->{$campo}) 
		    ? str_replace('http://', 'https://', $empresa->empresaImg->{$campo}) 
		    : 'https://via.placeholder.com/300x200.png?text=No+Image';
                        $label = ucfirst(str_replace('_', ' ', $campo)); 
                        if (stripos($campo, 'footer') !== false) {
                            $label = 'Rodapé';
                        } elseif (stripos($campo, 'header') !== false) {
                            $label = 'Cabeçalho';
                        } else {
                            $label = ucfirst(str_replace('_', ' ', $campo));
                        }
                        $imgSize = $imgTamanho->{$campo} ?? 'Tamanho não especificado'; 
                    @endphp
                    <div class="col-4 image-card">
                        <div class="card">
                            <img alt="{{ $label }}" class="card-img-top" height="200" src="{{ $imgPath }}" width="300" />
                            <div class="card-body">
                                <h5 class="card-title">{{ $label }}</h5>
                                <p><strong>Tamanho recomendado:</strong> <span class="text-danger" >{{ $imgSize }}</span> </p> 
                                <div>
                                    @csrf
                                    <div class="mb-3">

                                        <input class="form-control" id="{{ $campo }}Input" type="file" name="image" />
                                    </div>
                                    <button class="btn btn-primary" type="button">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const uploadButtons = document.querySelectorAll(".btn-primary[type='button']");

        uploadButtons.forEach(button => {
            button.addEventListener("click", () => {
                const card = button.closest(".image-card");
                const input = card.querySelector("input[type='file']");
                const campo = input.id.replace("Input", ""); // Obter o nome do campo a partir do ID
                const formData = new FormData();

                // Verificar se há um arquivo selecionado
                if (input.files.length === 0) {
                    showModal("Erro", "Por favor, selecione um arquivo antes de fazer o upload.");
                    return;
                }

                // Adicionar os dados ao FormData
                formData.append("image", input.files[0]);
                formData.append("_token", document.querySelector("input[name='_token']").value); // CSRF Token
                formData.append("campo", campo);

                // Enviar via AJAX
                fetch("{{ route('saveLayout') }}", {
                    method: "POST",
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showModal("Sucesso", `Imagem para "${campo}" foi enviada com sucesso!`);
                            // Atualizar o caminho da imagem no card
                            const img = card.querySelector("img");
                            img.src = data.image_path;
                        } else {
                            showModal("Erro", data.message || "Ocorreu um erro ao enviar a imagem.");
                        }
                    })
                    .catch(error => {
                        console.error("Erro ao enviar a imagem:", error);
                        showModal("Erro", "Erro inesperado. Por favor, tente novamente.");
                    });
            });
        });

        // Função para mostrar o modal de feedback
        function showModal(title, message) {
            const modalTitle = document.getElementById("feedbackModalLabel");
            const modalMessage = document.getElementById("feedbackMessage");

            modalTitle.textContent = title;
            modalMessage.textContent = message;

            const feedbackModal = new bootstrap.Modal(document.getElementById("feedbackModal"));
            feedbackModal.show();
        }
    });

</script>